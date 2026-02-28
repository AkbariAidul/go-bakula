<template>
  <div class="min-h-screen bg-slate-50 pb-20">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-10">
      <div class="px-4 py-4 flex items-center justify-between">
        <div>
          <h1 class="text-xl font-bold text-green-600">GO BAKULA</h1>
          <p class="text-xs text-slate-500">Barito Kuala</p>
        </div>
        
        <button class="p-2 rounded-xl hover:bg-slate-50 relative">
          <span class="text-2xl">🔔</span>
          <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
        </button>
      </div>
    </header>

    <!-- Page Content -->
    <main class="p-4">
      <slot />
    </main>

    <!-- Bottom Navigation -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 shadow-lg">
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
    >
      <span class="text-3xl">+</span>
    </Link>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();

const props = defineProps({
  showFAB: {
    type: Boolean,
    default: true
  }
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
