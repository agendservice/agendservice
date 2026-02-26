<template>
  <autocomplete 
    label="Produtos" 
    v-model="selectedProductName"
    :options="options"
    item-label="nome"
    item-value="id"
    :clearable="true"
    :disabled="options.length === 0 || disabled"
    @update:modelValue="handleSelectionChange"
  ></autocomplete>
  <!--  Exibir erro -->
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
  // O v-model deste componente deve ser a STRING que será exibida no input (o nome da categoria).
  modelValue: {
    type: [Number, String, null],
    default: null
  },
  filtroCategoria: {
    type: [String, Number, null],
    default: null
  },
  erro: {
    type: Array,
    default: () => ([])
  },
  disabled: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['update:modelValue']); // Adiciona 'changeId' para emitir o ID

// 2. Estado Reativo
// selectedProductName armazena a STRING (Nome do Produto) para o v-model do Autocomplete
const selectedProductName = ref(null);
const selectedProductId = ref(props.modelValue); // Armazena o ID do produto selecionado
const options = ref([]);

// 3. Funções
const buscar = async () => {
  if (!props.filtroCategoria) {
    options.value = [];
    selectedProductName.value = null;
    selectedProductId.value = null;
    emit('update:modelValue', null);
    return;
  }
  options.value = [];
  try {
    const response = await axios.post('/produtos/buscar', {
      filtros: {
        pagination: false,
        categoria_id: props.filtroCategoria
      }
    });
    options.value = response.data.data || response.data || null;
    initializeSelection(selectedProductId.value);
  } catch (error) {
    emit('error', 'Erro ao buscar produtos.');
  }
};
const initializeSelection = (id) => {
    // Busca o objeto na lista de opções com base no ID
    const selectedItem = options.value.find(opt => opt.id === id);
    
    // Atualiza o NOME que o Autocomplete vai exibir
    selectedProductName.value = selectedItem ? selectedItem.nome : null;
};

// Manipula o evento @change do Autocomplete (que envia o item[itemValue], ou seja, o ID)
const handleSelectionChange = (productName) => {
    // 1. Encontra o objeto completo (item) usando o nome que o Autocomplete retornou
    const selectedItem = options.value.find(opt => opt.nome === productName);
    const newId = selectedItem ? selectedItem.id : null;
    // 2. Atualiza o ID local
    selectedProductId.value = newId;

    // 3. Emite o ID para o v-model do componente pai
    emit('update:modelValue', newId);
};

// 4. Observadores

// Sincroniza a Prop Pai -> Filho
watch(
  () => props.modelValue,
  (newId) => {
    selectedProductId.value = newId;
    if (options.value.length > 0) {
        initializeSelection(newId);
    }
  }
);

watch(
  () => props.filtroCategoria,
  () => {
    buscar();
  },
  { immediate: true }
);

// 5. Ciclo de Vida
onMounted(() => {
  buscar();
});
</script>