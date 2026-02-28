<template>
  <DesktopLayout page-title="Manajemen Dinas">
    <!-- Add Button -->
    <div class="mb-6">
      <button
        @click="showModal = true"
        class="px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition font-semibold"
      >
        + Tambah Dinas
      </button>
    </div>

    <!-- Departments List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="department in departments"
        :key="department.id"
        class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-md transition"
      >
        <div class="flex items-start justify-between mb-4">
          <div>
            <h3 class="text-lg font-semibold text-slate-800 mb-1">{{ department.name }}</h3>
            <span class="inline-block px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-lg">
              {{ department.code }}
            </span>
          </div>
          <span
            class="px-2 py-1 rounded-lg text-xs font-semibold"
            :class="department.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
          >
            {{ department.is_active ? 'Aktif' : 'Nonaktif' }}
          </span>
        </div>

        <p class="text-sm text-slate-600 mb-4">{{ department.description }}</p>

        <div class="space-y-2 text-sm text-slate-500 mb-4">
          <div v-if="department.contact_email" class="flex items-center gap-2">
            <span>📧</span>
            <span>{{ department.contact_email }}</span>
          </div>
          <div v-if="department.contact_phone" class="flex items-center gap-2">
            <span>📞</span>
            <span>{{ department.contact_phone }}</span>
          </div>
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-slate-200">
          <div class="text-sm text-slate-500">
            <span class="font-semibold">{{ department.categories_count }}</span> kategori
            <span class="mx-2">•</span>
            <span class="font-semibold">{{ department.reports_count }}</span> laporan
          </div>
          <div class="flex gap-2">
            <button
              @click="editDepartment(department)"
              class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
            >
              ✏️
            </button>
            <button
              @click="deleteDepartment(department)"
              class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
            >
              🗑️
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Form -->
    <div
      v-if="showModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
      @click.self="closeModal"
    >
      <div class="bg-white rounded-2xl p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <h2 class="text-2xl font-bold text-slate-800 mb-6">
          {{ editMode ? 'Edit Dinas' : 'Tambah Dinas Baru' }}
        </h2>

        <form @submit.prevent="submit">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Dinas</label>
              <input
                v-model="form.name"
                type="text"
                class="w-full rounded-xl border-slate-300 focus:border-green-500 focus:ring-green-500"
                placeholder="Contoh: Dinas Pekerjaan Umum"
              />
              <p v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.name }}</p>
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-2">Kode Dinas</label>
              <input
                v-model="form.code"
                type="text"
                class="w-full rounded-xl border-slate-300 focus:border-green-500 focus:ring-green-500"
                placeholder="Contoh: PUPR"
              />
              <p v-if="form.errors.code" class="text-red-600 text-sm mt-1">{{ form.errors.code }}</p>
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi</label>
              <textarea
                v-model="form.description"
                rows="3"
                class="w-full rounded-xl border-slate-300 focus:border-green-500 focus:ring-green-500"
                placeholder="Deskripsi singkat tentang dinas"
              ></textarea>
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-2">Email Kontak</label>
              <input
                v-model="form.contact_email"
                type="email"
                class="w-full rounded-xl border-slate-300 focus:border-green-500 focus:ring-green-500"
                placeholder="email@baritokualakab.go.id"
              />
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-2">Telepon Kontak</label>
              <input
                v-model="form.contact_phone"
                type="text"
                class="w-full rounded-xl border-slate-300 focus:border-green-500 focus:ring-green-500"
                placeholder="0511-1234567"
              />
            </div>

            <div class="flex items-center">
              <input
                v-model="form.is_active"
                type="checkbox"
                class="rounded border-slate-300 text-green-600 focus:ring-green-500"
              />
              <label class="ml-2 text-sm text-slate-700">Aktif</label>
            </div>
          </div>

          <div class="flex gap-4 mt-6">
            <button
              type="button"
              @click="closeModal"
              class="flex-1 px-6 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition font-semibold"
            >
              Batal
            </button>
            <button
              type="submit"
              :disabled="form.processing"
              class="flex-1 px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition font-semibold disabled:opacity-50"
            >
              {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </DesktopLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import DesktopLayout from '@/Layouts/DesktopLayout.vue';

const props = defineProps({
  departments: Array,
});

const showModal = ref(false);
const editMode = ref(false);
const editingId = ref(null);

const form = useForm({
  name: '',
  code: '',
  description: '',
  contact_email: '',
  contact_phone: '',
  is_active: true,
});

const editDepartment = (department) => {
  editMode.value = true;
  editingId.value = department.id;
  form.name = department.name;
  form.code = department.code;
  form.description = department.description;
  form.contact_email = department.contact_email;
  form.contact_phone = department.contact_phone;
  form.is_active = department.is_active;
  showModal.value = true;
};

const deleteDepartment = (department) => {
  if (confirm(`Yakin ingin menghapus ${department.name}?`)) {
    form.delete(`/departments/${department.id}`, {
      preserveScroll: true,
    });
  }
};

const submit = () => {
  if (editMode.value) {
    form.put(`/departments/${editingId.value}`, {
      preserveScroll: true,
      onSuccess: () => closeModal(),
    });
  } else {
    form.post('/departments', {
      preserveScroll: true,
      onSuccess: () => closeModal(),
    });
  }
};

const closeModal = () => {
  showModal.value = false;
  editMode.value = false;
  editingId.value = null;
  form.reset();
  form.clearErrors();
};
</script>
