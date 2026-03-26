<template>
  <div v-bind="$attrs">
    <overlay v-show="loader"></overlay>
    <form ref="formRef" @submit.prevent>
      <div class="row mb-8">
        <div class="cols-12 cols-md-6 cols-sm-12">
          <select-input v-model="model.empresa_id" required label="Empresa" :options="empresas" item-label="nome" item-value="id"></select-input>
        </div>
        <div class="cols-12 cols-md-6 cols-sm-12">
          <select-input v-model="model.usuario_id" label="Usuário (opcional)" :options="usuarios" item-label="nome" item-value="id"></select-input>
        </div>
        <div class="cols-12 cols-md-6 cols-sm-12">
          <text-input v-model="model.nome" required label="Nome" alt="nome"></text-input>
        </div>
        <div class="cols-12 cols-md-6 cols-sm-12">
          <text-input v-model="model.especialidade" label="Especialidade" alt="especialidade"></text-input>
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
const empresas = ref([]);
const usuarios = ref([]);

const modoCadastro = computed(() => !props.linhaSelecionada.id);

const carregarEmpresas = () => {
  axios.get('/api/empresas')
    .then((response) => {
      empresas.value = response.data?.data ?? [];
    });
};

const carregarUsuarios = () => {
  axios.get('/api/usuarios')
    .then((response) => {
      usuarios.value = response.data?.data ?? [];
    });
};

const load = (id) => {
  Object.keys(model).forEach((key) => delete model[key]);
  loader.value = true;

  axios.get('/funcionario/' + id)
    .then((response) => {
      Object.assign(model, response.data?.data ?? response.data);
    })
    .finally(() => {
      loader.value = false;
    });
};

const payload = () => ({
  ...model,
  empresa_id: model.empresa_id ? Number(model.empresa_id) : null,
  usuario_id: model.usuario_id ? Number(model.usuario_id) : null,
});

const formValido = () => {
  if (!formRef.value?.checkValidity()) {
    return false;
  }
  return !!model.empresa_id && !!model.nome;
};

onMounted(() => {
  carregarEmpresas();
  carregarUsuarios();
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
