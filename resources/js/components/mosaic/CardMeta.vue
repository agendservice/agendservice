<template>
  <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
    <div class="flex justify-between items-center mb-2">
      <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase">
        {{ titulo }}
      </h2>
      <span class="text-lg font-bold text-blue-600 dark:text-blue-400">
        {{ valorAtualFormatado }} / <span class="text-gray-600 dark:text-gray-300">{{ valorTotalFormatado }}</span>
      </span>
    </div>
    <div>
      <div class="bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
        <div 
          class="bg-blue-600 h-2.5 rounded-full transition-all duration-500" 
          :style="{ width: Math.min(percentual, 100) + '%' }"
        ></div>
      </div>
      <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex justify-between">
        <span>{{ percentual.toFixed(1) }}%</span>
        <span>100%</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CardMeta',
  props: {
    titulo: {
      type: String,
      required: true
    },
    atual: {
      type: Number,
      default: 0
    },
    total: {
      type: Number,
      default: 0
    }
  },
  computed: {
    percentual() {
      if (!this.total || this.total === 0) return 0;
      return (this.atual / this.total) * 100;
    },
    valorAtualFormatado() {
      return this.formatCurrency(this.atual);
    },
    valorTotalFormatado() {
      return this.formatCurrency(this.total);
    }
  },
  methods: {
    // Movido para dentro do componente para ele ser autossuficiente
    formatCurrency(value) {
      return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
      }).format(value);
    }
  }
};
</script>