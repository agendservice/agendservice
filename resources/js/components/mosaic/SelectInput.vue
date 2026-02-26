<template>
  <div>
    <label :for="id" class="block text-sm font-medium mb-1 dark:text-gray-300">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    
    <div class="relative">
      <select
        :id="id"
        :value="modelValue"
        @change="handleChange"
        :disabled="disabled"
        :class="inputClass"
      >
        <option v-if="placeholder" :value="null" disabled>{{ placeholder }}</option>

        <option 
          v-for="option in options" 
          :key="option[itemValue]"
          :value="option[itemValue]"
        >
          {{ option[itemLabel] }}
        </option>
      </select>

      <button
        v-if="modelValue !== null && modelValue !== undefined && modelValue !== ''"
        type="button"
        @click.prevent="clearSelection"
        class="absolute top-0 bottom-0 right-2 flex items-center px-2 mr-4 text-gray-400 hover:text-gray-600 focus:outline-none"
        aria-label="Limpar Seleção"
      >
        <svg class="h-5 fill-current bg-azul-500 dark:bg-laranja-500 text-white rounded-full" viewBox="0 0 24 24">
            <path d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95 1.414-1.414 4.95 4.95z"/>
        </svg>
      </button>
    </div>
  </div>
</template>

<script setup>

import { ref, computed } from 'vue';
// Gera um ID único para acessibilidade
const id = `select-${Math.random().toString(36).substr(2, 9)}`;

// --- Props ---
const props = defineProps({
  modelValue: { // O valor selecionado (ID ou chave)
    default: null,
    // Permite Number, String, ou Null para cobrir IDs e chaves
    type: String, 
  },
  label: {
    type: String,
    default: ''
  },
  options: { // O array de opções
    type: Array, 
    default: () => [] 
  },
  itemLabel: { // Chave para o texto da opção (ex: 'nome')
    type: String, 
    default: 'label' 
  },
  itemValue: { // Chave para o valor da opção (ex: 'id')
    type: String, 
    default: 'value' 
  },
  required: {
    type: Boolean,
    default: false
  },
  disabled: {
    type: Boolean,
    default: false
  },
  placeholder: {
    type: String,
    default: 'Selecione uma opção'
  },
  erro: {
    type: [String, Boolean],
    default: null
  }
});
const inputClass = computed(() => {
  return [
    'form-select w-full pr-10 dark:bg-azul-700 dark:border-gray-700 dark:text-gray-300',
    {'border-red-300 dark:border-red-500': props.erro},
  ];
});
// --- Emits ---
const emit = defineEmits(['update:modelValue']);

// --- Métodos ---
const handleChange = (event) => {
  // Converte o valor para Number se for uma string numérica, 
  // caso contrário, mantém o valor (null ou string).
  let value = event.target.value;
  if (value && !isNaN(Number(value))) {
    value = Number(value);
  } else if (value === 'null' || value === '') {
    value = null;
  }
  
  emit('update:modelValue', value);
};

const clearSelection = () => {
    // Emite null para o v-model do componente pai
    emit('update:modelValue', null);
};
</script>