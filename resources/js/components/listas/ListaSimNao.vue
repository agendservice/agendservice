<template>
  <select-input :label="label" v-model="selectedCategory" :options="options"></select-input>
</template>

<script setup>
import { ref, watch } from 'vue';
import SelectInput from '../mosaic/SelectInput.vue';

const props = defineProps({
  label: {
    type: String,
    default: 'Sim / Não'
  },
  modelValue: {
    type: [Number, String],
    default: null
  },
  init: {
    type: [Number, String, Boolean, null], // Aceita Number (0, 1), String ('1', '0') ou Boolean (true/false)
    default: null
  }
});

const emit = defineEmits(['update:modelValue']);

const normalizeValue = (value) => {
  if (value === '' || value === null || value === undefined) {
    return null;
  }
  // Tenta converter para número
  const num = Number(value);
  return Number.isNaN(num) ? value : num;
};

const options = ref([
    { value: 1, label: 'Sim' },
    { value: 0, label: 'Não' }
]);

let initialValue = props.modelValue;

// Se modelValue for nulo, tenta usar a prop 'init'
if (initialValue === null || initialValue === undefined) {
    if (props.init !== null && props.init !== undefined && props.init !== false) {
        // Converte o valor de 'init' para 1 ou 0
        initialValue = props.init === true || props.init === '1' || props.init === 1 ? 1 : 0;
    }
}

// Inicializa o estado local (selectedCategory) com o valor determinado
const selectedCategory = ref(normalizeValue(initialValue));


// 2. Watcher para Sincronização Bidirecional (Pai -> Filho)
watch(
  () => props.modelValue,
  (newValue) => {
    selectedCategory.value = normalizeValue(newValue);
  }
);

// 3. Watcher para Emissão (Filho -> Pai)
watch(selectedCategory, (newValue) => {
  // Emite o valor normalizado/convertido (Number ou null)
  emit('update:modelValue', normalizeValue(newValue));
});

// Opcional: Se 'init' for true/false e precisar forçar a seleção no v-model na montagem
/*
onMounted(() => {
    if (props.init !== false && props.modelValue === null) {
        // Força a seleção inicial para que o componente pai receba o valor
        emit('update:modelValue', selectedCategory.value);
    }
});
*/
</script>