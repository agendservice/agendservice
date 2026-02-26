<template>
  <div v-bind="$attrs">
    <label :for="alt" class="block text-sm font-medium mb-1 flex items-center">
      {{ label }} 
      <span class="text-red-500 ml-1" v-show="required || hasRequiredRule">*</span>

      <div v-if="dica" class="relative group ml-2">
        <span 
          class="cursor-pointer text-gray-400 hover:text-blue-500 transition-colors"
          :class="{ 'pointer-events-none md:pointer-events-auto': isMobile }"
          @click.stop="toggleDica"
        >
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c0-1.115.823-2.001 1.932-2.001 1.11 0 2.001.886 2.001 2.001M12 18v-4.5M12 9h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </span>
        
        <div 
          v-show="isMobile ? mostrarDica : true"
          class="
            absolute z-10 p-3 w-64 md:w-80 top-full mt-2 left-1/2 transform -translate-x-1/2
            bg-white border border-gray-200 rounded-lg shadow-lg text-xs text-gray-700
            opacity-0 invisible md:group-hover:opacity-100 md:group-hover:visible transition-opacity
            md:block
          "
          :class="{ 'opacity-100 visible': isMobile && mostrarDica }"
        >
          {{ dica }}
          <button v-if="isMobile" @click.stop="mostrarDica = false" class="absolute top-1 right-1 text-gray-500 hover:text-gray-800">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
          </button>
        </div>
      </div>
    </label>

    <div class="relative" v-if="!money && !percent && !icone && !loader">
      <input 
        v-bind="$attrs" 
        :autocomplete="alt" 
        :class="inputClasses" 
        :type="tipoInputComputado"
        :disabled="disabled" 
        v-model="proxyValue"
        @blur="handleBlur" 
      />
      <button 
        v-if="props.clearable && proxyValue && !props.disabled && type !== 'password'" 
        @mousedown.prevent="clearInput" 
        type="button"
        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700 dark:hover:text-gray-400 focus:outline-none"
      >
        <svg class="h-5 fill-current bg-azul-500 dark:bg-laranja-500 text-white rounded-full" viewBox="0 0 20 20">
          <path d="M10 8.586L13.293 5.293a1 1 0 111.414 1.414L11.414 10l3.293 3.293a1 1 0 01-1.414 1.414L10 11.414l-3.293 3.293a1 1 0 01-1.414-1.414L8.586 10 5.293 6.707a1 1 0 011.414-1.414L10 8.586z"/>
        </svg>
      </button>
      <div v-if="type === 'password'" class="absolute inset-y-0 right-0 flex items-center pr-3">
        <button type="button" @click.stop="togglePassword" class="text-gray-400 hover:text-gray-600 focus:outline-none">
          <svg v-if="!mostrarSenha" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
          </svg>
          <svg v-else class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.056 10.056 0 011.557-2.888m1-1.088A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.791 3.475M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
          </svg>
        </button>
      </div>
    </div>

    <div class="relative" v-if="money && !percent && !icone && !loader">
      <input 
        v-bind="$attrs" 
        :autocomplete="alt" 
        :class="inputClasses" 
        @input="proxyValue = Mascara.moeda(proxyValue)"
        :disabled="disabled" 
        v-model="proxyValue"
        @blur="handleBlur" 
      />

      <button 
        v-if="props.clearable && proxyValue && !props.disabled" 
        @mousedown.prevent="clearInput" 
        type="button"
        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700 dark:hover:text-gray-400 focus:outline-none"
      >
        <svg class="h-5 fill-current bg-azul-500 dark:bg-laranja-500 text-white rounded-full" viewBox="0 0 20 20">
          <path d="M10 8.586L13.293 5.293a1 1 0 111.414 1.414L11.414 10l3.293 3.293a1 1 0 01-1.414 1.414L10 11.414l-3.293 3.293a1 1 0 01-1.414-1.414L8.586 10 5.293 6.707a1 1 0 011.414-1.414L10 8.586z"/>
        </svg>
      </button>
      <div class="absolute inset-0 right-auto flex items-center pointer-events-none">
        <span class="text-sm text-gray-400 dark:text-gray-500 font-medium px-3">R$</span>
      </div>
    </div>

    <div class="relative" v-if="percent && !money && !icone && !loader">
      <input 
        v-bind="$attrs" 
        :autocomplete="alt" 
        :class="inputClasses" 
        @input="proxyValue = Mascara.numero(proxyValue)"
        :disabled="disabled" 
        v-model="proxyValue"
        @blur="handleBlur" 
      />
      <button 
        v-if="props.clearable && proxyValue && !props.disabled" 
        @mousedown.prevent="clearInput" 
        type="button"
        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700 dark:hover:text-gray-400 focus:outline-none"
      >
        <svg class="h-5 fill-current bg-azul-500 dark:bg-laranja-500 text-white rounded-full" viewBox="0 0 20 20">
          <path d="M10 8.586L13.293 5.293a1 1 0 111.414 1.414L11.414 10l3.293 3.293a1 1 0 01-1.414 1.414L10 11.414l-3.293 3.293a1 1 0 01-1.414-1.414L8.586 10 5.293 6.707a1 1 0 011.414-1.414L10 8.586z"/>
        </svg>
      </button>
      <div class="absolute inset-0 right-auto flex items-center pointer-events-none">
        <span class="text-sm text-gray-400 dark:text-gray-500 font-medium px-3">%</span>
      </div>
    </div>

    <div class="relative" v-if="(!money && !percent && icone) || loader">
      <input 
        v-bind="$attrs" 
        :autocomplete="alt" 
        :class="inputClasses" 
        :type="type" 
        :disabled="disabled" 
        v-model="proxyValue"
        @blur="handleBlur" 
      />
      <div class="absolute inset-0 right-auto flex items-center pointer-events-none">
        <span class="text text-gray-400 dark:text-gray-500 font-medium px-3">
          <svg class="animate-spin fill-current shrink-0" width="16" height="16" viewBox="0 0 16 16" v-show="loader">
            <path d="M8 16a7.928 7.928 0 01-3.428-.77l.857-1.807A6.006 6.006 0 0014 8c0-3.309-2.691-6-6-6a6.006 6.006 0 00-5.422 8.572l-1.806.859A7.929 7.929 0 010 8c0-4.411 3.589-8 8-8s8 3.589 8 8-3.589 8-8 8z" />
          </svg>
          <icone :icone="icone" v-show="!loader"></icone>
        </span>
      </div>
    </div>

    <div class="text-xs mt-1 text-red-500" v-show="mensagemErro">
      {{ mensagemErro }}
    </div>
  </div>
