<template>
  <DesktopLayout page-title="Manajemen Kategori">
    <!-- Add Button -->
    <div class="mb-6">
      <button
        @click="showModal = true"
        class="px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition font-semibold"
      >
        + Tambah Kategori
      </button>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div
        v-for="category in categories"
        :key="category.id"
        class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-md transition"
        :style="{ borderTop: `4px solid ${category.color}` }"
      >
        <div class="flex items-start justify-between mb-4">
          <div class="text-4xl">{{ category.icon }}</div>
          <span
            class="px-2 py-1 rounded-lg text-xs font-semibold"
            :class="category.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
          >
            {{ category.is_active ? 'Aktif' : 'Nonaktif' }}
          </span>
        </div>

        <h3 class="text-lg font-semibold text-slate-800 mb-2">{{ category.name }}</h3>
        <p class="text-sm text-slate-600 mb-3">{{ category.description }}</p>

        <div class="mb-4">
          <span class="inline-block px-3 py-1 bg-slate-100 text-slate-700 text-xs font-semibold rounded-lg">
            {{ category.department.name }}
          </span>
        </div>

        <div class="flex gap-2 pt-4 border-t border-slate-200">
          <button
            @click="editCategory(category)"
            class="flex-1 px-4 py-2 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition text-sm font-semibold"
          >
            Edit
          </button>
          <button
            @click="deleteCategory(category)"
            class="flex-1 px-4 py-2 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition text-sm font-semibold"
          >
            Hapus
          </button>
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
          {{ editMode ? 'Edit Kategori' : 'Tambah Kategori Baru' }}
        </h2>

        <form @submit.prevent="submit">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-2">Dinas</label>
              <select
                v-model="form.department_id"
                class="w-full rounded-xl border-slate-300 focus:border-green-500 focus:ring-green-500"
              >
                <option value="">Pilih Dinas</option>
                <option v-for="dept in departments" :key="dept.id" :value="dept.id">
                  {{ dept.name }}
                </option>
              </select>
              <p v-if="form.errors.department_id" class="text-red-600 text-sm mt-1">{{ form.errors.department_id }}</p>
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Kategori</label>
              <input
                v-model="form.name"
                type="text"
                class="w-full rounded-xl border-slate-300 focus:border-green-500 focus:ring-green-500"
                placeholder="Contoh: Jalan Rusak"
              />
              <p v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.name }}</p>
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-2">Icon (Emoji)</label>
              <input
                v-model="form.icon"
                type="text"
                class="w-full rounded-xl border-slate-300 focus:border-green-500 focus:ring-green-500"
                placeholder="🛣️"
                maxlength="10"
              />
              <p class="text-xs text-slate-500 mt-1">Gunakan emoji untuk icon</p>
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-2">Warna</label>
              <div class="flex gap-2">
                <input
                  v-model="form.color"
                  type="color"
                  class="h-12 w-20 rounded-xl border-slate-300"
                />
                <input
                  v-model="form.color"
                  type="text"
                  class="flex-1 rounded-xl border-slate-300 focus:border-green-500 focus:ring-green-500"
                  placeholder="#22c55e"
                />
              </div>
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi</label>
              <textarea
                v-model="form.description"
                rows="3"
                class="w-full rounded-xl border-slate-300 focus:border-green-500 focus:ring-green-500"
                placeholder="Deskripsi singkat tentang kategori"
              ></textarea>
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
  categories: Array,
  departments: Array,
});

const showModal = ref(false);
const editMode = ref(false);
const editingId = ref(null);

const form = useForm({
  department_id: '',
  name: '',
  icon: '',
  color: '#22c55e',
  description: '',
  is_active: true,
});

const editCategory = (category) => {
  editMode.value = true;
  editingId.value = category.id;
  form.department_id = category.department_id;
  form.name = category.name;
  form.icon = category.icon;
  form.color = category.color;
  form.description = category.description;
  form.is_active = category.is_active;
  showModal.value = true;
};

const deleteCategory = (category) => {
  if (confirm(`Yakin ingin menghapus ${category.name}?`)) {
    form.delete(`/categories/${category.id}`, {
      preserveScroll: true,
    });
  }
};

const submit = () => {
  if (editMode.value) {
    form.put(`/categories/${editingId.value}`, {
      preserveScroll: true,
      onSuccess: () => closeModal(),
    });
  } else {
    form.post('/categories', {
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
