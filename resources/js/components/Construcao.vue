<template>
  <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl relative">
    <overlay v-show="loader"></overlay>
    <header class="px-5 py-4">
      <h2 class="font-semibold text-gray-800 dark:text-gray-100">Em Construção</h2>
    </header>
    <div class="p-5">
      <p class="text-gray-600 dark:text-gray-400">Esta funcionalidade ainda está em desenvolvimento. Por favor, aguarde.</p>  
    </div>
  </div>
</template>

<script>
import Overlay from './mosaic/Overlay.vue'
import Icone from './mosaic/Icone.vue';

export default {
  components: {
    Icone,
    Overlay
  },
  data() {
    return {
      loader: false,
      sucesso: false,
      erro: false,
      mensagem: ''
    };
  },
  methods: {
  }
};
</script>