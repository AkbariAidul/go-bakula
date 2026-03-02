<template>
  <component :is="layoutComponent" page-title="Buat Laporan Baru">
    <div class="max-w-3xl mx-auto">
      <div class="bg-white rounded-2xl shadow-sm p-8">
        <form @submit.prevent="submit">
          <!-- Category Selection -->
          <div class="mb-6">
            <label class="block text-sm font-semibold text-slate-700 mb-3">Pilih Kategori</label>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
              <div
                v-for="category in categories"
                :key="category.id"
                @click="form.category_id = category.id"
                class="p-4 border-2 rounded-xl cursor-pointer transition text-center"
                :class="form.category_id === category.id 
                  ? 'border-green-500 bg-green-50' 
                  : 'border-slate-200 hover:border-slate-300'"
              >
                <div class="text-3xl mb-2">{{ category.icon }}</div>
                <p class="text-sm font-semibold text-slate-800">{{ category.name }}</p>
                <p class="text-xs text-slate-500 mt-1">{{ category.department.name }}</p>
              </div>
            </div>
            <p v-if="form.errors.category_id" class="text-red-600 text-sm mt-2">{{ form.errors.category_id }}</p>
          </div>

          <!-- Title -->
          <div class="mb-6">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Laporan</label>
            <input
              v-model="form.title"
              type="text"
              placeholder="Contoh: Jalan Berlubang di Jl. Ahmad Yani"
              class="w-full rounded-xl border-slate-300 focus:border-green-500 focus:ring-green-500"
            />
            <p v-if="form.errors.title" class="text-red-600 text-sm mt-1">{{ form.errors.title }}</p>
          </div>

          <!-- Description -->
          <div class="mb-6">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi</label>
            <textarea
              v-model="form.description"
              rows="4"
              placeholder="Jelaskan detail masalah yang Anda laporkan..."
              class="w-full rounded-xl border-slate-300 focus:border-green-500 focus:ring-green-500"
            ></textarea>
            <p v-if="form.errors.description" class="text-red-600 text-sm mt-1">{{ form.errors.description }}</p>
          </div>

          <!-- Photo Upload -->
          <div class="mb-6">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Foto Bukti</label>
            <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center">
              <input
                ref="photoInput"
                type="file"
                accept="image/*"
                capture="environment"
                @change="handlePhotoUpload"
                class="hidden"
              />
              
              <div v-if="!photoPreview" @click="$refs.photoInput.click()" class="cursor-pointer">
                <div class="text-5xl mb-3">📷</div>
                <p class="text-slate-600 font-semibold mb-1">Ambil Foto</p>
                <p class="text-sm text-slate-500">Klik untuk mengambil foto dengan kamera</p>
              </div>

              <div v-else class="relative">
                <img :src="photoPreview" alt="Preview" class="max-h-64 mx-auto rounded-xl" />
                <button
                  type="button"
                  @click="removePhoto"
                  class="absolute top-2 right-2 bg-red-500 text-white p-2 rounded-full hover:bg-red-600"
                >
                  ✕
                </button>
              </div>
            </div>
            <p v-if="form.errors.photo" class="text-red-600 text-sm mt-1">{{ form.errors.photo }}</p>
          </div>

          <!-- Location -->
          <div class="mb-6">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Lokasi</label>
            <button
              type="button"
              @click="getLocation"
              :disabled="loadingLocation"
              class="w-full px-4 py-3 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-100 transition font-semibold"
            >
              <span v-if="loadingLocation">📍 Mengambil lokasi...</span>
              <span v-else-if="form.latitude">✅ Lokasi terdeteksi</span>
              <span v-else>📍 Ambil Lokasi GPS</span>
            </button>
            
            <div v-if="form.latitude" class="mt-3 p-3 bg-slate-50 rounded-xl text-sm text-slate-600">
              <p><strong>Latitude:</strong> {{ form.latitude }}</p>
              <p><strong>Longitude:</strong> {{ form.longitude }}</p>
              <p v-if="form.address"><strong>Alamat:</strong> {{ form.address }}</p>
            </div>
            
            <p v-if="form.errors.latitude" class="text-red-600 text-sm mt-1">{{ form.errors.latitude }}</p>
          </div>

          <!-- Submit Button -->
          <div class="flex gap-4">
            <Link
              href="/reports"
              class="flex-1 px-6 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition text-center font-semibold"
            >
              Batal
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="flex-1 px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition font-semibold disabled:opacity-50"
            >
              <span v-if="form.processing">Mengirim...</span>
              <span v-else>Kirim Laporan</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </component>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import DesktopLayout from '@/Layouts/DesktopLayout.vue';
import MobileAppLayout from '@/Layouts/MobileAppLayout.vue';

const page = usePage();

const props = defineProps({
  categories: Array,
});

// Determine layout based on user role
const layoutComponent = computed(() => {
  const roles = page.props.auth.user?.roles;
  const isWarga = roles?.includes('warga') && !roles?.includes('super_admin') && !roles?.includes('admin_dinas');
  return isWarga ? MobileAppLayout : DesktopLayout;
});

const form = useForm({
  category_id: null,
  title: '',
  description: '',
  photo: null,
  latitude: null,
  longitude: null,
  address: '',
});

const photoPreview = ref(null);
const photoInput = ref(null);
const loadingLocation = ref(false);

const handlePhotoUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.photo = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      photoPreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

const removePhoto = () => {
  form.photo = null;
  photoPreview.value = null;
  if (photoInput.value) {
    photoInput.value.value = '';
  }
};

const getLocation = () => {
  if (!navigator.geolocation) {
    alert('Geolocation tidak didukung oleh browser Anda');
    return;
  }

  loadingLocation.value = true;

  navigator.geolocation.getCurrentPosition(
    (position) => {
      form.latitude = position.coords.latitude;
      form.longitude = position.coords.longitude;
      
      // Reverse geocoding (optional - requires API)
      form.address = `Lat: ${form.latitude}, Long: ${form.longitude}`;
      
      loadingLocation.value = false;
    },
    (error) => {
      alert('Gagal mengambil lokasi: ' + error.message);
      loadingLocation.value = false;
    }
  );
};

const submit = () => {
  form.post('/reports', {
    forceFormData: true,
  });
};
</script>
