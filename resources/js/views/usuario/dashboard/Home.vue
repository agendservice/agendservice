<template>
  <app-intranet icone="mdi-account-multiple-outline" :exibir-filtros="false" :exibir-cadastro="false" :exibir-titulo="true">
    <template v-slot:resultados>
      Bem vindo, {{ $user?.name ?? 'Usuário' }}
    </template>
  </app-intranet>
</template>

<script>

import AppIntranet from '../../../components/AppLayout.vue';
import ResumoSistema from '../../../components/ResumoSistema.vue';
import Tabela from '../../../components/mosaic/Tabela.vue';
import CardMeta from '../../../components/mosaic/CardMeta.vue';
import Botao from '../../../components/mosaic/Botao.vue';
export default {
  data() {
    return {
      loader: false,
    };
  },
  components: {
    AppIntranet,
    ResumoSistema,
    Tabela,
    CardMeta,
    Botao,
  },
  mounted() {
    
  },
  methods: {
    
    
  }
};
</script>
