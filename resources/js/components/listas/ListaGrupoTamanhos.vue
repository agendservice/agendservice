<template>
  <autocomplete 
    :label="label"
    v-model="selectedGroupName"
    :options="options"
    item-label="nome"
    item-value="id"
    :clearable="true"
    :required="required"
    :disabled="options.length === 0"
    :dica="dica"
    @update:modelValue="handleSelectionChange"
  ></autocomplete>


  <div v-if="erro && erro.length > 0" class="text-red-600 text-xs mt-1">
    {{ erro[0] }}
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import axios from 'axios';
import Autocomplete from '../mosaic/Autocomplete.vue';

// 1. Definição de Props e Emits
const props = defineProps({
  label: {
    type: String,
    default: 'Grupo de Tamanhos'
  },
  // 2. Adicionei a prop 'dica' aqui
  dica: {
    type: String,
    default: ''
  },
  required: {
    type: Boolean,
    default: false
  },
  modelValue: {
    type: [Number, String, null],
    default: null
  },
  erro: 
  {
    type: Array,
    default: () => ([])
  }
});

const emit = defineEmits(['update:modelValue']); 

// 2. Estado Reativo
const selectedGroupName = ref(null);
const selectedGroupId = ref(props.modelValue); 
const options = ref([]);

// 3. Funções
const buscar = async () => {
  options.value = [];
  try {
    const response = await axios.post('/grupo-tamanhos/buscar', {
      filtros: {
        pagination: false,
      }
    });
    options.value = response.data.data || response.data || [];
    
    initializeSelection(selectedGroupId.value);
  } catch (error) {
    // É boa prática não emitir erro aqui se for apenas visual, 
    // mas mantive conforme seu padrão
    console.error('Erro ao buscar grupos:', error);
  }
};

const initializeSelection = (id) => {
    const selectedItem = options.value.find(opt => opt.id === id);
    selectedGroupName.value = selectedItem ? selectedItem.nome : null;
};

const handleSelectionChange = (groupName) => {
    const selectedItem = options.value.find(opt => opt.nome === groupName);
    const newId = selectedItem ? selectedItem.id : null;
    selectedGroupId.value = newId;
    emit('update:modelValue', newId);
};

// 4. Observadores
watch(
  () => props.modelValue,
  (newId) => {
    selectedGroupId.value = newId;
    if (options.value.length > 0) {
        initializeSelection(newId);
    }
  }
);

// 5. Ciclo de Vida
onMounted(() => {
  buscar();
});
</script>