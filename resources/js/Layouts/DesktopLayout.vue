<template>
  <div class="min-h-screen bg-slate-50 flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-sm fixed h-full">
      <div class="p-6 border-b border-slate-200">
        <h1 class="text-2xl font-bold text-green-600">GO BAKULA</h1>
        <p class="text-xs text-slate-500 mt-1">Barito Kuala</p>
      </div>

      <nav class="p-4">
        <Link 
          v-for="item in navigation" 
          :key="item.name"
          :href="item.href"
          class="flex items-center px-4 py-3 mb-2 rounded-xl transition"
          :class="isActive(item.href) ? 'bg-green-50 text-green-600' : 'text-slate-600 hover:bg-slate-50'"
        >
          <span class="text-xl mr-3">{{ item.icon }}</span>
          <span class="font-medium">{{ item.name }}</span>
        </Link>
      </nav>

      <div class="absolute bottom-0 w-64 p-4 border-t border-slate-200">
        <div class="relative">
          <button 
            @click="showUserMenu = !showUserMenu"
            class="flex items-center w-full hover:bg-slate-50 p-2 rounded-xl transition"
          >
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-semibold">
              {{ userInitial }}
            </div>
            <div class="ml-3 flex-1 text-left">
              <p class="text-sm font-semibold text-slate-800">{{ $page.props.auth.user?.name }}</p>
              <p class="text-xs text-slate-500">{{ userRole }}</p>
            </div>
            <span class="text-slate-400">{{ showUserMenu ? '▲' : '▼' }}</span>
          </button>

          <!-- Dropdown Menu -->
          <div 
            v-if="showUserMenu"
            class="absolute bottom-full left-0 right-0 mb-2 bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden"
          >
            <Link
              :href="route('profile.edit')"
              class="flex items-center px-4 py-3 hover:bg-slate-50 transition text-slate-700"
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
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 ml-64">
      <!-- Topbar -->
      <header class="bg-white shadow-sm sticky top-0 z-10">
        <div class="px-8 py-4 flex items-center justify-between">
          <h2 class="text-xl font-semibold text-slate-800">{{ pageTitle }}</h2>
          
          <div class="flex items-center space-x-4">
            <button class="p-2 rounded-xl hover:bg-slate-50 relative">
              <span class="text-2xl">🔔</span>
              <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="p-8">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';

const page = usePage();
const showUserMenu = ref(false);

// Route helper
const route = (name, params) => {
  return window.route ? window.route(name, params) : `/${name}`;
};

const props = defineProps({
  pageTitle: {
    type: String,
    default: 'Dashboard'
  }
});

const navigation = computed(() => {
  const user = page.props.auth.user;
  const isSuperAdmin = user?.roles?.includes('super_admin');
  const isAdminDinas = user?.roles?.includes('admin_dinas');
  
  const items = [
    { name: 'Dashboard', href: '/dashboard', icon: '📊' },
    { name: 'Laporan', href: '/reports', icon: '📝' },
  ];

  if (!isSuperAdmin && !isAdminDinas) {
    items.push({ name: 'Laporan Saya', href: '/reports/my-reports', icon: '📋' });
  }

  items.push({ name: 'Leaderboard', href: '/leaderboard', icon: '🏆' });

  if (isSuperAdmin) {
    items.push(
      { name: 'Dinas', href: '/departments', icon: '🏢' },
      { name: 'Kategori', href: '/categories', icon: '📁' }
    );
  }

  return items;
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

const isActive = (href) => {
  return page.url.startsWith(href);
};
</script>
