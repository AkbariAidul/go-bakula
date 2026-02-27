<template>
  <DesktopLayout :page-title="`Laporan #${report.id}`">
    <div class="max-w-4xl mx-auto">
      <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="p-6 border-b border-slate-200">
          <div class="flex items-start justify-between">
            <div class="flex-1">
              <div class="flex items-center gap-3 mb-2">
                <span class="text-3xl">{{ report.category.icon }}</span>
                <h1 class="text-2xl font-bold text-slate-800">{{ report.title }}</h1>
                <span 
                  v-if="report.is_urgent"
                  class="px-3 py-1 bg-red-100 text-red-600 text-sm font-semibold rounded-lg"
                >
                  URGENT
                </span>
              </div>
              <div class="flex items-center gap-4 text-sm text-slate-500">
                <span>👤 {{ report.user.name }}</span>
                <span>📅 {{ formatDate(report.created_at) }}</span>
              </div>
            </div>
            
            <span 
              class="px-4 py-2 rounded-xl text-sm font-semibold"
              :class="getStatusClass(report.status)"
            >
              {{ getStatusLabel(report.status) }}
            </span>
          </div>
        </div>

        <!-- Photo -->
        <div class="relative">
          <img 
            :src="`/storage/${report.photo_path}`" 
            alt="Report photo"
            class="w-full h-96 object-cover"
          />
        </div>

        <!-- Content -->
        <div class="p-6">
          <!-- Description -->
          <div class="mb-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-2">Deskripsi</h3>
            <p class="text-slate-600">{{ report.description }}</p>
          </div>

          <!-- Details -->
          <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div>
              <h3 class="text-lg font-semibold text-slate-800 mb-3">Informasi</h3>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-slate-500">Kategori:</span>
                  <span class="font-semibold text-slate-800">{{ report.category.name }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-slate-500">Dinas:</span>
                  <span class="font-semibold text-slate-800">{{ report.department.name }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-slate-500">Lokasi:</span>
                  <span class="font-semibold text-slate-800">{{ report.address || 'GPS' }}</span>
                </div>
              </div>
            </div>

            <div>
              <h3 class="text-lg font-semibold text-slate-800 mb-3">Status Timeline</h3>
              <div class="space-y-2 text-sm">
                <div v-if="report.created_at" class="flex items-start gap-2">
                  <span class="text-slate-400">📝</span>
                  <div>
                    <p class="font-semibold text-slate-800">Dilaporkan</p>
                    <p class="text-slate-500">{{ formatDateTime(report.created_at) }}</p>
                  </div>
                </div>
                <div v-if="report.verified_at" class="flex items-start gap-2">
                  <span class="text-blue-500">✓</span>
                  <div>
                    <p class="font-semibold text-slate-800">Diverifikasi</p>
                    <p class="text-slate-500">{{ formatDateTime(report.verified_at) }}</p>
                  </div>
                </div>
                <div v-if="report.completed_at" class="flex items-start gap-2">
                  <span class="text-green-500">✓</span>
                  <div>
                    <p class="font-semibold text-slate-800">Selesai</p>
                    <p class="text-slate-500">{{ formatDateTime(report.completed_at) }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Admin Notes -->
          <div v-if="report.admin_notes" class="mb-6 p-4 bg-blue-50 rounded-xl">
            <h3 class="text-sm font-semibold text-blue-800 mb-2">Catatan Admin</h3>
            <p class="text-sm text-blue-700">{{ report.admin_notes }}</p>
          </div>

          <!-- Completion Photo -->
          <div v-if="report.completion_photo_path" class="mb-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-3">Foto Penyelesaian</h3>
            <img 
              :src="`/storage/${report.completion_photo_path}`" 
              alt="Completion photo"
              class="w-full max-h-64 object-cover rounded-xl"
            />
          </div>

          <!-- Upvote Section -->
          <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
            <div>
              <p class="text-sm text-slate-600">Dukungan dari warga lain</p>
              <p class="text-2xl font-bold text-slate-800">{{ report.upvotes_count }} 👍</p>
            </div>
            <button
              v-if="canUpvote"
              @click="toggleUpvote"
              :disabled="upvoting"
              class="px-6 py-3 rounded-xl font-semibold transition"
              :class="hasUpvoted ? 'bg-green-600 text-white' : 'bg-white text-slate-700 border-2 border-slate-300 hover:border-green-500'"
            >
              {{ hasUpvoted ? '✓ Didukung' : '👍 Dukung' }}
            </button>
          </div>

          <!-- Admin Actions -->
          <div v-if="canManage" class="mt-6 p-4 bg-slate-50 rounded-xl">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Aksi Admin</h3>
            
            <form @submit.prevent="updateStatus" class="space-y-4">
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Ubah Status</label>
                <select
                  v-model="statusForm.status"
                  class="w-full rounded-xl border-slate-300 focus:border-green-500 focus:ring-green-500"
                >
                  <option value="verified">Terverifikasi</option>
                  <option value="in_progress">Sedang Diproses</option>
                  <option value="completed">Selesai</option>
                  <option value="rejected">Ditolak</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan</label>
                <textarea
                  v-model="statusForm.admin_notes"
                  rows="3"
                  class="w-full rounded-xl border-slate-300 focus:border-green-500 focus:ring-green-500"
                  placeholder="Tambahkan catatan (opsional)"
                ></textarea>
              </div>

              <button
                type="submit"
                :disabled="statusForm.processing"
                class="w-full px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition font-semibold"
              >
                Perbarui Status
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </DesktopLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import DesktopLayout from '@/Layouts/DesktopLayout.vue';
import axios from 'axios';

const props = defineProps({
  report: Object,
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const canManage = computed(() => {
  return user.value?.roles?.includes('super_admin') || user.value?.roles?.includes('admin_dinas');
});
const canUpvote = computed(() => {
  return user.value && props.report.user_id !== user.value.id;
});

const hasUpvoted = ref(false);
const upvoting = ref(false);

const statusForm = useForm({
  status: props.report.status,
  admin_notes: props.report.admin_notes || '',
});

const toggleUpvote = async () => {
  upvoting.value = true;
  try {
    const response = await axios.post(`/reports/${props.report.id}/upvote`);
    hasUpvoted.value = response.data.upvoted;
    props.report.upvotes_count = response.data.count;
  } catch (error) {
    console.error('Error toggling upvote:', error);
  } finally {
    upvoting.value = false;
  }
};

const updateStatus = () => {
  statusForm.patch(`/reports/${props.report.id}/status`, {
    preserveScroll: true,
  });
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
    pending: 'Menunggu Verifikasi',
    verified: 'Terverifikasi',
    in_progress: 'Sedang Diproses',
    completed: 'Selesai',
    rejected: 'Ditolak',
  };
  return labels[status] || status;
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });
};

const formatDateTime = (date) => {
  return new Date(date).toLocaleString('id-ID', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};
</script>
