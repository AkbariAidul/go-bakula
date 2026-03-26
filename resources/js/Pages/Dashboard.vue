<template>
  <component :is="layoutComponent" page-title="Dashboard">
    <!-- Stats Cards - BWA Style -->
    <div class="grid grid-cols-2 gap-3 mb-6">
      <!-- Total -->
      <div class="bg-white rounded-2xl p-4 border border-slate-100">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center">
            <span class="text-2xl">📊</span>
          </div>
          <div>
            <p class="text-2xl font-bold text-slate-800">{{ stats.total_reports }}</p>
            <p class="text-xs text-slate-500">Total</p>
          </div>
        </div>
      </div>

      <!-- Pending -->
      <div class="bg-white rounded-2xl p-4 border border-amber-200">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
            <span class="text-2xl">⏳</span>
          </div>
          <div>
            <p class="text-2xl font-bold text-amber-600">{{ stats.pending_reports }}</p>
            <p class="text-xs text-amber-600">Menunggu</p>
          </div>
        </div>
      </div>

      <!-- Progress -->
      <div class="bg-white rounded-2xl p-4 border border-primary-200">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-xl bg-primary-100 flex items-center justify-center">
            <span class="text-2xl">🔄</span>
          </div>
          <div>
            <p class="text-2xl font-bold text-primary-700">{{ stats.in_progress_reports }}</p>
            <p class="text-xs text-primary-700">Diproses</p>
          </div>
        </div>
      </div>

      <!-- Done -->
      <div class="bg-white rounded-2xl p-4 border border-emerald-200">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
            <span class="text-2xl">✅</span>
          </div>
          <div>
            <p class="text-2xl font-bold text-emerald-600">{{ stats.completed_reports }}</p>
            <p class="text-xs text-emerald-600">Selesai</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Urgent Alert (if any) -->
    <div v-if="stats.urgent_reports > 0" class="bg-white rounded-2xl p-4 mb-6 border-2 border-red-200">
      <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
          <span class="text-2xl">🚨</span>
        </div>
        <div class="flex-1">
          <p class="text-lg font-bold text-red-600">{{ stats.urgent_reports }} Laporan Mendesak</p>
          <p class="text-xs text-red-500">Perlu perhatian segera</p>
        </div>
      </div>
    </div>

    <!-- Recent Reports -->
    <div class="bg-white rounded-2xl p-4 border border-slate-100">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-base font-bold text-slate-800">Laporan Terbaru</h3>
        <Link href="/reports" class="text-xs text-primary-700 font-semibold">
          Lihat Semua →
        </Link>
      </div>
      
      <div class="space-y-3">
        <div 
          v-for="report in recentReports.slice(0, 5)" 
          :key="report.id"
          class="flex items-start gap-3 p-3 bg-slate-50 rounded-xl hover:bg-slate-100 transition cursor-pointer"
          @click="$inertia.visit(`/reports/${report.id}`)"
        >
          <!-- Icon -->
          <div class="flex-shrink-0">
            <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center border border-slate-200">
              <span class="text-xl">{{ getCategoryIcon(report.category) }}</span>
            </div>
          </div>
          
          <!-- Content -->
          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-2 mb-1">
              <h4 class="font-semibold text-slate-800 text-sm line-clamp-1">{{ report.title }}</h4>
              <span 
                v-if="report.is_urgent"
                class="flex-shrink-0 px-2 py-0.5 bg-red-500 text-white text-xs font-bold rounded"
              >
                URGENT
              </span>
            </div>
            <p class="text-xs text-slate-500 mb-2 line-clamp-1">{{ report.description }}</p>
            <div class="flex items-center gap-2">
              <span 
                class="inline-block px-2 py-0.5 rounded text-xs font-semibold"
                :class="getStatusClass(report.status)"
              >
                {{ getStatusLabel(report.status) }}
              </span>
              <span class="text-xs text-slate-400">•</span>
              <span class="text-xs text-slate-500">{{ formatDate(report.created_at) }}</span>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="recentReports.length === 0" class="text-center py-12">
          <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3">
            <span class="text-4xl">📝</span>
          </div>
          <p class="text-slate-600 text-sm font-medium mb-1">Belum Ada Laporan</p>
          <p class="text-slate-400 text-xs">Laporan akan muncul di sini</p>
        </div>
      </div>
    </div>
  </component>
</template>

<script setup>
import { computed } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
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
