<template>
  <select-input :label="label" v-model="selectedStatus" :options="options" :class="inputClass" :erro="mensagemErro"></select-input>
  <!-- Exibir erro -->
  <div v-if="mensagemErro" class="text-xs mt-1 text-red-500">
    {{ mensagemErro }}
  </div>
</template>

<script setup>
import { ref, watch} from 'vue';
import SelectInput from '../mosaic/SelectInput.vue';
import { isArray } from 'lodash';

const props = defineProps({
  label: {
    type: String,
    default: 'Status'
  },
  modelValue: {
    type: [Number, String, null], // Adicionando null para robustez
    default: null
  },
  erro: {
    type: Array,
    default: () => ([])
  },
  inputClass: {
    type: String,
    default: ''
  }
});

const emit = defineEmits(['update:modelValue']);

// 1. Inicializa o estado local com o valor da prop
const selectedStatus = ref(props.modelValue); 

const options = ref([
    { value: 'ativo', label: 'ATIVO' },
    { value: 'inativo', label: 'INATIVO' }
]);

// 2. Observa a prop e atualiza o estado local (Pai -> Filho)
watch(
  () => props.modelValue,
  (newValue) => {
    selectedStatus.value = newValue;
  }
);

// 3. Observa o estado local e emite a mudança para o Pai (Filho -> Pai)
watch(selectedStatus, (newValue) => {
  emit('update:modelValue', newValue);
});

const mensagemErro = ref('');
watch(() => props.erro, (v) => {
  if (isArray(v)) {
    mensagemErro.value = v.join(', ');
    return;
  }
  mensagemErro.value = v;

  console.log('Erro atualizado em ListaStatus.vue:', mensagemErro.value);
});

const clearSelection = () => {
    // Define o estado local como null
    selectedStatus.value = null; 
    
    // O 'watch(selectedStatus, ...)' já cuidará de emitir 'null' para o pai.
    // O componente pai verá seu v-model ser alterado para null, limpando o filtro.
};
</script>