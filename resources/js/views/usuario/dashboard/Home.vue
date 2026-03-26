<template>
  <app-intranet icone="mdi-account-multiple-outline" :exibir-filtros="false" :exibir-cadastro="false" :exibir-titulo="true">
    <template v-slot:resultados>
      Bem vindo, {{ $user?.name ?? 'Usuário' }}
    </template>
  </app-intranet>
</template>

<script>

import AppIntranet from '../../../components/AppLayout.vue';
export default {
  components: {
    AppIntranet,
  }
};
</script>
