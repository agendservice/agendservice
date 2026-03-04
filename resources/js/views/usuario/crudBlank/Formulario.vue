<template>
  <div v-bind="$attrs">
    <overlay v-show="loader"></overlay>
    <form ref="formRef" @submit.prevent>
      <div class="row mb-8">
        <div class="cols-12 cols-md-12 cols-sm-12">
          <text-input v-model="model.nome" required :upper="true" label="Nome" alt="nome"></text-input>
        </div>
        <div class="cols-12 cols-md-12 cols-sm-12">
          <text-input v-model="model.email" required type="email" label="E-mail" alt="email"></text-input>
        </div>
        <div class="cols-12 cols-md-6 cols-sm-12">
          <text-input v-model="model.password" :required="modoCadastro" type="password" alt="off" autocomplete="off" label="Senha"></text-input>
        </div>
        <div class="cols-12 cols-md-6 cols-sm-12">
          <text-input v-model="model.password_confirmation" :required="modoCadastro" type="password" alt="off" autocomplete="off" label="Confirmar Senha"></text-input>
        </div>
      </div>
    </form>
    <div class="flex justify-end">
      <botao color="blue" :disabled="!formValido()"
        @click="$emit('click:submit', model)"
        text="Salvar"
        append-icon="mdi-content-save-outline">
      </botao>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import TextInput from '../../../components/mosaic/TextInput.vue';
import Botao from '../../../components/mosaic/Botao.vue';
import Overlay from '../../../components/mosaic/Overlay.vue';

// Props
const props = defineProps({
  linhaSelecionada: {
    type: Object,
    default: () => ({})
  }
});

// Emits
const emit = defineEmits(['click:submit']);

// Refs
const loader = ref(false);
const model = reactive({});
const formRef = ref(null);

// Composables
const route = useRoute();

// Computed
const modoCadastro = computed(() => {
  return !props.linhaSelecionada.id;
});

// Methods
const load = (id) => {
  Object.keys(model).forEach(key => delete model[key]);
  loader.value = true;
  
  axios.get('/usuario/' + id)
    .then((response) => {
      Object.assign(model, response.data);
      loader.value = false;
    })
    .catch(() => {
      loader.value = false;
    });
};

const formValido = () => {
  if (formRef.value?.checkValidity()) {
    if (!modoCadastro.value && ((model.password && model.password == model.password_confirmation) || (!model.password && !model.password_confirmation))) {
      return true;
    }
    if (modoCadastro.value && model.nome && model.email && (model.password && model.password_confirmation) && (model.password === model.password_confirmation)) {
      return true;
    }
    return false;
  }
  return false;
};

// Lifecycle
onMounted(() => {
  if (props.linhaSelecionada.id) {
    load(props.linhaSelecionada.id);
  }
});
</script>
