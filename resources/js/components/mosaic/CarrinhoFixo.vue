<template>
  <div v-if="temItens">
    
    <transition name="fade">
      <div v-if="expandido" class="fixed inset-0 bg-black/60 z-40 backdrop-blur-sm" @click="expandido = false"></div>
    </transition>

    <div class="fixed bottom-0 left-0 w-full z-50 px-3 pb-3 flex justify-center pointer-events-none">
      <div class="w-full max-w-3xl pointer-events-auto relative">
        
        <transition name="slide-up">
          <div v-if="expandido" class="bg-white rounded-t-2xl shadow-xl overflow-hidden mb-[-15px] pb-24">
            
            <div class="flex justify-between items-center p-4 border-b border-gray-100">
              <h2 class="font-bold text-gray-800 text-lg">Seu Pedido</h2>
              <div class="flex items-center gap-2">
                <button @click="confirmarLimpeza" class="text-sm text-laranja-600 hover:text-laranja-800 font-medium px-3 py-1 rounded-full hover:bg-laranja-50 transition-colors mr-2">Limpar</button>
                <button @click="expandido = false" class="bg-azul-500 rounded-full p-1 text-white hover:bg-azul-600">
                  <icone icone="mdi-close" class="text-2xl"></icone>
                </button>
              </div>
            </div>

            <div class="max-h-[60vh] overflow-y-auto bg-gray-50">
              
              <div class="p-4 space-y-4 bg-white mb-2">
                <div v-for="item in itensRenderizaveis" :key="item.produto_id + '-' + (item.tamanho_id || '')" class="flex gap-4">
                  
                  <div class="w-20 h-20 flex-shrink-0 relative">
                     <button class="absolute -top-2 -left-2 bg-red-600 text-white rounded-full h-6 w-6 flex items-center justify-center shadow-md hover:bg-red-700 transition-colors" @click.stop="emit('remover', item.id)">
                      <icone icone="mdi-close" class="text-sm"></icone>
                    </button>
                    <img v-if="item.image" :src="item.image" class="w-full h-full rounded-lg object-cover bg-gray-100" />
                    <div v-else class="w-full h-full rounded-lg bg-gray-200 flex items-center justify-center text-gray-400">
                      <icone icone="mdi-image-off" class="text-2xl"></icone>
                    </div>
                  </div>

                  <div class="flex-1 flex flex-col justify-between">
                    
                    <div class="flex justify-between items-start">
                      <div class="flex flex-col pr-2">
                        <h3 class="font-medium text-gray-700 text-sm md:text-base line-clamp-2 leading-tight">{{ item.nome }}</h3>
                        
                        <div v-if="item.opcoes && item.opcoes.adicionais && item.opcoes.adicionais.length > 0" class="mt-1.5 space-y-1">
                          <div v-for="(add, idx) in item.opcoes.adicionais" :key="idx" class="flex flex-wrap items-center gap-1 text-xs text-gray-500">
                            <span>+ {{ add.nome }}</span>
                            <span v-if="add.is_gratis" class="bg-green-100 text-green-700 px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide">Grátis</span>
                            <span v-else class="font-semibold text-gray-700">(R$ {{ formatarMoeda(add.preco_cobrado) }})</span>
                          </div>
                        </div>
                      </div>

                      <span class="font-bold text-gray-800 text-sm whitespace-nowrap">R$ {{ formatarMoeda(item.subtotal) }}</span>
                    </div>

                    <div class="flex justify-between items-end mt-2">
                         <p class="text-xs text-gray-400 mb-1">
                            {{ item.medida === 'peso' ? 'Preço/Kg:' : 'Unitário:' }} 
                            R$ {{ formatarMoeda(item.preco) }}
                         </p>
                         
                         <div class="flex items-center gap-3">
                            <botao 
                              @click="emit('alterar-quantidade', item.id, item.medida === 'peso' ? -(item.incremento || 50) : -1)" 
                              v-if="(item.medida === 'peso' ? item.quantidade > (item.valor_inicial || 50) : item.quantidade > 1)" 
                              color="bg-azul-600 rounded-full text-white hover:bg-laranja-500" 
                              text="-" 
                              class="w-8 h-8 flex items-center justify-center rounded-full font-bold transition-colors" 
                            />
                            
                            <botao 
                              @click="emit('alterar-quantidade', item.id, -item.quantidade)" 
                              v-else 
                              color="bg-red-600 rounded-full text-white hover:bg-red-700" 
                              icone="mdi-delete-outline" 
                              class="w-8 h-8 flex items-center justify-center rounded-full font-bold transition-colors" 
                            />
                            
                            <span class="text-sm font-semibold min-w-[2rem] text-center px-1">
                                {{ item.medida === 'peso' ? (item.quantidade >= 1000 ? (item.quantidade/1000)+'kg' : item.quantidade+'g') : item.quantidade }}
                            </span>
                            
                            <botao 
                              @click="emit('alterar-quantidade', item.id, item.medida === 'peso' ? (item.incremento || 50) : 1)" 
                              color="bg-azul-600 rounded-full text-white hover:bg-laranja-500" 
                              text="+" 
                              class="w-8 h-8 flex items-center justify-center rounded-full font-bold transition-colors" 
                            />
                         </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="bg-white p-4 mb-2">
                 <h3 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                   <icone icone="mdi-ticket-percent-outline" class="text-gray-500"></icone>
                   Cupom de desconto
                 </h3>
                 
                 <div v-if="!cupomObjeto" class="flex gap-2">
                   <input v-model="cupomInput" type="text" placeholder="Digite seu código" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-red-500 uppercase" @keyup.enter="aplicarCupomLocal" />
                   <button @click="aplicarCupomLocal" :disabled="!cupomInput || loadingCupom" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                     {{ loadingCupom ? '...' : 'Aplicar' }}
                   </button>
                 </div>

                 <div v-else class="flex flex-col gap-2">
                    <div class="flex items-center justify-between bg-gray-50 border rounded-lg p-3" 
                         :class="faltaParaDesconto > 0 ? 'border-orange-200 bg-orange-50' : 'border-green-200 bg-green-50'">
                        
                        <div class="flex flex-col">
                          <span v-if="faltaParaDesconto <= 0" class="text-xs text-green-600 font-bold uppercase tracking-wider">Cupom Ativo</span>
                          <span v-else class="text-xs text-orange-600 font-bold uppercase tracking-wider">Complete o valor</span>
                          
                          <span class="font-bold text-gray-800 uppercase">{{ cupomObjeto.codigo }}</span>
                        </div>
                        
                        <button @click="emit('remover-cupom')" class="text-red-500 hover:text-red-700 text-sm font-medium px-2 py-1">Remover</button>
                    </div>

                    <div v-if="faltaParaDesconto > 0" class="text-sm bg-orange-100 text-orange-800 p-3 rounded-lg flex flex-col gap-1 animate-pulse">
                        <div class="flex justify-between font-medium">
                            <span>Adicione mais itens</span>
                            <span>Faltam R$ {{ formatarMoeda(faltaParaDesconto) }}</span>
                        </div>
                        <p class="text-xs opacity-80">
                            Para ativar o desconto, o pedido mínimo é de R$ {{ formatarMoeda(valorMinimoCupom) }}.
                        </p>
                        <div class="w-full bg-orange-200 h-1.5 rounded-full mt-1">
                            <div class="bg-orange-500 h-1.5 rounded-full transition-all duration-500" 
                                 :style="{ width: percentualConcluido + '%' }"></div>
                        </div>
                    </div>
                 </div>
              </div>

              <div class="bg-white p-4 space-y-2 text-sm">
                <div class="flex justify-between text-gray-500">
                  <span>Subtotal</span>
                  <span>R$ {{ formatarMoeda(valorTotal) }}</span>
                </div>
                
                <div v-if="valorDesconto > 0" class="flex justify-between text-green-600 font-medium">
                  <span>Desconto</span>
                  <span>- R$ {{ formatarMoeda(valorDesconto) }}</span>
                </div>
                
                <div class="flex justify-between text-gray-900 font-bold text-base pt-2 border-t border-gray-100">
                  <span>Total</span>
                  <span>R$ {{ formatarMoeda(valorFinal) }}</span>
                </div>
              </div>

            </div>
          </div>
        </transition>

        <div @click="acaoPrincipal" class="bg-azul-500 hover:bg-azul-600 transition-colors text-white p-4 rounded-xl shadow-lg cursor-pointer flex justify-between items-center relative z-50">
          <div class="flex items-center gap-3">
            <div class="bg-branco-500 px-2 py-1 rounded-xl relative">
              <icone icone="mdi-shopping-outline" class="text-xl text-azul-500"></icone>
              <span class="absolute -top-1 -right-1 bg-white text-red-600 text-[10px] font-bold h-4 w-4 rounded-full flex items-center justify-center">{{ qtdTotal }}</span>
            </div>
            <div class="flex flex-col leading-tight">
              <span class="text-xs font-light opacity-90">{{ qtdTotal }} item(s)</span>
              <span class="font-bold text-lg">R$ {{ formatarMoeda(expandido ? valorFinal : valorTotal) }}</span>
            </div>
          </div>

          <div class="flex items-center gap-2 font-medium text-sm md:text-base">
            <span>{{ expandido ? 'Finalizar pedido' : 'Ver carrinho' }}</span>
            <icone :icone="expandido ? 'mdi-chevron-down' : 'mdi-chevron-up'" class="text-xl"></icone>
          </div>
        </div>

      </div>
    </div>
    
    <modal titulo="Remover Registro" v-model="exibirRemover" texto-confirmar="Remover" @submit:confirmar="$emit('limpar')">
      <span style="font-size: 22px; text-align:center; display: block" class="my-6">Tem certeza que deseja limpar todo o carrinho?</span>
    </modal>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import Modal from './Modal.vue';
