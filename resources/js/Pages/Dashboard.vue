<template>
  <component :is="layoutComponent" page-title="Dashboard">
    <!-- Stats Cards -->
    <div class="grid grid-cols-2 gap-3 mb-6">
      <!-- Total -->
      <div class="bg-white rounded-2xl shadow-sm p-4">
        <div class="flex items-center justify-between mb-2">
          <span class="text-2xl">📊</span>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ stats.total_reports }}</p>
        <p class="text-xs text-slate-500 mt-1">Total Laporan</p>
      </div>

      <!-- Pending -->
      <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-2xl shadow-sm p-4">
        <div class="flex items-center justify-between mb-2">
          <span class="text-2xl">⏳</span>
        </div>
        <p class="text-2xl font-bold text-yellow-700">{{ stats.pending_reports }}</p>
        <p class="text-xs text-yellow-600 mt-1">Menunggu</p>
      </div>

      <!-- Progress -->
      <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl shadow-sm p-4">
        <div class="flex items-center justify-between mb-2">
          <span class="text-2xl">🔄</span>
        </div>
        <p class="text-2xl font-bold text-blue-700">{{ stats.in_progress_reports }}</p>
        <p class="text-xs text-blue-600 mt-1">Diproses</p>
      </div>

      <!-- Done -->
      <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl shadow-sm p-4">
        <div class="flex items-center justify-between mb-2">
          <span class="text-2xl">✅</span>
        </div>
        <p class="text-2xl font-bold text-green-700">{{ stats.completed_reports }}</p>
        <p class="text-xs text-green-600 mt-1">Selesai</p>
      </div>
    </div>

    <!-- Urgent Banner (if any) -->
    <div v-if="stats.urgent_reports > 0" class="bg-gradient-to-r from-red-500 to-red-600 rounded-2xl shadow-sm p-4 mb-6 text-white">
      <div class="flex items-center gap-3">
        <span class="text-3xl">🚨</span>
        <div>
          <p class="text-2xl font-bold">{{ stats.urgent_reports }}</p>
          <p class="text-sm opacity-90">Laporan Mendesak</p>
        </div>
      </div>
    </div>

    <!-- Recent Reports -->
    <div class="bg-white rounded-2xl shadow-sm p-4">
      <h3 class="text-lg font-semibold text-slate-800 mb-4">Laporan Terbaru</h3>
      
      <div class="space-y-3">
        <div 
          v-for="report in recentReports" 
          :key="report.id"
          class="flex items-start gap-3 p-3 border border-slate-100 rounded-xl hover:bg-slate-50 transition cursor-pointer active:scale-98"
          @click="$inertia.visit(`/reports/${report.id}`)"
        >
          <div class="flex-shrink-0">
            <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center text-2xl">
              {{ getCategoryIcon(report.category) }}
            </div>
          </div>
          
          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-2 mb-1">
              <h4 class="font-semibold text-slate-800 text-sm line-clamp-1">{{ report.title }}</h4>
              <span 
                v-if="report.is_urgent"
                class="flex-shrink-0 px-2 py-0.5 bg-red-100 text-red-600 text-xs font-semibold rounded-lg"
              >
                URGENT
              </span>
            </div>
            <p class="text-xs text-slate-600 mb-2 line-clamp-2">{{ report.description }}</p>
            <div class="flex items-center gap-3 text-xs text-slate-500">
              <span class="flex items-center gap-1">
                <span>👤</span>
                <span class="truncate">{{ report.user.name }}</span>
              </span>
              <span class="flex items-center gap-1">
                <span>📅</span>
                <span>{{ formatDate(report.created_at) }}</span>
              </span>
            </div>
          </div>
          
          <div class="flex-shrink-0">
            <span 
              class="inline-block px-2 py-1 rounded-lg text-xs font-semibold"
              :class="getStatusClass(report.status)"
            >
              {{ getStatusLabel(report.status) }}
            </span>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="recentReports.length === 0" class="text-center py-8">
          <div class="text-5xl mb-3">📝</div>
          <p class="text-slate-600 text-sm">Belum ada laporan</p>
        </div>
      </div>
    </div>
  </component>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import DesktopLayout from '@/Layouts/DesktopLayout.vue';
import MobileAppLayout from '@/Layouts/MobileAppLayout.vue';

const page = usePage();

const props = defineProps({
  stats: Object,
  recentReports: Array,
  heatmapData: Array,
});

// Determine layout based on user role
const layoutComponent = computed(() => {
  const roles = page.props.auth.user?.roles;
  const isWarga = roles?.includes('warga') && !roles?.includes('super_admin') && !roles?.includes('admin_dinas');
  return isWarga ? MobileAppLayout : DesktopLayout;
});

const getCategoryIcon = (category) => {
  return category?.icon || '📝';
};

const getStatusClass = (status) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-700',
    verified: 'bg-blue-100 text-blue-700',
    in_progress: 'bg-blue-100 text-blue-700',
    completed: 'bg-green-100 text-green-700',
    rejected: 'bg-red-100 text-red-700',
  };
  return classes[status] || 'bg-slate-100 text-slate-700';
};

const getStatusLabel = (status) => {
  const labels = {
    pending: 'Menunggu',
    verified: 'Terverifikasi',
    in_progress: 'Diproses',
    completed: 'Selesai',
    rejected: 'Ditolak',
  };
  return labels[status] || status;
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'short',
    year: 'numeric'
  });
};
</script>
