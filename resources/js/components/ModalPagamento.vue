<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
    
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl overflow-hidden flex flex-col md:flex-row">
      
      <div class="w-full md:w-1/2 p-8 flex flex-col items-center justify-center border-r border-gray-100">
        <h3 class="text-xl font-bold text-gray-700 mb-2">QR CODE da fatura</h3>
        <span class="text-sm text-red-500 font-semibold mb-6 animate-pulse">Não atualize a página</span>

        <div v-if="loading" class="py-10">
            <i class="fas fa-spinner fa-spin text-4xl text-blue-500"></i>
        </div>

        <div v-else class="flex flex-col items-center w-full">
            <img :src="'data:image/jpeg;base64,' + dados.qr_code_base64" class="w-64 h-64 object-contain border-2 border-gray-200 rounded-lg mb-4 shadow-sm" />
            
            <button @click="copiarCodigo" class="bg-azul-500 hover:bg-azul-600 text-white font-bold py-2 px-6 rounded transition w-full max-w-xs flex items-center justify-center gap-2">
                <i class="fas fa-copy"></i> Copiar código Pix
            </button>
            
            <p v-if="copiado" class="mt-2 text-green-600 text-sm font-bold bg-green-100 px-3 py-1 rounded">
                Copiado! Abra o app do seu banco.
            </p>
        </div>
      </div>

      <div class="w-full md:w-1/2 p-8 bg-gray-50 flex flex-col justify-center">
        
        <div v-if="statusPagamento === 'approved'" class="text-center animate-fade-in">
             <img src="/img/payment_success.png" class="w-32 mx-auto mb-4" /> <h3 class="text-2xl font-bold text-green-600 mb-2">Pagamento Realizado!</h3>
             <p class="text-gray-600">Seu plano foi renovado com sucesso.</p>
             <button @click="fecharEAtualizar" class="mt-6 bg-green-500 text-white px-6 py-2 rounded">Continuar</button>
        </div>

        <div v-else>
            <div class="border-b-2 border-azul-500 mb-4 pb-2">
                <h2 class="text-2xl font-bold text-azul-600">Resumo do Pagamento</h2>
            </div>

            <div class="space-y-3 font-mono text-gray-700 text-lg">
                <div class="flex justify-between">
                    <span>Valor Mensalidade:</span>
                    <span class="font-bold">R$ {{ formatMoney(dados.valor_total) }}</span>
                </div>
                <div class="flex justify-between text-xl font-bold text-azul-600 mt-4 pt-4 border-t">
                    <span>Total a Pagar:</span>
                    <span>R$ {{ formatMoney(dados.valor_total) }}</span>
                </div>

                <div class="text-center mt-6 text-sm text-gray-500">
                    Válido até: {{ dados.vencimento }}
                </div>
            </div>
            
            <button @click="$emit('close')" class="mt-8 w-full text-gray-400 hover:text-gray-600 text-sm underline">
                Fechar e pagar depois
            </button>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
  isOpen: {
    type: Boolean,
    required: true
  }
});

const emit = defineEmits(['close']);

const loading = ref(true);
const copiado = ref(false);
const dados = ref({});
const statusPagamento = ref('pending');
let intervaloPolling = null;

// Função para formatar moeda
const formatMoney = (value) => {
    return parseFloat(value).toLocaleString('pt-BR', { minimumFractionDigits: 2 });
}

const gerarCobranca = async () => {
    try {
        loading.value = true;
        const res = await axios.post('/fatura/gerar');
        dados.value = res.data;
        
        // Inicia o loop de verificação (Polling)
        iniciarPolling(res.data.external_reference);
    } catch (e) {
        alert('Erro ao gerar cobrança');
    } finally {
        loading.value = false;
    }
};

const iniciarPolling = (ref) => {
    intervaloPolling = setInterval(async () => {
        try {
            const res = await axios.get(`/fatura/status/${ref}`);
            if (res.data.status === 'approved') {
                statusPagamento.value = 'approved';
                clearInterval(intervaloPolling);
            }
        } catch (e) { console.error(e); }
    }, 3000); // Verifica a cada 3 segundos
};

const copiarCodigo = () => {
    navigator.clipboard.writeText(dados.value.copia_cola);
    copiado.value = true;
    setTimeout(() => copiado.value = false, 3000);
};

const fecharEAtualizar = () => {
    window.location.reload(); // Recarrega para atualizar o status do sistema
}

// Watch para quando o modal abrir
watch(() => props.isOpen, (newVal) => {
    if (newVal) {
        gerarCobranca();
    }
});

onUnmounted(() => {
    if (intervaloPolling) clearInterval(intervaloPolling);
});
</script>

<style scoped>
/* Animação simples de entrada */
.animate-fade-in {
    animation: fadeIn 0.5s ease-in;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
