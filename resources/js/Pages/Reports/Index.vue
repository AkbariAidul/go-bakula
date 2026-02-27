<template>
  <DesktopLayout page-title="Semua Laporan">
    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <select 
          v-model="filterForm.status" 
          @change="applyFilters"
          class="rounded-xl border-slate-300 focus:border-green-500 focus:ring-green-500"
        >
          <option value="">Semua Status</option>
          <option value="pending">Menunggu</option>
          <option value="verified">Terverifikasi</option>
          <option value="in_progress">Diproses</option>
          <option value="completed">Selesai</option>
          <option value="rejected">Ditolak</option>
        </select>

        <select 
          v-model="filterForm.is_urgent" 
          @change="applyFilters"
          class="rounded-xl border-slate-300 focus:border-green-500 focus:ring-green-500"
        >
          <option value="">Semua Prioritas</option>
          <option value="1">Urgent</option>
          <option value="0">Normal</option>
        </select>

        <button 
          @click="resetFilters"
          class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition"
        >
          Reset Filter
        </button>

        <Link 
          href="/reports/create"
          class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition text-center font-semibold"
        >
          + Buat Laporan
        </Link>
      </div>
    </div>

    <!-- Reports List -->
    <div class="space-y-4">
      <div 
        v-for="report in reports.data" 
        :key="report.id"
        class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-md transition cursor-pointer"
        @click="$inertia.visit(`/reports/${report.id}`)"
      >
        <div class="flex items-start gap-4">
          <img 
            :src="`/storage/${report.photo_path}`" 
            alt="Report photo"
            class="w-24 h-24 object-cover rounded-xl"
          />
          
          <div class="flex-1">
            <div class="flex items-center gap-2 mb-2">
              <span class="text-2xl">{{ report.category.icon }}</span>
              <h3 class="text-lg font-semibold text-slate-800">{{ report.title }}</h3>
              <span 
                v-if="report.is_urgent"
                class="px-2 py-1 bg-red-100 text-red-600 text-xs font-semibold rounded-lg"
              >
                URGENT
              </span>
            </div>
            
            <p class="text-slate-600 mb-3">{{ report.description.substring(0, 150) }}...</p>
            
            <div class="flex items-center gap-4 text-sm text-slate-500">
              <span>👤 {{ report.user.name }}</span>
              <span>🏢 {{ report.department.name }}</span>
              <span>📅 {{ formatDate(report.created_at) }}</span>
              <span>👍 {{ report.upvotes_count }} upvotes</span>
            </div>
          </div>

          <div class="text-right">
            <span 
              class="inline-block px-3 py-1 rounded-lg text-xs font-semibold mb-2"
              :class="getStatusClass(report.status)"
            >
              {{ getStatusLabel(report.status) }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <div v-if="reports.links.length > 3" class="mt-6 flex justify-center gap-2">
      <Link
        v-for="(link, index) in reports.links"
        :key="index"
        :href="link.url"
        v-html="link.label"
        class="px-4 py-2 rounded-xl transition"
        :class="link.active ? 'bg-green-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-50'"
      />
    </div>
  </DesktopLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import DesktopLayout from '@/Layouts/DesktopLayout.vue';

const props = defineProps({
  reports: Object,
  filters: Object,
});

const filterForm = ref({
  status: props.filters.status || '',
  is_urgent: props.filters.is_urgent || '',
});

const applyFilters = () => {
  router.get('/reports', filterForm.value, {
    preserveState: true,
    preserveScroll: true,
  });
};

const resetFilters = () => {
  filterForm.value = { status: '', is_urgent: '' };
  applyFilters();
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
