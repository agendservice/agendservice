<template>
  <label class="block text-sm font-medium mb-1" :for="alt">{{label}} <span class="text-red-500" v-show="required">*</span></label>
  <div class="relative">
    <flat-pickr class="w-full form-input pl-9 dark:bg-azul-700 hover:text-gray-800 dark:text-white dark:hover:text-gray-100 font-medium" :config="config" v-model="date"></flat-pickr>
    <div class="absolute inset-0 right-auto flex items-center pointer-events-none">
      <svg class="fill-current text-gray-400 dark:text-gray-500 ml-3" width="16" height="16" viewBox="0 0 16 16">
        <path d="M5 4a1 1 0 0 0 0 2h6a1 1 0 1 0 0-2H5Z" />
        <path d="M4 0a4 4 0 0 0-4 4v8a4 4 0 0 0 4 4h8a4 4 0 0 0 4-4V4a4 4 0 0 0-4-4H4ZM2 4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4Z" />
      </svg>
    </div>    
  </div>
  <div class="text-xs mt-1 text-red-500" v-show="mensagemErro">
    {{ mensagemErro }}
  </div>
</template>

<script setup>
import flatPickr from 'vue-flatpickr-component'
import { onMounted, ref, watch} from 'vue';
import { isArray } from 'lodash';

const props = defineProps({
  label: {
    type: String,
    required: true,
  },
  alt: {
    type: String,
    default: 'default'
  },
  required: {
    type: Boolean,
    default: false,
  },
  modelValue: {
    default: null
  },
  minDate: {
    type: String,
    default: null
  },
  maxDate: {
    type: String,
    default: null
  },
  erro: {
    type: String,
    default: null
  }
});

onMounted(() => {
  if (props.modelValue) {
    date.value = stringToDate(props.modelValue);
  }
});

const stringToDate = (value) => {
  let [d, m, y] = value.split('/').map(Number);
  return new Date(y, m-1, d);
};

const emit = defineEmits(['update:modelValue']);

const date = ref(null);
const mensagemErro = ref(null);
const config = ref({
  mode: 'single',
  monthSelectorType: 'static',
  dateFormat: 'd/m/Y',
  defaultDate: null,
  prevArrow: '<svg class="fill-current" width="7" height="11" viewBox="0 0 7 11"><path d="M5.4 10.8l1.4-1.4-4-4 4-4L5.4 0 0 5.4z" /></svg>',
  nextArrow: '<svg class="fill-current" width="7" height="11" viewBox="0 0 7 11"><path d="M1.4 10.8L0 9.4l4-4-4-4L1.4 0l5.4 5.4z" /></svg>',
  onReady: (selectedDates, dateStr, instance) => {
    instance.element.value = dateStr;
  },
  onChange: (selectedDates, dateStr, instance) => {
    instance.element.value = dateStr;
  }
  // add disable rules here if needed
});

watch(() => props.modelValue, (newValue) => {
  date.value = newValue;
  emit('update:modelValue', newValue);
});

watch(date, (newValue) => {
  emit('update:modelValue', newValue);
});

watch(() => props.minDate, (newMinDate) => {
  if (newMinDate) {
    config.value.minDate = stringToDate(newMinDate);
  } else {
    delete config.value.minDate;
  }
});

watch(() => props.maxDate, (newMaxDate) => {
  if (newMaxDate) {
    config.value.maxDate = stringToDate(newMaxDate);
  } else {
    delete config.value.maxDate;
  }
});

watch(() => props.erro, (v) => {
  if (isArray(v)) {
    mensagemErro.value = v.join(', ');
    return;
  }
  mensagemErro.value = v;
});
</script>
