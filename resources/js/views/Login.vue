<template>
  <div class="bg-azul-500 h-screen">
    <overlay v-show="loader"></overlay>
    <snackbar-sucesso v-model="estado.sucesso">{{ estado.mensagem }}</snackbar-sucesso>
    <snackbar-erro v-model="estado.erro">{{ estado.mensagem }}</snackbar-erro>
    <div class="flex items-center justify-center h-full">
      <div class="row w-[900px] bg-white rounded-xl py-[50px]">
        <div class="cols-12 cols-md-6 flex items-center justify-center">
          <img src="/images/logo-facilivery.png" class="h-[250px] rounded-xl" alt="logo">
        </div>
        <div class="cols-12 cols-md-6 px-[40px]">
          <h1 class="text-2xl text-slate-900 font-light pb-5">Bem vindo ao Facilivery</h1>
          <!-- Form -->
          <div class="row">
            <div class="cols-12">
              <text-input label="Email" alt="email" type="email" :disabled="loader" v-model="form.email"></text-input>
            </div>
            <div class="cols-12">
              <text-input label="Senha" alt="password" type="password" :disabled="loader" v-model="form.password"></text-input>
            </div>
          </div>
          <div class="row mt-6 gap-y-4">

            <botao class="cols-12" text="Entrar" color="bg-laranja-500 text-white rounded-lg" @click="login()" :disabled="loader"></botao>
            
            <div class="cols-6 flex items-center">
              <router-link class="text-sm underline hover:no-underline text-slate-900" to="/redefinir-senha">
                Esqueci minha senha!
              </router-link>
            </div>

            <div class="cols-6 flex justify-end">
              <router-link class="text-sm underline hover:no-underline text-slate-900" to="/registro">
                Cadastrar-se
              </router-link>
            </div>
          </div>
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
import SnackbarErro from '../components/mosaic/SnackbarErro.vue';
import SnackbarSucesso from '../components/mosaic/SnackbarSucesso.vue';
import { useRouter } from 'vue-router';
import { ref , watch} from 'vue';
import { computed } from 'vue';


const router = useRouter();
const erro = ref(false);
const estado = ref({
  erro: false,
  sucesso: false,
  mensagem: null,
  notificationTimeout: null,
  agora : null,
  countdownTimer: null
});

const loader = ref(false);
const form = ref({
  email: '',
  password: ''
});
const login = () => {
  loader.value = true;
  axios.post('/login', form.value)
  .then((response) => {
    const redirectTo = response.data.redirect;
    if (redirectTo) {
      router.push(redirectTo);
    } else {
      notificar('error', 'Redirecionamento não especificado.');
    }
  }).catch((error) => {
    notificar('error', error.response.data.message);
    loader.value = false;
  });
}
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
    estado.value.erro = false;
    estado.value.sucesso = false;
    estado.value.notificationTimeout = null;
  }, 3000);
}

//usando watch para verificar a variavel window.laravel_flash
if (window.laravel_flash) {
  if (window.laravel_flash.success) {
    notificar('success', window.laravel_flash.success);
  } else if (window.laravel_flash.error) {
    notificar('error', window.laravel_flash.error);
  }
}

</script>