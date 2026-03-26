<template>
  <div v-bind="$attrs">
    <overlay v-show="loader"></overlay>
    <form ref="formRef" @submit.prevent>
      <div class="row mb-8">
        <div class="cols-12 cols-md-12 cols-sm-12">
          <text-input v-model="model.nome" required label="Nome" alt="nome"></text-input>
        </div>
        <div class="cols-12 cols-md-6 cols-sm-12">
          <text-input v-model="model.cnpj" label="CNPJ" alt="cnpj"></text-input>
        </div>
        <div class="cols-12 cols-md-6 cols-sm-12">
          <text-input v-model="model.email" type="email" label="E-mail" alt="email"></text-input>
        </div>
        <div class="cols-12 cols-md-6 cols-sm-12">
          <text-input v-model="model.telefone" label="Telefone" alt="telefone"></text-input>
        </div>
        <div class="cols-12 cols-md-6 cols-sm-12">
          <text-input v-model="model.endereco" label="Endereço" alt="endereco"></text-input>
        </div>
      </div>
    </form>
    <div class="flex justify-end">
      <botao color="blue" :disabled="!formValido()" @click="$emit('click:submit', model)" text="Salvar" append-icon="mdi-content-save-outline"></botao>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, reactive, ref, watch } from 'vue';
import axios from 'axios';
import TextInput from '../../../components/mosaic/TextInput.vue';
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

const modoCadastro = computed(() => !props.linhaSelecionada.id);

const load = (id) => {
  Object.keys(model).forEach((key) => delete model[key]);
  loader.value = true;

  axios.get('/empresa/' + id)
    .then((response) => {
      Object.assign(model, response.data?.data ?? response.data);
    })
    .finally(() => {
      loader.value = false;
    });
};

const formValido = () => {
  if (!formRef.value?.checkValidity()) {
    return false;
  }

  return !!model.nome;
};

onMounted(() => {
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
