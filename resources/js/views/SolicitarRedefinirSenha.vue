<template>
  <div class="bg-violet-800 h-screen">
    <overlay v-show="loader"></overlay>
    <snack-error v-if="errorMessage" :message="errorMessage"></snack-error>
    <snack-success v-if="successMessage" :message="successMessage"></snack-success>
    <div class="flex items-center justify-center h-full">
      <div class="row w-[900px] bg-white rounded-xl py-[50px]">
        <div class="cols-12 cols-md-6 flex items-center justify-center">
          <img src="/images/logo.jpg" class="h-[250px]" alt="logo">
        </div>
        <div class="cols-12 cols-md-6 px-[40px]">
          <h1 class="text-2xl text-slate-900 dark:text-slate-100 font-light pb-5">Definir Nova Senha</h1>
          <div class="row">
            <div class="cols-12">
              <text-input 
                label="Email" 
                alt="email" 
                type="email" 
                :disabled="loader" 
                v-model="form.email"
                required
              ></text-input>
            </div>
          </div>
          <div class="row mt-6 gap-y-4">
            <botao class="cols-12" text="Redefinir" color="dark" @click="redefinirSenha()" :disabled="loader"></botao>
            <div class="cols-12 flex items-center">
              <router-link class="text-sm underline hover:no-underline text-slate-900" to="/">
                Voltar para login
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Botao from '../components/mosaic/Botao.vue';
import TextInput from '../components/mosaic/TextInput.vue';
import Overlay from '../components/mosaic/Overlay.vue';
import Icone from '../components/mosaic/Icone.vue';
import { RouterLink } from 'vue-router';
import SnackError from '../components/mosaic/SnackbarErro.vue';
import SnackSuccess from '../components/mosaic/SnackbarSucesso.vue';

export default {
  name: 'RedefinirSenha',
  components: {
    TextInput,
    Botao,
    Overlay,
    Icone,
    SnackError,
    SnackSuccess
  },
  data () {
    return {
      // O v-model irá popular este objeto
      form: {
        email: '',
      }, 
      successMessage: null,
      errorMessage: null,
      loader: false
    }
  },
  methods: {
    redefinirSenha: function () {
      this.loader = true;
      
      axios.post('/solicitar-codigo', this.form)
      .then((response) => {
        // Redireciona para a página de redefinição de senha
        this.$router.push({ name: 'Resetar Senha' });
        this.loader = false;
      }, (error) => {
        this.notificar('error', error.response.data.message);
        this.loader = false;
      });
    },
    notificar: function (tipo, mensagem) {
      this.successMessage = null;
      this.errorMessage = null;
      if (tipo === 'success') {
        this.successMessage = mensagem;
        setTimeout(() => {
          this.successMessage = null;
        }, 3000);
      } else if (tipo === 'error') {
        this.errorMessage = mensagem;
        setTimeout(() => {
          this.errorMessage = null;
        }, 3000);
      }
    }
  }
}
</script>