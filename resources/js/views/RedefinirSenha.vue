<template>
  <div class="bg-violet-800 h-screen">
    <overlay v-show="loader"></overlay>
    <snack-error v-model="erro">{{ mensagemErro }}</snack-error>
    <snack-success v-model="sucesso">{{ mensagemSucesso }}</snack-success>
    
    <div class="flex items-center justify-center h-full">
      <div class="row w-[900px] bg-white rounded-xl py-[50px]">
        <div class="cols-12 cols-md-6 flex items-center justify-center">
          <img src="/images/logo.jpg" class="h-[250px]" alt="logo">
        </div>
        <div class="cols-12 cols-md-6 px-[40px]">
          <h1 class="text-2xl text-slate-900 dark:text-slate-100 font-light pb-5">Definir Nova Senha</h1>
          <p class="text-sm text-gray-600 mb-4">
            Por favor, informe seu e-mail, o código que você recebeu e sua nova senha.
          </p>
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
            
            <div class="cols-12">
              <text-input 
                label="Código de Verificação" 
                alt="codigo" 
                type="text" 
                :disabled="loader" 
                v-model="form.codigo"
                placeholder="Código recebido no e-mail"
                maxlength="6"
                required
              ></text-input>
              <botao 
                @click="solicitarNovoCodigo" 
                color="dark" 
                :text="tituloBotaoSolicitacao" 
                class="mt-2" 
                :disabled="loader || !form.email || form.codigo || (agora && ((new Date() - agora) < 60000))" 
                size="small" 
                icone="mdi-refresh-cw"
              >
              </botao>
            </div>

            <div class="cols-12">
              <text-input 
                label="Nova Senha" 
                alt="password" 
                type="password" 
                :disabled="loader" 
                v-model="form.password"
                required
              ></text-input>
            </div>
            
            <div class="cols-12">
              <text-input 
                label="Confirmar Senha" 
                alt="password_confirmation" 
                type="password" 
                :disabled="loader" 
                v-model="form.password_confirmation"
                required
              ></text-input>
            </div>

          </div>
          <div class="row mt-6 gap-y-4">
            <botao class="cols-12" text="Definir Nova Senha" color="dark" @click="redefinirSenha()" :disabled="loader"></botao>
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
      form: {
        email: '',
        codigo: '',
        password: '',
        password_confirmation: ''
      }, 
      erro: false,
      sucesso: false,
      mensagemSucesso: null,
      mensagemErro: null,
      loader: false,
      notificationTimeout: null,
      agora : null,
      tituloBotaoSolicitacao: 'Solicitar novo código',
      countdownTimer: null
    }
  },
  mounted() {
    // Tenta preencher o e-mail se ele veio da URL
    if (this.$route.query.email) {
      this.form.email = this.$route.query.email;
    }
  },
  methods: {
    redefinirSenha: function () {
      this.loader = true;
      
      axios.post('/verificar-codigo', this.form)
        .then((response) => {
          this.notificar('success', response.data.message || 'Senha redefinida com sucesso! Você será redirecionado para o login.');
          this.loader = false;
          setTimeout(() => {
            this.$router.push({ path: '/' }); 
          }, 5000); // 5 segundos
        })
        .catch((error) => {
          this.notificar('error', error.response.data.message || 'Ocorreu um erro.');
          this.loader = false;
        });
    },
    solicitarNovoCodigo: function () {
        this.loader = true;
        this.notificar(null, null);
        axios.post('/solicitar-codigo', { email: this.form.email })
          .then((response) => {
            this.notificar('success', response.data.message || 'Um novo código foi enviado para o seu e-mail.');
            
            this.iniciarContagemRegressiva(); 
          })
          .catch((error) => {
            console.log(error);
            this.notificar('error', error.response.data.message || 'Ocorreu um erro ao solicitar um novo código.');
          })
          .finally(() => {
            this.loader = false;
          });
      },

      iniciarContagemRegressiva() {
        this.limparContagemRegressiva();

        this.agora = new Date();
        
        this.countdownTimer = setInterval(() => {
          const agoraMesmo = new Date();
          const diffMs = agoraMesmo - this.agora;
          const segundosRestantes = Math.max(0, 60 - Math.floor(diffMs / 1000));

          if (segundosRestantes > 0) {
            this.tituloBotaoSolicitacao = `Aguarde ${segundosRestantes}s para solicitar um novo código`;
          } else {
            this.limparContagemRegressiva();
          }
        }, 1000);
      },

      limparContagemRegressiva() {
        if (this.countdownTimer) {
          clearInterval(this.countdownTimer); 
          this.countdownTimer = null;
          this.agora = null;
          this.tituloBotaoSolicitacao = 'Solicitar novo código';
        }
      },

      notificar: function (tipo, mensagem) {
        this.mensagemSucesso = null;
        this.mensagemErro = null;
        this.erro = false;
        this.sucesso = false;
        if (this.notificationTimeout) {
          clearTimeout(this.notificationTimeout);
        }

        if (tipo === 'success') {
          this.mensagemSucesso = mensagem;
          this.sucesso = true;
        } else if (tipo === 'error') {
          this.mensagemErro = mensagem;
          this.erro = true;
        }

        this.notificationTimeout = setTimeout(() => {
          this.mensagemSucesso = null;
          this.mensagemErro = null;
          this.notificationTimeout = null;
        }, 3000);
      }
    },
    beforeUnmount() {
      this.limparContagemRegressiva();
    },
}
</script>