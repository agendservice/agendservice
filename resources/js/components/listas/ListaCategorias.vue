<template>
  <autocomplete 
    :label="label"
    v-model="selectedCategoryName"
    :options="options"
    item-label="nome"
    item-value="id"
    :clearable="true"
    :required="required"
    :disabled="options.length === 0"
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
  label: {
    type: String,
    default: 'Categoria'
  },
  required: {
    type: Boolean,
    default: false
  },
  // O v-model deste componente deve ser a STRING que será exibida no input (o nome da categoria).
  modelValue: {
    type: [Number, String, null],
    default: null
  },
  filtroCategoriasPrincipais: {
    type: Boolean,
    default: true
  },
  erro: 
  {
    type: Array,
    default: () => ([])
  }
});

const emit = defineEmits(['update:modelValue']); // Adiciona 'changeId' para emitir o ID

// 2. Estado Reativo
// selectedCategoryName armazena a STRING (Nome da Categoria) para o v-model do Autocomplete
const selectedCategoryName = ref(null);
const selectedCategoryId = ref(props.modelValue); // Armazena o ID da categoria selecionada
const options = ref([]);

// 3. Funções
const buscar = async () => {
  options.value = [];
  try {
    const response = await axios.post('/categorias/buscar', {
      filtros: {
        pagination: false,
        somenteCategoriasPrincipais: props.filtroCategoriasPrincipais
      }
    });
    options.value = response.data.data || response.data || [];
    
    initializeSelection(selectedCategoryId.value);
  } catch (error) {
    emit('error', 'Erro ao buscar categorias.');
  }
};
const initializeSelection = (id) => {
    // Busca o objeto na lista de opções com base no ID
    const selectedItem = options.value.find(opt => opt.id === id);
    
    // Atualiza o NOME que o Autocomplete vai exibir
    selectedCategoryName.value = selectedItem ? selectedItem.nome : null;
};

// Manipula o evento @change do Autocomplete (que envia o item[itemValue], ou seja, o ID)
const handleSelectionChange = (categoryName) => {
    // 1. Encontra o objeto completo (item) usando o nome que o Autocomplete retornou
    const selectedItem = options.value.find(opt => opt.nome === categoryName);
    const newId = selectedItem ? selectedItem.id : null;
    // 2. Atualiza o ID local
    selectedCategoryId.value = newId;

    // 3. Emite o ID para o v-model do componente pai
    emit('update:modelValue', newId);
};

// 4. Observadores

// Sincroniza a Prop Pai -> Filho
watch(
  () => props.modelValue,
  (newId) => {
    selectedCategoryId.value = newId;
    // ⭐️ Atualiza o nome de exibição (label) quando o ID (modelValue) muda
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