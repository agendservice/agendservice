<template>
  <div class="mb-4 ">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
      {{ label }} <span v-if="required" class="text-red-500">*</span>
    </label>
    <div class="space-y-2 max-h-48 overflow-y-auto border p-2 rounded-lg dark:border-azul-700 dark:bg-azul-600">
      <CheckboxId
        v-for="categoria in categorias"
        :key="categoria.id"
        :item-id="categoria.id"
        :label="categoria.nome.toUpperCase()"
        :modelValue="modelValue"
        @update:modelValue="handleUpdate"
        :disabled="disabled"
        :alt="`${categoria.nome}-${categoria.id}`"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch} from 'vue';
import axios from 'axios';
import CheckboxId from '../mosaic/CheckboxId.vue';

const props = defineProps({
  label: {
    type: String,
    default: 'Categorias de Produto'
  },
  modelValue: {
    type: Array, 
    default: () => [] 
  },
  required: {
    type: Boolean,
    default: false
  },
  disabled: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['update:modelValue']);

const categorias = ref([]);
const loading = ref(false);



const buscarCategorias = async () => {
    loading.value = true;
    axios.post('/categorias/buscar', {
        filtros: {
            pagination: false
        }
    }).then(response => {
        categorias.value = response.data.data || response.data || [];
    }).catch(error => {
        emit('error', 'Erro ao buscar categorias');
    }).finally(() => {
        loading.value = false;
    });
};

const handleUpdate = (newValue) => {
    emit('update:modelValue', newValue);
};

const sanitizarProdIds = (value) => {
    if (!Array.isArray(value) || value.length === 0) {
        return false;
    }
    
    const [firstRow] = value;
    
    if (typeof firstRow === 'string') {
        const ids = firstRow.split(',').map(id => parseInt(id.trim())).filter(id => !isNaN(id));
        emit('update:modelValue', ids);
        return true;
    }
    
    return false;
};

watch(() => props.modelValue, (newValue) => {
    sanitizarProdIds(newValue);
}, { 
    immediate: true 
});

onMounted(() => {
    buscarCategorias();
});

</script>