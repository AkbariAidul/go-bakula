<template>
  <component :is="layoutComponent" page-title="Laporan Saya">
    <div class="mb-6">
      <Link 
        href="/reports/create"
        class="inline-block px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition font-semibold"
      >
        + Buat Laporan Baru
      </Link>
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

      <!-- Empty State -->
      <div v-if="reports.data.length === 0" class="bg-white rounded-2xl shadow-sm p-12 text-center">
        <div class="text-6xl mb-4">📝</div>
        <h3 class="text-xl font-semibold text-slate-800 mb-2">Belum Ada Laporan</h3>
        <p class="text-slate-600 mb-6">Anda belum membuat laporan apapun</p>
        <Link 
          href="/reports/create"
          class="inline-block px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition font-semibold"
        >
          Buat Laporan Pertama
        </Link>
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
  </component>
</template>

<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import DesktopLayout from '@/Layouts/DesktopLayout.vue';
import MobileAppLayout from '@/Layouts/MobileAppLayout.vue';

const page = usePage();

const props = defineProps({
  reports: Object,
});

// Determine layout based on user role
const layoutComponent = computed(() => {
  const roles = page.props.auth.user?.roles;
  const isWarga = roles?.includes('warga') && !roles?.includes('super_admin') && !roles?.includes('admin_dinas');
  return isWarga ? MobileAppLayout : DesktopLayout;
});

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
