<template>
  <div class="bg-azul-500 min-h-screen py-12 px-4">
    <overlay v-show="loader"></overlay>
    <snackbar-sucesso v-model="estado.sucesso">{{ estado.mensagem }}</snackbar-sucesso>
    <snackbar-erro v-model="estado.erro">{{ estado.mensagem }}</snackbar-erro>
    
    <div class="flex items-center justify-center h-full cols-10">
      <div class="row w-full max-w-6xl bg-white rounded-xl py-8 sm:py-12">
        
        <div class="cols-12 md:cols-5 flex items-center justify-center p-4">
          <img src="/images/logo-facilivery.png" class="h-auto max-h-[200px] rounded-xl" alt="logo">
        </div>

        <div class="cols-12 md:cols-7 px-6 sm:px-10">
          <h1 class="text-3xl text-slate-900 font-light pb-4 mb-4 border-b">
            Crie sua Conta
          </h1>

          <form ref="formRef" @submit.prevent="cadastrar">
            <div class="row gap-y-4">
              <div class="cols-12">
                <h2 class="text-lg font-semibold text-slate-900 mb-2">Dados do proprietário</h2>
              </div>
              <div class="cols-8 sm:cols-12">
                <text-input 
                  label="Nome completo"
                  v-model="form.nome" 
                  :rules="[Validacao.required(form.nome)]"
                  @input="form.nome = Mascara.texto(form.nome)"
                  :ref="(el) => setRef(el, 'nome')"
                  maxlength="60"
                />
              </div>
              <div class="cols-4 sm:cols-12">
                <text-input 
                  label="CPF" 
                  v-model="form.cpf"
                  :rules="[Validacao.required(form.cpf), Validacao.cpfValido(form.cpf)]"
                  @input="form.cpf = Mascara.cpf(form.cpf)"
                  :ref="(el) => setRef(el, 'cpf')"
                  placeholder="XXX.XXX.XXX-XX"
                  maxlength="14"
                />
              </div>
              <div class="cols-4 sm:cols-12">
                <text-input 
                  label="Telefone" 
                  v-model="form.telefone"
                  :rules="[Validacao.required(form.telefone), Validacao.telefoneValido(form.telefone)]"
                  @input="form.telefone = Mascara.telefone(form.telefone)"
                  :ref="(el) => setRef(el, 'telefone')"
                  placeholder="(XX) XXXXX-XXXX" 
                  maxlength="15"
                />
              </div>
              <div class="cols-8 sm:cols-12">
                <text-input 
                  label="Email" 
                  type="email" 
                  v-model="form.email"
                  :rules="[Validacao.required(form.email), Validacao.emailValido(form.email)]"
                  :ref="(el) => setRef(el, 'email')"
                  maxlength="100"
                />
              </div>
              <div class="cols-6 sm:cols-12">
                <text-input 
                  label="Senha" 
                  type="password" 
                  v-model="form.password"
                  :ref="(el) => setRef(el, 'password')"
                  :rules="[Validacao.required(form.password)]"
                  maxlength="100"
                />
              </div>
              <div class="cols-6 sm:cols-12">
                <text-input 
                  label="Confirmação de Senha" 
                  type="password" 
                  v-model="form.password_confirmation"
                  :ref="(el) => setRef(el, 'password_confirmation')"
                  :rules="[Validacao.required(form.password_confirmation), Validacao.matchPasswords(form.password, form.password_confirmation)]"
                  maxlength="100"
                />
              </div>
              
              <div class="cols-12">
                <h2 class="text-lg font-semibold text-slate-900 mb-2">Dados da Empresa</h2>
              </div>
              <div class="cols-6 sm:cols-12">
                <text-input
                  label="Nome da empresa"
                  v-model="form.nome_empresa"
                  :ref="(el) => setRef(el, 'nome_empresa')"
                  :rules="[Validacao.required(form.nome_empresa)]"
                  maxlength="100"
                />
              </div>
              <div class="cols-6 sm:cols-12">
                <text-input 
                  label="Slogan" 
                  v-model="form.slogan" 
                  maxlength="200"
                />
              </div>
              <div class="cols-6 sm:cols-12">
                <text-input 
                  label="CNPJ" 
                  v-model="form.cnpj" 
                  :disabled="form.possui_cnpj"
                  :ref="(el) => setRef(el, 'cnpj')"
                  :rules="[!form.possui_cnpj ? Validacao.required(form.cnpj) : null, Validacao.cnpjValido(form.cnpj)]"
                  @input="form.cnpj = Mascara.cnpj(form.cnpj)"
                  maxlength="18"
                />
              </div>              
              <div class="cols-6 sm:cols-12">
                <text-input
                  label="Telefone de atendimento" 
                  v-model="form.telefone_empresa" 
                  :ref="(el) => setRef(el, 'telefone_empresa')"
                  :rules="[Validacao.required(form.telefone_empresa), Validacao.telefoneValido(form.telefone_empresa)]"
                  @input="form.telefone_empresa = Mascara.telefone(form.telefone_empresa)"
                  maxlength="15"
                />
              </div>
              <div class="cols-12 sm:cols-12 flex items-center gap-2 mt-2 mb-4">
                <input 
                  type="checkbox" 
                  id="possuiCnpj" 
                  v-model="form.possui_cnpj" 
                  class="w-5 h-5 accent-violet-600 border-gray-300 rounded focus:ring-violet-500 transition duration-150" 
                />
                <label for="possuiCnpj" class="text-sm font-small text-slate-900 select-none cursor-pointer">
                  Não possuo CNPJ
                </label>
              </div>
              <div class="cols-6 sm:cols-12">
                <text-input 
                  label="CEP" 
                  v-model="form.cep" 
                  :loader="loadingCep"
                  :ref="(el) => setRef(el, 'cep')"
                  :rules="[Validacao.required(form.cep)]"
                  @input="form.cep = Mascara.cep(form.cep)"
                  maxlength="9"
                />
              </div>
              <div class="cols-6 sm:cols-12">
                <text-input 
                  label="Rua" 
                  v-model="form.rua"
                  :ref="(el) => setRef(el, 'rua')"
                  :disabled="loadingCep || !form.cep"
                  :rules="[Validacao.required(form.rua)]"
                  maxlength="100"
                />
              </div>
              <div class="cols-3 sm:cols-6">
                <text-input
                  label="Número" 
                  v-model="form.numero" 
                  :ref="(el) => setRef(el, 'numero')"
                  :disabled="loadingCep || !form.cep"
                  :rules="[Validacao.required(form.numero)]"
                  maxlength="11"
                />
              </div>
              <div class="cols-3 sm:cols-6">
                <text-input 
                  label="Complemento" 
                  :ref="(el) => setRef(el, 'complemento')"
                  :disabled="loadingCep || !form.cep"
                  v-model="form.complemento" 
                  maxlength="50"
                />
              </div>
              <div class="cols-6 sm:cols-12">
                <text-input 
                  label="Bairro" 
                  :disabled="loadingCep || !form.cep"
                  :ref="(el) => setRef(el, 'bairro')"
                  :rules="[Validacao.required(form.bairro)]"
                  v-model="form.bairro" 
                  maxlength="50"
                />
              </div>
              <div class="cols-6 sm:cols-12">
                  <autocomplete 
                      label="Estado"
                      v-model="form.estado"
                      :options="listaEstados"
                      item-label="nome"
                      item-value="sigla"
                      :disabled="loadingCep || !form.cep"
                      :ref="(el) => setRef(el, 'estado')"
                      :rules="[Validacao.required]"
                      @change="carregarCidades" 
                  />
              </div>

              <div class="cols-6 sm:cols-12">
                  <autocomplete 
                      label="Cidade"
                      v-model="form.cidade"
                      :options="listaCidades"
                      item-label="nome"
                      item-value="nome"
                      :disabled="loadingCep || !form.estado || loadingCidades  || !form.cep"
                      :ref="(el) => setRef(el, 'cidade')"
                      :rules="[Validacao.required]"
                  />
                  <span v-if="loadingCidades" class="text-xs text-gray-500">Carregando cidades...</span>
              </div>
            </div>
            <div class="flex items-center justify-end mt-8 pt-4 border-t">
              <router-link class="text-sm underline hover:no-underline" to="/">Já tem uma conta? Faça login</router-link>
              <botao class="ml-4" text="Cadastrar" color="dark" type="submit" :disabled="loader"></botao>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import axios from 'axios';
