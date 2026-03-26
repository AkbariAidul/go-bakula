<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 flex items-start justify-center">
    <!-- Mobile Container -->
    <div class="w-full max-w-md min-h-screen bg-white pb-20 relative shadow-2xl">
      <!-- Header -->
      <header class="bg-white border-b border-slate-100 sticky top-0 z-10">
        <div class="px-4 py-3 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <img src="/images/logo.png" alt="GO BAKULA" class="h-8" />
          </div>
          
          <div class="flex items-center gap-2">
            <button class="p-2 rounded-xl hover:bg-slate-50 relative transition">
              <span class="text-xl">🔔</span>
              <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>
            
            <!-- User Menu Button -->
            <button 
              @click="showUserMenu = !showUserMenu"
              class="w-9 h-9 rounded-full bg-gradient-to-br from-primary-800 to-primary-900 flex items-center justify-center text-white font-bold text-sm shadow-sm"
            >
              {{ userInitial }}
            </button>
          </div>
        </div>

        <!-- User Dropdown Menu -->
        <div 
          v-if="showUserMenu"
          class="absolute top-14 right-4 bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden z-20 min-w-[200px]"
        >
          <div class="px-4 py-3 bg-gradient-to-br from-primary-50 to-primary-100 border-b border-primary-200">
            <p class="text-sm font-bold text-slate-800">{{ $page.props.auth.user?.name }}</p>
            <p class="text-xs text-slate-600">{{ userRole }}</p>
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
            class="flex items-center w-full px-4 py-3 hover:bg-red-50 transition text-red-600 border-t border-slate-100"
          >
            <span class="text-lg mr-3">🚪</span>
            <span class="text-sm font-medium">Logout</span>
          </Link>
        </div>
      </header>

      <!-- Page Content -->
      <main class="p-4 bg-slate-50 min-h-screen">
        <slot />
      </main>

      <!-- Bottom Navigation -->
      <nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-md bg-white border-t border-slate-200 shadow-lg">
        <div class="flex items-center justify-around px-2 py-2">
          <Link
            v-for="item in navigation"
            :key="item.name"
            :href="item.href"
            class="flex flex-col items-center py-2 px-3 rounded-xl transition-all"
            :class="isActive(item.href) 
              ? 'text-primary-900 bg-primary-50' 
              : 'text-slate-400 hover:text-slate-600 hover:bg-slate-50'"
          >
            <span class="text-2xl mb-0.5">{{ item.icon }}</span>
            <span class="text-xs font-semibold">{{ item.name }}</span>
          </Link>
        </div>
      </nav>

      <!-- FAB (Floating Action Button) for Create Report -->
      <Link
        v-if="props.showFAB"
        href="/reports/create"
        class="fixed bottom-20 right-4 w-14 h-14 bg-gradient-to-br from-primary-800 to-primary-900 text-white rounded-full shadow-lg flex items-center justify-center hover:shadow-xl transition-all z-20 hover:scale-105"
        style="transform: translateX(calc(-50vw + 50% + 224px));"
      >
        <span class="text-2xl font-bold">+</span>
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
