<template>
  <div class="min-h-screen bg-slate-900 flex items-start justify-center">
    <!-- Mobile Container -->
    <div class="w-full max-w-md min-h-screen bg-slate-50 pb-20 relative shadow-2xl">
      <!-- Header -->
      <header class="bg-white shadow-sm sticky top-0 z-10">
        <div class="px-4 py-4 flex items-center justify-between">
          <div>
            <h1 class="text-xl font-bold text-green-600">GO BAKULA</h1>
            <p class="text-xs text-slate-500">Barito Kuala</p>
          </div>
          
          <div class="flex items-center gap-2">
            <button class="p-2 rounded-xl hover:bg-slate-50 relative">
              <span class="text-2xl">🔔</span>
              <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>
            
            <!-- User Menu Button -->
            <button 
              @click="showUserMenu = !showUserMenu"
              class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-semibold"
            >
              {{ userInitial }}
            </button>
          </div>
        </div>

        <!-- User Dropdown Menu -->
        <div 
          v-if="showUserMenu"
          class="absolute top-16 right-4 bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden z-20 min-w-[200px]"
        >
          <div class="px-4 py-3 border-b border-slate-200">
            <p class="text-sm font-semibold text-slate-800">{{ $page.props.auth.user?.name }}</p>
            <p class="text-xs text-slate-500">{{ userRole }}</p>
          </div>
          <Link
            :href="route('profile.edit')"
            class="flex items-center px-4 py-3 hover:bg-slate-50 transition text-slate-700"
            @click="showUserMenu = false"
          >
            <span class="text-lg mr-3">👤</span>
            <span class="text-sm font-medium">Profile</span>
          </Link>
          <Link
            :href="route('logout')"
            method="post"
            as="button"
            class="flex items-center w-full px-4 py-3 hover:bg-red-50 transition text-red-600"
          >
            <span class="text-lg mr-3">🚪</span>
            <span class="text-sm font-medium">Logout</span>
          </Link>
        </div>
      </header>

      <!-- Page Content -->
      <main class="p-4">
        <slot />
      </main>

      <!-- Bottom Navigation -->
      <nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-md bg-white border-t border-slate-200 shadow-lg">
        <div class="flex items-center justify-around py-2">
          <Link
            v-for="item in navigation"
            :key="item.name"
            :href="item.href"
            class="flex flex-col items-center py-2 px-4 rounded-xl transition"
            :class="isActive(item.href) ? 'text-green-600' : 'text-slate-400'"
          >
            <span class="text-2xl mb-1">{{ item.icon }}</span>
            <span class="text-xs font-medium">{{ item.name }}</span>
          </Link>
        </div>
      </nav>

      <!-- FAB (Floating Action Button) for Create Report -->
      <Link
        v-if="showFAB"
        href="/reports/create"
        class="fixed bottom-20 right-4 w-16 h-16 bg-green-600 text-white rounded-full shadow-lg flex items-center justify-center hover:bg-green-700 transition z-20"
        style="transform: translateX(calc(-50vw + 50% + 224px));"
      >
        <span class="text-3xl">+</span>
      </Link>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const showUserMenu = ref(false);

// Route helper
const route = (name, params) => {
  return window.route ? window.route(name, params) : `/${name}`;
};

const props = defineProps({
  showFAB: {
    type: Boolean,
    default: true
  }
});

const userInitial = computed(() => {
  return page.props.auth.user?.name?.charAt(0).toUpperCase() || 'U';
});

const userRole = computed(() => {
  const roles = page.props.auth.user?.roles;
  if (roles?.includes('super_admin')) return 'Super Admin';
  if (roles?.includes('admin_dinas')) return 'Admin Dinas';
  return 'Warga';
});

const navigation = computed(() => {
  const user = page.props.auth.user;
  const isSuperAdmin = user?.roles?.includes('super_admin');
  const isAdminDinas = user?.roles?.includes('admin_dinas');
  
  const items = [
    { name: 'Beranda', href: '/dashboard', icon: '🏠' },
    { name: 'Laporan', href: '/reports', icon: '📝' },
  ];

  if (!isSuperAdmin && !isAdminDinas) {
    items.push({ name: 'Saya', href: '/reports/my-reports', icon: '📋' });
  }

  items.push({ name: 'Ranking', href: '/leaderboard', icon: '🏆' });

  if (isSuperAdmin) {
    items.push({ name: 'Admin', href: '/departments', icon: '⚙️' });
  }

  return items;
});

const isActive = (href) => {
  return page.url.startsWith(href);
};
</script>