import Botao from '../components/mosaic/Botao.vue'; 
import TextInput from '../components/mosaic/TextInput.vue';
import Overlay from '../components/mosaic/Overlay.vue';
import SnackbarSucesso from '../components/mosaic/SnackbarSucesso.vue';
import SnackbarErro from '../components/mosaic/SnackbarErro.vue';
import Validacao from '../components/Validacao.vue';
import Mascara from '../components/Mascara.vue';
import SelectInput from '../components/mosaic/SelectInput.vue';
import Autocomplete from '../components/mosaic/Autocomplete.vue'
import { Estados } from '../Estados.js';
import { ref, watch } from 'vue';

const showPassword = ref(false);
const showConfirmPassword = ref(false);
const listaEstados = ref(Estados);
const listaCidades = ref([]);
const loadingCidades = ref(false);
const loadingCep = ref(false);
const formRef = ref(null);
const loader = ref(false);
const temConjuge = ref(false);
const estado = ref({
  erro: false,
  sucesso: false,
  mensagem: null,
  notificationTimeout: null,
  agora : null,
  countdownTimer: null
});
const form = ref({
  nome: '',
  cpf: '',
  email: '',
  password: '',
  password_confirmation: '',
  nome_empresa: '',
  slogan: '',
  cnpj: '',
  telefone_empresa: '',
  rua: '',
  numero: '',
  bairro: '',
  cidade: '',
  estado: '',
  cep: '',
  complemento: '',
});

