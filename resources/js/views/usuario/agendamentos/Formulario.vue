<template>
  <div v-bind="$attrs">
    <overlay v-show="loader"></overlay>
    <form ref="formRef" @submit.prevent>
      <div class="row mb-8" v-if="modoCadastro">
        <div class="cols-12 cols-md-6 cols-sm-12">
          <select-input v-model="model.cliente_id" required label="Cliente" :options="clientes" item-label="nome" item-value="id"></select-input>
        </div>
        <div class="cols-12 cols-md-6 cols-sm-12">
          <select-input v-model="model.funcionario_id" required label="Funcionário" :options="funcionarios" item-label="nome" item-value="id"></select-input>
        </div>
        <div class="cols-12 cols-md-6 cols-sm-12">
          <select-input v-model="model.servico_id" required label="Serviço" :options="servicos" item-label="nome" item-value="id"></select-input>
        </div>
        <div class="cols-12 cols-md-6 cols-sm-12">
          <text-input v-model="model.data_hora_inicio" required type="datetime-local" label="Data e hora de início" alt="data_hora_inicio"></text-input>
        </div>
        <div class="cols-12 cols-md-12 cols-sm-12">
          <text-input v-model="model.observacao" label="Observação" alt="observacao"></text-input>
        </div>
      </div>

      <div class="row mb-8" v-else>
        <div class="cols-12 cols-md-6 cols-sm-12">
          <select-input v-model="model.status" required label="Status" :options="statusOptions"></select-input>
        </div>
        <div class="cols-12 cols-md-12 cols-sm-12">
          <text-input v-model="model.observacao" label="Observação" alt="observacao"></text-input>
        </div>
      </div>
    </form>
    <div class="flex justify-end">
      <botao color="blue" :disabled="!formValido()" @click="$emit('click:submit', payload())" text="Salvar" append-icon="mdi-content-save-outline"></botao>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, reactive, ref, watch } from 'vue';
import axios from 'axios';
import TextInput from '../../../components/mosaic/TextInput.vue';
import SelectInput from '../../../components/mosaic/SelectInput.vue';
import Botao from '../../../components/mosaic/Botao.vue';
import Overlay from '../../../components/mosaic/Overlay.vue';

const props = defineProps({
  linhaSelecionada: {
    type: Object,
    default: () => ({})
  }
});

const loader = ref(false);
const model = reactive({});
const formRef = ref(null);
const clientes = ref([]);
const funcionarios = ref([]);
const servicos = ref([]);

const statusOptions = [
  { label: 'Pendente', value: 'pendente' },
  { label: 'Confirmado', value: 'confirmado' },
  { label: 'Cancelado', value: 'cancelado' },
  { label: 'Concluído', value: 'concluido' },
];

const modoCadastro = computed(() => !props.linhaSelecionada.id);

const carregarRelacionamentos = () => {
  axios.get('/api/clientes').then((response) => {
    clientes.value = response.data?.data ?? [];
  });
  axios.get('/api/funcionarios').then((response) => {
    funcionarios.value = response.data?.data ?? [];
  });
  axios.get('/api/servicos').then((response) => {
    servicos.value = response.data?.data ?? [];
  });
};

const formatarDataHoraParaInput = (valor) => {
  if (!valor) {
    return null;
  }
  return String(valor).substring(0, 16).replace(' ', 'T');
};

const formatarDataHoraParaApi = (valor) => {
  if (!valor) {
    return null;
  }
  return valor.replace('T', ' ') + ':00';
};

const load = (id) => {
  Object.keys(model).forEach((key) => delete model[key]);
  loader.value = true;

  axios.get('/agendamento/' + id)
    .then((response) => {
      const dados = response.data?.data ?? response.data;
      Object.assign(model, dados);
      if (model.data_hora_inicio) {
        model.data_hora_inicio = formatarDataHoraParaInput(model.data_hora_inicio);
      }
    })
    .finally(() => {
      loader.value = false;
    });
};

const payload = () => {
  if (modoCadastro.value) {
    return {
      cliente_id: model.cliente_id ? Number(model.cliente_id) : null,
      funcionario_id: model.funcionario_id ? Number(model.funcionario_id) : null,
      servico_id: model.servico_id ? Number(model.servico_id) : null,
      data_hora_inicio: formatarDataHoraParaApi(model.data_hora_inicio),
      observacao: model.observacao ?? null,
    };
  }

  return {
    id: model.id,
    status: model.status,
    observacao: model.observacao ?? null,
  };
};

const formValido = () => {
  if (!formRef.value?.checkValidity()) {
    return false;
  }
  if (modoCadastro.value) {
    return !!model.cliente_id && !!model.funcionario_id && !!model.servico_id && !!model.data_hora_inicio;
  }
  return !!model.status;
};

onMounted(() => {
  carregarRelacionamentos();
  if (!modoCadastro.value) {
    load(props.linhaSelecionada.id);
  }
});

watch(
  () => props.linhaSelecionada?.id,
  (id) => {
    if (id) {
      load(id);
      return;
    }
    Object.keys(model).forEach((key) => delete model[key]);
  }
);
</script>
