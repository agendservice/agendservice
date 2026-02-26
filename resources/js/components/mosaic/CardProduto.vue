<template>
  <div 
    @click="abrirModal"
    class="h-full w-full bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow flex flex-col overflow-hidden relative group border border-gray-100 cursor-pointer"
  >
    
    <div class="h-48 w-full relative overflow-hidden">
      <img 
        v-if="produto && produto.imagem_produto"
        :src="produto.imagem_produto.url" 
        :alt="produto.nome" 
        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
      >
      <div v-else class="w-full h-full bg-gray-100 flex items-center justify-center group-hover:scale-105 transition-transform duration-500">
         <icone icone="mdi-image-off" class="text-4xl text-gray-300"></icone>
      </div>
    </div>

    <div class="p-4 flex flex-col flex-grow">
      <h3 class="text-lg font-bold text-gray-800 mb-1 leading-tight">{{ produto.nome }}</h3>
      
      <p class="text-sm text-gray-500 mb-4 line-clamp-2 flex-grow">
        {{ produto.descricao }}
      </p>

      <div class="flex items-center justify-between mt-auto pt-2 border-t border-gray-50">
        <div class="flex flex-col">
          <span class="text-xs text-gray-400 font-medium" v-if="temTamanhos">A partir de</span>
          <span class="text-lg font-bold text-gray-800">
            R$ {{ formatarPreco(produto.preco) }}
          </span>
        </div>
        
        <button 
          @click.stop="verificarAdicao"
          class="bg-laranja-500 hover:bg-laranja-600 text-white font-medium py-2 px-4 rounded-full flex items-center gap-1 text-sm transition-colors shadow-sm active:scale-95 transform"
        >
          <icone icone="mdi-plus" class="text-base"></icone>
          Adicionar
        </button>
      </div>
    </div>

  </div>

    <ModalProduto 
      v-model="exibirModalProduto" 
      :produto="produto"
      @adicionar="processarAdicaoDoModal"
    />
</template>


<script setup>
import { ref, computed } from 'vue';
import Icone from './Icone.vue'; 
import util from '../../components/Util.vue'; 

const props = defineProps({
  produto: {
    type: Object,
    required: true
  }
});

const emit = defineEmits(['adicionar-ao-carrinho', 'abrir-modal']);

const exibirModalProduto = ref(false);


const formatarPreco = (valor) => {
  return util.formatarDecimal(valor).replace('.', ',');
};

// 3. Função Simples para abrir o modal (Clique no Card)
const abrirModal = () => {
  emit('abrir-modal', props.produto);
};

// Função Lógica do Botão (Mantida)
const verificarAdicao = () => {
  if (props.produto.precisa_detalhes) {
    // Se tem tamanhos, precisa abrir o modal para escolher
    emit('abrir-modal', props.produto);
  } else {
    // Se é produto simples, adiciona direto
    emit('adicionar-ao-carrinho', props.produto);
  }
};



const processarAdicaoDoModal = (itemFormatado) => {
  exibirModalProduto.value = false;
  emit('adicionar-ao-carrinho', itemFormatado);
};
</script>