const notificar = (tipo, mensagem) => {
  estado.value.mensagem = null;
  estado.value.erro = false;
  estado.value.sucesso = false;
  if (estado.value.notificationTimeout) {
    clearTimeout(estado.value.notificationTimeout);
    estado.value.notificationTimeout = null;
  }
  estado.value.mensagem = mensagem;
  if (tipo === 'success') {
    estado.value.sucesso = true;
  } else if (tipo === 'error') {
    estado.value.erro = true;
  }

  estado.value.notificationTimeout = setTimeout(() => {
    estado.value.mensagem = null;
    estado.value.notificationTimeout = null;
  }, 3000);
}

const cadastrar = () => {
  if (!formValido()) {
     return; 
  }
  loader.value = true;
  axios.post('/registro', form.value)
    .then(response => {
      estado.value.mensagem = response.data.message || 'Cadastro realizado com sucesso! Faça o login para enviar os documentos necessários.';
      notificar('success', estado.value.mensagem);
    })
    .catch(error => {
      if (error.response && error.response.data.errors) {
        const erros = Object.values(error.response.data.errors).flat().join('\n');
        estado.value.mensagem = `Erro: ${erros}`;
      } else {
        estado.value.mensagem = error.response?.data?.message || 'Ocorreu um erro ao realizar o cadastro.';
      }
      notificar('error', estado.value.mensagem);
    })
    .finally(() => {
      loader.value = false;
    });
}

const carregarCidades = async (siglaUf) => {
    // Se não tiver UF selecionada ou for evento de limpar
    if (!siglaUf) {
        listaCidades.value = [];
        return;
    }

    loadingCidades.value = true;
    try {
        const response = await axios.get(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${siglaUf.sigla}/municipios`);
        listaCidades.value = response.data; 
    } catch (error) {
        notificar('error', 'Erro ao carregar lista de cidades.');
    } finally {
        loadingCidades.value = false;
    }
};

const buscarEndereco = async (cep) => {
    const cepLimpo = cep.replace(/\D/g, '');

    if (cepLimpo.length === 8) {
        loadingCep.value = true;
        
        try {
            const response = await axios.get(`https://viacep.com.br/ws/${cepLimpo}/json/`);
            
            if (!response.data.erro) {
                form.value.rua = response.data.logradouro;
                form.value.bairro = response.data.bairro;
                
                form.value.estado = response.data.uf;
                
                await carregarCidades(form.value.estado);
                
                form.value.cidade = response.data.localidade;

                notificar('success', 'Endereço encontrado!');
            } else {
                notificar('error', 'CEP não encontrado.');
                limparEndereco();
            }
        } catch (error) {
            notificar('error', 'Erro ao buscar CEP.');
        } finally {
            loadingCep.value = false;
        }
    }
};

const limparEndereco = () => {
    form.value.rua = '';
    form.value.bairro = '';
    form.value.cidade = '';
    form.value.estado = '';
};

watch(() => form.value.cep, (novoValor) => {
    if (novoValor) {
        buscarEndereco(novoValor);
    }
});

const inputsRefs = ref({}); 

const setRef = (el, nomeDoCampo) => {
  if (el) {
    inputsRefs.value[nomeDoCampo] = el;
  }
};

const formValido = () => {
  let formularioEstaOk = true;
  
  const inputs = Object.values(inputsRefs.value);

  inputs.forEach(inputComponent => {
    if (inputComponent && typeof inputComponent.validar === 'function') {
      const inputValido = inputComponent.validar();
      
      if (!inputValido) {
        formularioEstaOk = false;
      }
    }
  });

  if (formularioEstaOk) {
    return true;
  } else {
    notificar('error', 'Verifique os campos em vermelho.');
    return false;
  }
}

</script>