import Botao from './Botao.vue';
import Icone from '../mosaic/Icone.vue';

const props = defineProps({
  carrinho: {
    type: Object,
    required: true,
    default: () => ({})
  },
  loadingCupom: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits([
  'update:carrinho', 'finalizar', 'alterar-quantidade', 'remover', 'limpar', 'aplicar-cupom', 'remover-cupom'
]);

const exibirRemover = ref(false);
const expandido = ref(false);
const cupomInput = ref('');

// === COMPUTEDS DE CARRINHO ===
const temItens = computed(() => {
  return props.carrinho && props.carrinho.itens && Object.keys(props.carrinho.itens).length > 0;
});

const itensRenderizaveis = computed(() => {
  if (!props.carrinho) return [];
  const listaDeItens = props.carrinho.itens || {};
  
  return Object.entries(listaDeItens).map(([key, item]) => {
    const precoBase = parseFloat(item.preco);
    const valorAdicionais = parseFloat(item.valor_adicionais || 0); // Pega o valor dos adicionais
    const qtdNum = parseInt(item.quantidade);
    
    let subtotalCalculado = 0;

    // APLICA A LÓGICA DE MEDIDA (IGUAL AO BACKEND)
    if (item.medida === 'peso') {
        // (Gramas / 1000 * PreçoBase) + Adicionais Fixos
        const precoDoPeso = (qtdNum / 1000) * precoBase;
        subtotalCalculado = precoDoPeso + valorAdicionais;
    } else {
        // Unidade * (PreçoBase + Adicionais)
        subtotalCalculado = qtdNum * (precoBase + valorAdicionais);
    }

    return {
      ...item,
      id: key, 
      preco: precoBase,
      quantidade: qtdNum,
      // Se o backend já mandou o subtotal, usamos ele. Se não, usamos o nosso cálculo.
      subtotal: item.subtotal ? parseFloat(item.subtotal) : subtotalCalculado
    };
  });
});

const valorTotal = computed(() => {
  return itensRenderizaveis.value.reduce((acc, item) => acc + item.subtotal, 0);
});

// Correção visual: Se for peso (ex: 500g), conta como 1 item no carrinho, não 500.
const qtdTotal = computed(() => {
  return itensRenderizaveis.value.reduce((acc, item) => {
    if (item.medida === 'peso') {
        return acc + 1; 
    }
    return acc + item.quantidade;
  }, 0);
});

// === COMPUTEDS DO CUPOM ===
const cupomObjeto = computed(() => {
  return props.carrinho ? props.carrinho.cupom || null : null;
});

const valorDesconto = computed(() => {
  return cupomObjeto.value ? parseFloat(cupomObjeto.value.valor_desconto) : 0;
});

const valorMinimoCupom = computed(() => {
  return cupomObjeto.value ? parseFloat(cupomObjeto.value.valor_minimo) : 0;
});

const faltaParaDesconto = computed(() => {
  if (valorMinimoCupom.value <= 0) return 0;
  const diferenca = valorMinimoCupom.value - valorTotal.value;
  return diferenca > 0 ? diferenca : 0;
});

const percentualConcluido = computed(() => {
  if (valorMinimoCupom.value <= 0) return 100;
  const perc = (valorTotal.value / valorMinimoCupom.value) * 100;
  return perc > 100 ? 100 : perc;
});

const valorFinal = computed(() => {
  const total = valorTotal.value - valorDesconto.value;
  return total > 0 ? total : 0;
});

// === UTILS ===
const formatarMoeda = (val) => {
  if (!val && val !== 0) return '0,00';
  return val.toFixed(2).replace('.', ',');
};

const acaoPrincipal = () => {
  if (expandido.value) {
    emit('finalizar');
  } else {
    expandido.value = true;
  }
};

const confirmarLimpeza = () => {
  exibirRemover.value = true;
  expandido.value = false;
};

const aplicarCupomLocal = () => {
  if (!cupomInput.value) return;
  emit('aplicar-cupom', cupomInput.value.toUpperCase());
  cupomInput.value = '';
};
</script>

<style scoped>
.slide-up-enter-active,
.slide-up-leave-active {
  transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
}
.slide-up-enter-from,
.slide-up-leave-to {
  transform: translateY(100%);
  opacity: 0;
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>