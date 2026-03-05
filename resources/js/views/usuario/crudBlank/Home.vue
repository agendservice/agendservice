<template>
  <app-intranet 
  icone="mdi-rule" 
  :exibir-cadastro="true" 
  @submit:filtro="buscar()" 
  @submit:cadastrar="linhaSelecionada = {}" 
  @submit:remover="remover(linhaSelecionada)" ref="app">
    
    <template v-slot:resultados>
      <overlay v-show="loader" />
      <snackbar-sucesso v-model="sucesso">{{ mensagem }}</snackbar-sucesso>
      <snackbar-erro v-model="erro">{{ mensagem }}</snackbar-erro>
      <tabela
        v-model="resultados"
        :colunas="colunas"
        :filtros="resultados.filtros"
        @mudar-pagina="mudarPagina"
        @buscar="buscar()"
        @editar="prepararEdicao($event)"
        @remover="prepararRemocao($event)"
      />
    </template>
    
    <template v-slot:formulario>
      <overlay v-show="loader"></overlay>
      <formulario :linha-selecionada="linhaSelecionada" @click:submit="submit($event)"></formulario>
    </template>

  </app-intranet>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';
import AppIntranet from '../../../components/AppLayout.vue';
import Tabela from '../../../components/mosaic/Tabela.vue';
import Formulario from './Formulario.vue';
import Overlay from '../../../components/mosaic/Overlay.vue';
import SnackbarSucesso from '../../../components/mosaic/SnackbarSucesso.vue';
import SnackbarErro from '../../../components/mosaic/SnackbarErro.vue';

// Refs
const loader = ref(false);
const tipo = ref('');
const linhaSelecionada = ref({});
const sucesso = ref(false);
const erro = ref(false);
const mensagem = ref('');
const app = ref(null);
let notificationTimeout = null;

// Reactive objects
const resultados = reactive({
  data: [],
  meta: {},
  filtros: { per_page: 10 }
});

const colunas = [
  { text: 'Nome', value: 'nome' },
  { text: 'Email', value: 'email' },
];

// Methods
const buscar = () => {
  loader.value = true;
  axios.post('/usuario', resultados.filtros)
    .then(({ data }) => {
      resultados.data = data.data;
      resultados.meta = data.meta;
    })
    .catch(error => {
      erro.value = true;
      mensagem.value = "Erro ao buscar regras.";
    })
    .finally(() => {
      loader.value = false;
    });
};

const submit = (model) => {
  app.value.exibirFormulario = false;
  loader.value = true;
  const promise = model.id 
    ? axios.put('/usuario/atualizar', model)
    : axios.post('/usuario/cadastrar', model);

  promise
    .then(response => {
      tipo.value = 'success';
      mensagem.value = 'Salvo com Sucesso!';
      buscar();
    })
    .catch(erro => {
      if (erro.response?.status === 422) {
        tipo.value = 'error';
        mensagem.value = 'Por favor, corrija os erros no formulário.';
      } else {
        tipo.value = 'error';
        mensagem.value = erro.response?.data?.message || 'Erro ao salvar.';
      }
    })
    .finally(() => {
      loader.value = false;
      notificar(tipo.value, mensagem.value);
    });
};

const remover = (model) => {
  loader.value = true;
  axios.delete('/regras/' + model.id)
    .then(response => {
      tipo.value = 'success';
      mensagem.value = 'Removido Com Sucesso';
      app.value.exibirRemover = false;
      buscar();
    })
    .catch(err => {
      tipo.value = 'error';
      mensagem.value = err.response?.data?.message || 'Erro Ao Remover Registro';
    })
    .finally(() => {
      loader.value = false;
      notificar(tipo.value, mensagem.value);
    });
};

const prepararEdicao = (linha) => {
  console.log(linha);
  linhaSelecionada.value = linha;
  app.value.exibirFormulario = true;
};

const prepararRemocao = (linha) => {
  console.log(linha);
  linhaSelecionada.value = linha;
  app.value.exibirRemover = true;
};

const mudarPagina = (link) => {
  if (link.url) {
    loader.value = true;
    axios.post(link.url, resultados.filtros)
      .then(({ data }) => {
        resultados.data = data.data;
        resultados.meta = data.meta;
        loader.value = false;
      });
  }
};

const notificar = (tipoNotif, mensagemNotif) => {
  mensagem.value = null;
  erro.value = false;
  sucesso.value = false;
  
  if (notificationTimeout) {
    clearTimeout(notificationTimeout);
  }

  if (tipoNotif === 'success') {
    mensagem.value = mensagemNotif;
    sucesso.value = true;
  } else if (tipoNotif === 'error') {
    mensagem.value = mensagemNotif;
    erro.value = true;
  }

  notificationTimeout = setTimeout(() => {
    mensagem.value = null;
    sucesso.value = false;
    erro.value = false;
    notificationTimeout = null;
  }, 3000);
};

// Lifecycle
onMounted(() => {
  buscar();
});
</script>
