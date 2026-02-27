<template>
  <DesktopLayout page-title="Leaderboard Warga Peduli">
    <div class="max-w-4xl mx-auto">
      <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 p-8 text-white text-center">
          <h1 class="text-3xl font-bold mb-2">🏆 Leaderboard</h1>
          <p class="text-green-100">Warga Peduli Kabupaten Barito Kuala</p>
        </div>

        <!-- Leaderboard List -->
        <div class="p-6">
          <div class="space-y-3">
            <div
              v-for="(entry, index) in leaderboard"
              :key="entry.id"
              class="flex items-center gap-4 p-4 rounded-xl transition"
              :class="index < 3 ? 'bg-gradient-to-r from-yellow-50 to-orange-50' : 'bg-slate-50 hover:bg-slate-100'"
            >
              <!-- Rank -->
              <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-full font-bold text-lg"
                :class="getRankClass(index)"
              >
                {{ index < 3 ? getMedal(index) : index + 1 }}
              </div>

              <!-- User Info -->
              <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                  <h3 class="font-semibold text-slate-800">{{ entry.user.name }}</h3>
                  <span 
                    class="px-2 py-1 rounded-lg text-xs font-semibold"
                    :class="getBadgeClass(entry.badge)"
                  >
                    {{ entry.badge }}
                  </span>
                </div>
                <div class="flex items-center gap-4 text-sm text-slate-500">
                  <span>✅ {{ entry.reports_completed }} laporan selesai</span>
                  <span>👍 {{ entry.upvotes_given }} upvotes</span>
                </div>
              </div>

              <!-- Points -->
              <div class="text-right">
                <p class="text-2xl font-bold text-green-600">{{ entry.points }}</p>
                <p class="text-xs text-slate-500">poin</p>
              </div>
            </div>
          </div>

          <!-- Empty State -->
          <div v-if="leaderboard.length === 0" class="text-center py-12">
            <div class="text-6xl mb-4">🏆</div>
            <p class="text-slate-600">Belum ada data leaderboard</p>
          </div>
        </div>

        <!-- Badge Info -->
        <div class="p-6 bg-slate-50 border-t border-slate-200">
          <h3 class="text-lg font-semibold text-slate-800 mb-4">Tingkatan Badge</h3>
          <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="text-center p-3 bg-white rounded-xl">
              <div class="text-2xl mb-1">🥉</div>
              <p class="text-sm font-semibold text-slate-800">Pemula</p>
              <p class="text-xs text-slate-500">0 poin</p>
            </div>
            <div class="text-center p-3 bg-white rounded-xl">
              <div class="text-2xl mb-1">🥈</div>
              <p class="text-sm font-semibold text-slate-800">Warga Peduli</p>
              <p class="text-xs text-slate-500">50 poin</p>
            </div>
            <div class="text-center p-3 bg-white rounded-xl">
              <div class="text-2xl mb-1">🥇</div>
              <p class="text-sm font-semibold text-slate-800">Pahlawan Lingkungan</p>
              <p class="text-xs text-slate-500">100 poin</p>
            </div>
            <div class="text-center p-3 bg-white rounded-xl">
              <div class="text-2xl mb-1">💎</div>
              <p class="text-sm font-semibold text-slate-800">Guardian Kota</p>
              <p class="text-xs text-slate-500">250 poin</p>
            </div>
            <div class="text-center p-3 bg-white rounded-xl">
              <div class="text-2xl mb-1">👑</div>
              <p class="text-sm font-semibold text-slate-800">Legend</p>
              <p class="text-xs text-slate-500">500 poin</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </DesktopLayout>
</template>

<script setup>
import DesktopLayout from '@/Layouts/DesktopLayout.vue';

const props = defineProps({
  leaderboard: Array,
});

const getMedal = (index) => {
  const medals = ['🥇', '🥈', '🥉'];
  return medals[index] || index + 1;
};

const getRankClass = (index) => {
  if (index === 0) return 'bg-yellow-400 text-yellow-900';
  if (index === 1) return 'bg-slate-300 text-slate-700';
  if (index === 2) return 'bg-orange-400 text-orange-900';
  return 'bg-slate-200 text-slate-600';
};

const getBadgeClass = (badge) => {
  const classes = {
    'Pemula': 'bg-slate-100 text-slate-700',
    'Warga Peduli': 'bg-blue-100 text-blue-700',
    'Pahlawan Lingkungan': 'bg-green-100 text-green-700',
    'Guardian Kota': 'bg-purple-100 text-purple-700',
    'Legend': 'bg-yellow-100 text-yellow-700',
  };
  return classes[badge] || 'bg-slate-100 text-slate-700';
};
</script>
