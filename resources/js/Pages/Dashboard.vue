<template>
  <DesktopLayout page-title="Dashboard">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
      <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-2">
          <span class="text-3xl">📊</span>
          <span class="text-xs font-semibold text-slate-500 uppercase">Total</span>
        </div>
        <p class="text-3xl font-bold text-slate-800">{{ stats.total_reports }}</p>
        <p class="text-sm text-slate-500 mt-1">Total Laporan</p>
      </div>

      <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-2">
          <span class="text-3xl">⏳</span>
          <span class="text-xs font-semibold text-yellow-500 uppercase">Pending</span>
        </div>
        <p class="text-3xl font-bold text-yellow-600">{{ stats.pending_reports }}</p>
        <p class="text-sm text-slate-500 mt-1">Menunggu Verifikasi</p>
      </div>

      <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-2">
          <span class="text-3xl">🔄</span>
          <span class="text-xs font-semibold text-blue-500 uppercase">Progress</span>
        </div>
        <p class="text-3xl font-bold text-blue-600">{{ stats.in_progress_reports }}</p>
        <p class="text-sm text-slate-500 mt-1">Sedang Diproses</p>
      </div>

      <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-2">
          <span class="text-3xl">✅</span>
          <span class="text-xs font-semibold text-green-500 uppercase">Done</span>
        </div>
        <p class="text-3xl font-bold text-green-600">{{ stats.completed_reports }}</p>
        <p class="text-sm text-slate-500 mt-1">Selesai</p>
      </div>

      <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-2">
          <span class="text-3xl">🚨</span>
          <span class="text-xs font-semibold text-red-500 uppercase">Urgent</span>
        </div>
        <p class="text-3xl font-bold text-red-600">{{ stats.urgent_reports }}</p>
        <p class="text-sm text-slate-500 mt-1">Mendesak</p>
      </div>
    </div>

    <!-- Recent Reports -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
      <h3 class="text-xl font-semibold text-slate-800 mb-4">Laporan Terbaru</h3>
      
      <div class="space-y-4">
        <div 
          v-for="report in recentReports" 
          :key="report.id"
          class="flex items-start p-4 border border-slate-200 rounded-xl hover:bg-slate-50 transition cursor-pointer"
          @click="$inertia.visit(`/reports/${report.id}`)"
        >
          <div class="flex-1">
            <div class="flex items-center gap-2 mb-2">
              <span class="text-2xl">{{ getCategoryIcon(report.category) }}</span>
              <h4 class="font-semibold text-slate-800">{{ report.title }}</h4>
              <span 
                v-if="report.is_urgent"
                class="px-2 py-1 bg-red-100 text-red-600 text-xs font-semibold rounded-lg"
              >
                URGENT
              </span>
            </div>
            <p class="text-sm text-slate-600 mb-2">{{ report.description.substring(0, 100) }}...</p>
            <div class="flex items-center gap-4 text-xs text-slate-500">
              <span>👤 {{ report.user.name }}</span>
              <span>🏢 {{ report.department.name }}</span>
              <span>📅 {{ formatDate(report.created_at) }}</span>
            </div>
          </div>
          <div>
            <span 
              class="px-3 py-1 rounded-lg text-xs font-semibold"
              :class="getStatusClass(report.status)"
            >
              {{ getStatusLabel(report.status) }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </DesktopLayout>
</template>

<script setup>
import DesktopLayout from '@/Layouts/DesktopLayout.vue';

const props = defineProps({
  stats: Object,
  recentReports: Array,
  heatmapData: Array,
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