</template>

<script setup>
import Icone from './Icone.vue';
import { ref, computed, onMounted, watch} from 'vue';
import Mascara from '../../components/Mascara.vue';
import { isArray } from 'lodash';

const props = defineProps({
  modelValue: { default: null },
  label: {
    type: String,
    default: ''
  },
  alt: {
    type: String,
    default: ''
  },
  type: {
    type: String,
    default: 'text'
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
  upper: { 
    type: Boolean, 
    default: false 
  },
  money: { 
    type: Boolean, 
    default: false 
  },
  percent: { 
    type: Boolean, 
    default: false 
  },
  icone: { 
    type: String, 
    default: '' 
  },
  loader: { 
    type: Boolean, 
    default: false 
  },
  size: { 
    type: String, 
    default: '' 
  },
  erro: { 
    type: String, 
    default: '' 
  },
  rules: {
    type: Array,
    default: () => []
  },
  dica: {
    type: String,
    default: ''
  },
  clearable: {
    type: Boolean,
    default: true
  }
});

const isMobile = ref(false);
const mostrarDica = ref(false);
const emit = defineEmits(['update:modelValue']);

const toggleDica = () => {
  mostrarDica.value = !mostrarDica.value;
};

const checkIsMobile = () => {
  isMobile.value = window.innerWidth <= 768;
};

onMounted(() => {
  checkIsMobile();
  window.addEventListener('resize', checkIsMobile);
});

const mostrarSenha = ref(false);
const togglePassword = () => {
  mostrarSenha.value = !mostrarSenha.value;
};
const tipoInputComputado = computed(() => {
  if (props.type === 'password') {
    return mostrarSenha.value ? 'text' : 'password';
  }
  return props.type;
});
// 1. Controle de Foco (Touched)
const touched = ref(false);

const handleBlur = () => {
  touched.value = true;
};

const proxyValue = computed({
  get() {
    return props.modelValue;
  },
  set(newValue) {
    let finalValue = newValue;
    if (props.upper && typeof newValue === 'string') {
      finalValue = newValue.toUpperCase();
    }
    emit('update:modelValue', finalValue);
  }
});

const clearInput = () => {
    proxyValue.value = ''; // Limpa o texto exibido
    
    // Zera o v-model e notifica a mudança
    emit('update:modelValue', null); 
    emit('change', null); 
 
};
const mensagemErro = ref(null);

watch(() => props.erro, (v) => {
  if (isArray(v)) {
    mensagemErro.value = v.join(', ');
    return;
  }
  mensagemErro.value = v;
});

// Opcional: Para mostrar o asterisco (*) se tiver regra required
const hasRequiredRule = computed(() => {
   if (Array.isArray(props.rules)) {
     return props.rules.some(r => r === 'Este campo é obrigatório.' || r === 'Campo Obrigatório!');
   }
   return false;
});

// 4. Classes CSS (Convertido para Computed puro para evitar bugs)
const computedSize = computed(() => {
  if (props.size === 'large') return 'px-4 py-3';
  if (props.size === 'small') return 'px-2 py-1';
  return '';
});

const inputClasses = computed(() => {
  return [
    'form-input w-full dark:bg-azul-700 dark:border-gray-700 dark:text-white', 
    computedSize.value,
    // Classe de erro baseada na computed mensagemErro
    {'border-red-300 dark:border-red-500': !!mensagemErro.value},
    {'pl-8': props.money || props.icone || props.percent}, 
    {'dark:disabled:placeholder:text-gray-600 disabled:border-gray-200 dark:disabled:border-gray-700 disabled:bg-gray-100 dark:disabled:bg-azul-550 disabled:text-gray-400 dark:disabled:text-gray-600 disabled:cursor-not-allowed shadow-none': props.disabled },
    {'pr-10': props.type === 'password'},
  ];
});

const validar = () => {
  touched.value = true;
  return !mensagemErro.value;
};

defineExpose({ validar });
</script>