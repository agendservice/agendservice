<template>
  <Transition name="fade">
    <div v-if="modelValue" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      
      <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="fechar"></div>

      <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md relative z-10 overflow-hidden flex flex-col max-h-[90vh]">
        
        <div class="h-40 relative flex-shrink-0">
          <img 
            v-if="produto.imagem_produto"
            :src="produto.imagem_produto.url" 
            class="w-full h-full object-cover"
          >
          <div v-else class="w-full h-full bg-gray-200 flex items-center justify-center">
             <icone icone="mdi-image-off" class="text-3xl text-gray-400"></icone>
          </div>
          
          <button @click="fechar" class="absolute top-3 right-3 bg-white/90 text-gray-700 rounded-full p-1 shadow hover:bg-white transition-colors">
            <icone icone="mdi-close" class="text-xl"></icone>
          </button>
        </div>

        <div class="flex-grow overflow-y-auto custom-scrollbar">
          <div class="p-6">
            <h3 class="text-xl font-bold text-gray-800">{{ produto.nome }}</h3>
            <p class="text-sm text-gray-500 mt-1 mb-4">{{ produto.descricao }}</p>

            <div v-if="produto.tamanhos && produto.tamanhos.length > 0" class="mb-6">
              <label class="block text-sm font-semibold text-gray-700 mb-2">Escolha o tamanho:</label>
              
              <div class="space-y-2">
                <div 
                  v-for="tamanho in produto.tamanhos" 
                  :key="tamanho.id"
                  @click="selecionarTamanho(tamanho)"
                  class="border rounded-lg p-3 cursor-pointer flex justify-between items-center transition-all"
                  :class="tamanhoSelecionado?.id === tamanho.id ? 'border-laranja-500 bg-orange-50 ring-1 ring-laranja-500' : 'border-gray-200 hover:border-gray-300'"
                >
                  <div class="flex items-center gap-3">
                    <div class="w-4 h-4 rounded-full border flex items-center justify-center"
                         :class="tamanhoSelecionado?.id === tamanho.id ? 'border-laranja-500' : 'border-gray-300'">
                      <div v-if="tamanhoSelecionado?.id === tamanho.id" class="w-2 h-2 rounded-full bg-laranja-500"></div>
                    </div>
                    <span class="text-gray-700 font-medium">{{ tamanho.nome }}</span>
                  </div>
                  <span class="font-bold text-gray-900">
                    R$ {{ formatarPreco(tamanho.preco) }}
                  </span>
                </div>
              </div>
            </div>

            <div v-if="gruposAdicionais.length > 0" class="mt-4 border-t border-gray-100 pt-4">
              <h4 class="font-bold text-gray-800 mb-3">Adicionais e Complementos</h4>

              <div v-for="grupo in gruposAdicionais" :key="grupo.id" class="mb-5">
    
                <div class="flex justify-between items-end mb-2">
                  <div class="flex flex-col">
                    <h5 class="font-semibold text-gray-700 text-sm">{{ grupo.nome }}</h5>
                    <span v-if="!ehAtivo(grupo.cobrar_no_limite)" class="text-[10px] text-green-600 font-bold uppercase">
                      Escolha até {{ grupo.limite }} grátis
                    </span>
                    <span v-else-if="ehAtivo(grupo.permite_exceder)" class="text-[10px] text-gray-400">
                      Adicionais extras serão cobrados
                    </span>
                  </div>

                  <span class="text-xs font-medium px-2 py-0.5 rounded" 
                        :class="(adicionaisSelecionados[grupo.id]?.length || 0) > limiteGrupo(grupo) ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-500'">
                    {{ adicionaisSelecionados[grupo.id]?.length || 0 }} / {{ limiteGrupo(grupo) }}
                  </span>
                </div>

                <div class="grid grid-cols-1 gap-2">
                  <div 
                    v-for="item in grupo.itens" 
                    :key="item.id"
                    @click="toggleAdicional(grupo, item)"
                    class="flex justify-between items-center p-3 rounded-lg border cursor-pointer transition-all select-none group"
                    :class="[
                        // Lógica de borda ativa
                      adicionaisSelecionados[grupo.id]?.includes(item.id) 
                        ? 'border-laranja-500 bg-orange-50 ring-1 ring-laranja-500' 
                        : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50',
                      // Lógica de desabilitado
                      isAdicionalDisabled(grupo, item) ? 'opacity-50 cursor-not-allowed bg-gray-50' : ''
                    ]"
                  >
                    <div class="flex items-center gap-3">
                      <div 
                        class="w-5 h-5 rounded flex items-center justify-center transition-colors border"
                        :class="adicionaisSelecionados[grupo.id]?.includes(item.id) ? 'bg-laranja-500 border-laranja-500' : 'border-gray-300 bg-white'"
                      >
                        <icone v-if="adicionaisSelecionados[grupo.id]?.includes(item.id)" icone="mdi-check" class="text-white text-xs"></icone>
                      </div>
                      
                      <span class="text-sm text-gray-700 font-medium">{{ item.nome }}</span>
                    </div>

                    <div class="text-right">
                      <span v-if="!ehAtivo(grupo.cobrar_no_limite) && adicionaisSelecionados[grupo.id]?.includes(item.id) && adicionaisSelecionados[grupo.id].indexOf(item.id) < limiteGrupo(grupo)" 
                            class="text-xs font-bold text-green-600 bg-green-100 px-2 py-1 rounded">
                        GRÁTIS
                      </span>

                      <span v-else class="text-sm font-bold text-gray-900">
                        + R$ {{ util.formatarDecimal(item.preco) }}
                      </span>
                    </div>
                  </div>
                </div>
            </div>
            </div>

            <div v-if="podeEscolherMeioAMeio" class="mt-4 border-t border-gray-100 pt-4">
              <div 
                v-if="modoMeioAMeio === 'inativo'"
                @click="ativarMeioAMeio"
                class="flex items-center gap-3 p-3 rounded-lg border border-dashed border-gray-300 hover:border-laranja-500 hover:bg-orange-50 cursor-pointer text-gray-600 hover:text-laranja-600 transition-all group"
              >
                <div class="bg-gray-100 p-2 rounded-full group-hover:bg-white">
                  <icone icone="mdi-circle-half-full" class="text-xl"></icone>
                </div>
                <div>
                  <span class="block font-semibold text-sm">Adicionar outra metade?</span>
                  <span class="text-xs">Escolha outro sabor do mesmo tamanho</span>
                </div>
              </div>

              <div v-else class="animate-fadeIn">
                <div class="flex justify-between items-center mb-2">
                  <label class="text-sm font-semibold text-gray-700">Escolha o 2º sabor:</label>
                  <button @click="cancelarMeioAMeio" class="text-xs text-red-500 hover:text-red-700 font-medium">
                    Cancelar metade
                  </button>
                </div>

                <div v-if="carregandoSabores" class="py-4 text-center text-gray-400">
                  <icone icone="mdi-loading" class="animate-spin text-2xl mb-1"></icone>
                  <p class="text-xs">Buscando sabores compatíveis...</p>
                </div>

                <input 
                  v-if="!carregandoSabores"
                  v-model="termoBusca"
                  type="text"
                  placeholder="Buscar sabor..."
                  class="w-full text-sm border-gray-200 rounded-lg mb-3 focus:ring-laranja-500 focus:border-laranja-500"
                >

                <div v-if="!carregandoSabores" class="space-y-2 max-h-48 overflow-y-auto pr-1 custom-scrollbar">
                  <div 
                    v-for="sabor in saboresFiltrados" 
                    :key="sabor.id"
                    @click="selecionarSegundoSabor(sabor)"
                    class="p-2 rounded-lg border cursor-pointer flex justify-between items-center text-sm"
                    :class="segundoProdutoSelecionado?.id === sabor.id ? 'bg-green-50 border-green-500 text-green-700' : 'border-gray-100 hover:bg-gray-50'"
                  >
                    <span class="font-medium truncate pr-2">{{ sabor.nome }}</span>
                    <span class="font-bold text-xs whitespace-nowrap">
                      + R$ {{ formatarPreco(sabor.preco_tamanho) }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
            
            <div v-if="modoMeioAMeio === 'ativo' && segundoProdutoSelecionado" class="mt-4 p-3 bg-gray-50 rounded-lg text-sm border border-gray-200">
               <div class="flex items-center gap-2 mb-1">
                 <icone icone="mdi-check-circle" class="text-green-500"></icone>
                 <span class="font-bold text-gray-700">Pedido Meio a Meio:</span>
               </div>
               <div class="pl-6 text-gray-600 space-y-1">
                 <p>½ {{ produto.nome }}</p>
                 <p>½ {{ segundoProdutoSelecionado.nome }}</p>
               </div>
             </div>

          </div>
        </div>

        <div class="mt-6 mb-4 flex justify-center items-center">
          <div class="flex items-center gap-4 bg-gray-50 rounded-full p-2 border border-gray-200 shadow-sm">
            
            <button 
              @click="decrementar"
              class="w-10 h-10 rounded-full bg-white text-azul-500 hover:bg-azul-500 hover:text-white border border-gray-200 flex items-center justify-center transition-colors text-xl font-bold disabled:opacity-50"
              :disabled="produto.medida === 'peso' ? quantidadeAtual <= (produto.valor_inicial || 0) : quantidadeAtual <= 1"
            >
              -
            </button>

            <div class="flex flex-col items-center w-24">
              <span class="text-lg font-bold text-gray-800">{{ textoQuantidade }}</span>
              <span v-if="produto.medida === 'peso'" class="text-[10px] text-gray-400 uppercase font-bold">Peso</span>
              <span v-else class="text-[10px] text-gray-400 uppercase font-bold">Quantidade</span>
            </div>

            <button 
              @click="incrementar"
              class="w-10 h-10 rounded-full bg-white text-azul-500 hover:bg-azul-500 hover:text-white border border-gray-200 flex items-center justify-center transition-colors text-xl font-bold"
            >
              +
            </button>
          </div>
        </div>

        <div class="p-4 border-t border-gray-100 bg-gray-50 mt-auto flex-shrink-0">
          <div class="flex justify-between items-center mb-3">
            <span class="text-gray-600">Total a pagar:</span>
            <span class="text-xl font-bold text-gray-900">R$ {{ formatarPreco(precoFinal) }}</span>
          </div>
          
          <button 
            @click="confirmarAdicao"
            :disabled="!podeAdicionar"
            class="w-full bg-laranja-500 hover:bg-laranja-600 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold py-3 px-4 rounded-xl flex items-center justify-center gap-2 transition-colors shadow-lg shadow-laranja-500/20"
          >
            <span>Adicionar</span>
            <span class="text-xs bg-white/20 px-2 py-0.5 rounded">{{ textoQuantidade }}</span>
          </button>
        </div>

      </div>
    </div>
  </Transition>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import axios from 'axios';
import Icone from './Icone.vue'; // Ajuste o caminho
import util from '../../components/Util.vue'; // Ajuste o caminho

const props = defineProps({
  modelValue: Boolean,
  produto: { type: Object, required: true }
});

const emit = defineEmits(['update:modelValue', 'adicionar']);

// Estados Gerais
const tamanhoSelecionado = ref(null);
const modoMeioAMeio = ref('inativo');
const carregandoSabores = ref(false);
const listaSaboresCompativeis = ref([]);
const segundoProdutoSelecionado = ref(null);
const termoBusca = ref('');
const quantidadeAtual = ref(1);

// Estados Adicionais
const gruposAdicionais = ref([]); 
const adicionaisSelecionados = ref({}); // Ex: { 5: [10, 12], 8: [22] } (Chave=GrupoID, Valor=ArrayIDs)

// --- Inicialização ---

watch(() => props.modelValue, (val) => {
  if (val) {
    resetarEstados();
  }
});

const resetarEstados = () => {
  tamanhoSelecionado.value = null;
  modoMeioAMeio.value = 'inativo';
  segundoProdutoSelecionado.value = null;
  listaSaboresCompativeis.value = [];
  termoBusca.value = '';
  
  // POPULAR ADICIONAIS VINDOS DO PRODUTO (Lazy Loading do Home.vue)
  gruposAdicionais.value = props.produto.adicionais || [];
  
  // Inicializa o objeto de seleção vazio para cada grupo
  const selecaoInicial = {};
  if (gruposAdicionais.value.length > 0) {
    gruposAdicionais.value.forEach(grupo => {
      selecaoInicial[grupo.id] = [];
    });
  }
  if (props.produto.medida === 'peso') {
      // Se for peso, inicia com o valor configurado (ex: 200g) ou 100g se não tiver config
      quantidadeAtual.value = props.produto.valor_inicial || 100;
  } else {
      // Se for unidade, inicia com 1
      quantidadeAtual.value = 1;
  }
  adicionaisSelecionados.value = selecaoInicial;
};

// --- Formatação Utils ---
const formatarPreco = (valor) => {
  if (!valor) return '0,00';
  return util.formatarDecimal(parseFloat(valor)).replace('.', ',');
};

const incrementar = () => {
  if (props.produto.medida === 'peso') {
    const inc = props.produto.incremento || 50; // Padrão 50g se nulo
    quantidadeAtual.value += inc;
  } else {
    quantidadeAtual.value++;
  }
};
const decrementar = () => {
  if (props.produto.medida === 'peso') {
    const min = props.produto.valor_inicial || 100;
    const inc = props.produto.incremento || 50;
    
    if (quantidadeAtual.value - inc >= min) {
      quantidadeAtual.value -= inc;
    }
  } else {
    if (quantidadeAtual.value > 1) {
      quantidadeAtual.value--;
    }
  }
};
const textoQuantidade = computed(() => {
  if (props.produto.medida === 'peso') {
    if (quantidadeAtual.value >= 1000) {
      return (quantidadeAtual.value / 1000).toFixed(1).replace('.', ',') + ' kg';
    }
    return quantidadeAtual.value + 'g';
  }
  return quantidadeAtual.value;
});

// --- Lógica de Tamanhos ---
const selecionarTamanho = (tamanho) => {
  if (tamanhoSelecionado.value?.id !== tamanho.id) {
    modoMeioAMeio.value = 'inativo';
    segundoProdutoSelecionado.value = null;
    listaSaboresCompativeis.value = [];
  }
  tamanhoSelecionado.value = tamanho;
};

// --- Lógica de Seleção ---

const toggleAdicional = (grupo, item) => {
  const selecionados = adicionaisSelecionados.value[grupo.id] || [];
  const index = selecionados.indexOf(item.id);

  if (index > -1) {
    // Remover item (sempre permitido)
    selecionados.splice(index, 1);
  } else {
    // Lógica de Adição
    const qtdAtual = selecionados.length;
    const limite = limiteGrupo(grupo);
    
    // CASO 1: Ainda não atingiu o limite -> Adiciona
    if (qtdAtual < limite) {
      selecionados.push(item.id);
    } 
    // CASO 2: Atingiu limite, mas permite exceder -> Adiciona (será cobrado no cálculo)
    else if (ehAtivo(grupo.permite_exceder)) {
      selecionados.push(item.id);
    }
    // CASO 3: Atingiu limite e NÃO permite exceder (Comportamento de trava ou troca)
    else {
      // Se for limite 1, fazemos o efeito de "Radio Button" (troca)
      if (limite === 1) {
        selecionados.pop(); 
        selecionados.push(item.id);
      } else {
        // Opcional: Efeito de "Shake" ou alerta visual que atingiu o máximo
        console.log("Limite atingido");
      }
    }
  }
  
  adicionaisSelecionados.value[grupo.id] = selecionados;
};

// Verifica se deve bloquear o clique (Visualmente desabilitado)
const isAdicionalDisabled = (grupo, item) => {
  const selecionados = adicionaisSelecionados.value[grupo.id] || [];
  if (selecionados.includes(item.id)) return false;

  // Se permite exceder, NUNCA desabilita (o usuário pode comprar infinitos)
  if (ehAtivo(grupo.permite_exceder)) return false;

  // Se limite é 1, não desabilita para permitir troca rápida
  if (limiteGrupo(grupo) === 1) return false;

  // Bloqueia apenas se atingiu limite E não pode exceder
  return selecionados.length >= limiteGrupo(grupo);
};

// --- CÁLCULO DE PREÇO INTELIGENTE ---
const precoAdicionais = computed(() => {
  let total = 0;

  gruposAdicionais.value.forEach(grupo => {
    const idsSelecionados = adicionaisSelecionados.value[grupo.id] || [];
    
    // Se não tiver selecionados, pula
    if (idsSelecionados.length === 0) return;

    // Recupera os objetos completos dos itens selecionados para pegar o preço
    const itensObj = idsSelecionados.map(id => grupo.itens.find(i => i.id === id)).filter(Boolean);

    if (ehAtivo(grupo.cobrar_no_limite)) {
      // CENÁRIO SIMPLES: Cobra tudo, independente do limite
      total += itensObj.reduce((acc, item) => acc + parseFloat(item.preco), 0);
    } else {
      // CENÁRIO COMPLEXO: Os primeiros N itens são grátis (dentro do limite)
      // Regra de Negócio: Geralmente, cobra-se os adicionais extras. 
      // Mas qual fica de graça? O mais caro? O mais barato? O primeiro clicado?
      // Padrão de mercado: A ordem de seleção define o que é grátis. (O array idsSelecionados mantém a ordem).
      
      const limite = limiteGrupo(grupo);
      itensObj.forEach((item, index) => {
        // Se o índice for menor que o limite, é grátis. Se passar, cobra.
        if (index >= limite) {
          total += parseFloat(item.preco);
        }
      });
    }
  });

  return total;
});

// --- Lógica Meio a Meio ---

const podeEscolherMeioAMeio = computed(() => {
  return props.produto.meio_a_meio === 'ativo' && 
         props.produto.tamanhos && 
         props.produto.tamanhos.length > 0 &&
         tamanhoSelecionado.value !== null;
});

const ativarMeioAMeio = async () => {
  if (!tamanhoSelecionado.value) return;
  modoMeioAMeio.value = 'ativo';
  carregandoSabores.value = true;
  try {
    const response = await axios.post('/produtos/buscar-compativeis', {
      tamanho_id: tamanhoSelecionado.value.id,
      produto_pai_id: props.produto.id,
      categoria_id: props.produto.categoria_id
    });
    listaSaboresCompativeis.value = response.data;
  } catch (error) {
    console.error(error);
    modoMeioAMeio.value = 'inativo';
  } finally {
    carregandoSabores.value = false;
  }
};

const cancelarMeioAMeio = () => {
  modoMeioAMeio.value = 'inativo';
  segundoProdutoSelecionado.value = null;
};

const selecionarSegundoSabor = (sabor) => {
  segundoProdutoSelecionado.value = sabor;
};

const saboresFiltrados = computed(() => {
  if (!termoBusca.value) return listaSaboresCompativeis.value;
  const termo = termoBusca.value.toLowerCase();
  return listaSaboresCompativeis.value.filter(s => s.nome.toLowerCase().includes(termo));
});


// --- Lógica de Preço Total e Finalização ---

const precoFinal = computed(() => {
  // 1. Define o Preço Base (Unitário ou Preço por Kg)
  let base = tamanhoSelecionado.value ? parseFloat(tamanhoSelecionado.value.preco) : parseFloat(props.produto.preco);
  
  // Regra Meio a Meio (Se houver)
  if (modoMeioAMeio.value === 'ativo' && segundoProdutoSelecionado.value) {
    const preco2 = parseFloat(segundoProdutoSelecionado.value.preco_tamanho);
    base = Math.max(base, preco2);
  }

  // 2. Multiplica pela Quantidade/Peso
  let totalProduto = 0;
  
  if (props.produto.medida === 'peso') {
    // Se for peso, o 'base' é o preço do KG.
    // Fórmula: (gramas / 1000) * preco_kg
    totalProduto = (quantidadeAtual.value / 1000) * base;
  } else {
    // Se for unidade: qtd * preco_unitario
    // NOTA: Se o seu sistema calcula a qtd total no carrinho, 
    // aqui no modal mostramos o preço de 1 unidade somada aos adicionais, 
    // ou o preço total da seleção. Normalmente em modal mostramos o total.
    
    // Vou assumir que você quer mostrar o total X quantidade aqui:
    totalProduto = base * quantidadeAtual.value;
  }

  // 3. Soma Adicionais
  // Nota: Adicionais geralmente são cobrados POR UNIDADE do produto principal.
  // Se for peso, geralmente o adicional é um valor fixo somado ao total.
  // Ajuste conforme sua regra de negócio. Aqui vou somar direto.
  return totalProduto + precoAdicionais.value;
});

const podeAdicionar = computed(() => {
  const temTamanho = props.produto.tamanhos?.length > 0 ? tamanhoSelecionado.value !== null : true;
  
  if (modoMeioAMeio.value === 'ativo' && !segundoProdutoSelecionado.value) {
    return false;
  }
  return temTamanho;
});

const confirmarAdicao = () => {
  const payload = {
    id: props.produto.id,
    // Enviamos a quantidade calculada (se for peso, vai enviar 200, 300, etc)
    quantidade: quantidadeAtual.value,
    
    tamanho_id: tamanhoSelecionado.value ? tamanhoSelecionado.value.id : null,
    meio_a_meio: modoMeioAMeio.value === 'ativo',
    segundo_produto_id: modoMeioAMeio.value === 'ativo' && segundoProdutoSelecionado.value ? segundoProdutoSelecionado.value.id : null,
    adicionais: adicionaisSelecionados.value
  };

  emit('adicionar', payload);
  fechar();
};

const fechar = () => {
  emit('update:modelValue', false);
};

// Utilidades locais
const ehAtivo = (valor) => {
  return valor === 'ativo' || valor === true || valor === 1 || valor === '1';
};

const limiteGrupo = (grupo) => {
  const n = Number(grupo.limite);
  return Number.isFinite(n) ? n : 0;
};
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
</style>