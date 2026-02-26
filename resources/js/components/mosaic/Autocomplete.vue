<template>
  <div v-bind="$attrs" ref="containerRef">
    
    <div v-if="label" class="flex items-center mb-1">
      <label :for="id" class="block text-sm font-medium dark:text-gray-300">
        {{ label }}
        <span v-if="required" class="text-red-500">*</span>
      </label>

      <div v-if="dica" class="relative group ml-2 flex items-center">
        <svg 
          xmlns="http://www.w3.org/2000/svg" 
          class="h-4 w-4 text-gray-400 hover:text-azul-500 cursor-help" 
          fill="none" 
          viewBox="0 0 24 24" 
          stroke="currentColor"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>

        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-48 p-2 bg-gray-900 text-white text-xs rounded shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 text-center pointer-events-none">
          {{ dica }}
          <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
        </div>
      </div>
    </div>

    <div class="relative w-full">
      <input
        :id="id"
        type="text"
        :value="displayValue" 
        @input="onInput"
        @focus="openDropdown"
        @blur="handleBlur"
        :disabled="disabled"
        :placeholder="placeholder || 'Digite para buscar...'"
        class="form-input w-full dark:bg-azul-700 dark:border-gray-700 dark:text-gray-300"
        :class="{'border-red-300 dark:border-red-500': !!mensagemErro, 'rounded-b-none': isOpen && filteredOptions.length > 0, 'pr-10': props.clearable}"
        autocomplete="off"
      />

      <button 
        v-if="props.clearable && searchQuery" 
        @mousedown.prevent="clearInput" 
        type="button"
        class="absolute inset-y-0 right-0 flex items-center pr-8 text-gray-500 hover:text-gray-700 dark:hover:text-gray-400 focus:outline-none"
      >
        <svg class="h-5 fill-current bg-azul-500 dark:bg-laranja-500 text-white rounded-full" viewBox="0 0 20 20">
          <path d="M10 8.586L13.293 5.293a1 1 0 111.414 1.414L11.414 10l3.293 3.293a1 1 0 01-1.414 1.414L10 11.414l-3.293 3.293a1 1 0 01-1.414-1.414L8.586 10 5.293 6.707a1 1 0 011.414-1.414L10 8.586z"/>
        </svg>
      </button>
      
      <div 
        class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-500"
        :class="{'pointer-events-none': !props.clearable || !searchQuery, 'pr-3': props.clearable && searchQuery}"
      >
        <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20">
          <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
        </svg>
      </div>

      <ul 
        v-show="isOpen && filteredOptions.length > 0"
        class="absolute z-50 w-full bg-white dark:bg-gray-800 border border-t-0 border-gray-300 dark:border-gray-700 rounded-b-md shadow-lg max-h-60 overflow-auto focus:outline-none"
        style="top: 100%;" 
      >
        <li
          v-for="(item, index) in filteredOptions"
          :key="index"
          @mousedown.prevent="selectOption(item)" 
          class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-blue-100 dark:hover:bg-blue-900 text-gray-900 dark:text-gray-100"
        >
          <span class="block truncate font-normal">
            {{ item[itemLabel] }}
          </span>
        </li>
      </ul>
      
      <div 
        v-show="isOpen && filteredOptions.length === 0 && searchQuery" 
        class="absolute z-50 w-full bg-white border border-gray-200 p-2 text-sm text-gray-500"
        style="top: 100%;"
      >
          Nenhum resultado encontrado.
      </div>

    </div> 
    
    <div class="text-xs mt-1 text-red-500" v-show="mensagemErro">
      {{ mensagemErro }}
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
  modelValue: { 
    default: null 
  }, 
  label: {
    type: String,
    default: ''
  },
  // 3. PROP NOVA ADICIONADA
  dica: {
    type: String,
    default: ''
  },
  options: {
    type: Array, 
    default: () => []
  },
  itemLabel: {
    type: String,
    default: 'label'
  }, 
  itemValue: {
    type: String,
    default: 'id'
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
    default: ''
  },
  rules: {
    type: Array,
    default: () => []
  },
  erro: {
    type: String,
    default: ''
  },
  clearable: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['update:modelValue', 'change']);
const id = `autocomplete-${Math.random().toString(36).substr(2, 9)}`;
const containerRef = ref(null);

const isOpen = ref(false);
const searchQuery = ref('');
const touched = ref(false);

// Sincroniza o searchQuery com o modelValue inicial
watch(() => props.modelValue, (newVal) => {
    if (typeof newVal === 'number' && props.options.length > 0) {
        const item = props.options.find(o => o[props.itemValue] === newVal);
        searchQuery.value = item ? item[props.itemLabel] : '';
    } else if (newVal !== null && newVal !== undefined) {
        searchQuery.value = String(newVal);
    } else {
        searchQuery.value = '';
    }
}, { immediate: true });

watch(props.options, (newOptions) => {
    if (newOptions.length > 0 && props.modelValue) {
        const item = newOptions.find(o => o[props.itemLabel] === props.modelValue || o[props.itemValue] === props.modelValue);
        searchQuery.value = item ? item[props.itemLabel] : String(props.modelValue || '');
    }
});

const displayValue = computed(() => searchQuery.value);

const filteredOptions = computed(() => {
  if (searchQuery.value === '') {
      return props.options; 
  }
  return props.options.filter(option => {
    return option[props.itemLabel]
      .toLowerCase()
      .includes(searchQuery.value.toLowerCase());
  });
});

const onInput = (event) => {
    searchQuery.value = event.target.value;
    isOpen.value = true;
    emit('update:modelValue', event.target.value); 
};

const openDropdown = () => {
    isOpen.value = true;
};

const handleBlur = () => {
    setTimeout(() => {
        isOpen.value = false;
        touched.value = true;
    }, 200);
};

const selectOption = (item) => {
    const selectedLabel = item[props.itemLabel];
    const selectedValue = item[props.itemValue]; 

    searchQuery.value = selectedLabel; 
    
    emit('update:modelValue', selectedLabel); 
    emit('change', selectedValue); 

    isOpen.value = false;
    touched.value = true;
};

const mensagemErro = computed(() => {
  if (!touched.value && !props.erro) return null;
  if (props.erro) return props.erro;
  if (props.rules && props.rules.length > 0) {
    const errorFound = props.rules.find(r => typeof r === 'string');
    return errorFound || null;
  }
  return null;
});

const clearInput = () => {
    searchQuery.value = ''; 
    emit('update:modelValue', null); 
    emit('change', null); 
    isOpen.value = false;
};

const validar = () => {
  touched.value = true;
  return !mensagemErro.value;
};

defineExpose({ validar });
</script>