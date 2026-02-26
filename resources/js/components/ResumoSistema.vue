<template>
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg grid grid-cols-6">
      <div class="flex flex-wrap justify-between items-center cols-12 p-1 mb-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">
          Resumo do Sistema
        </h2>
        <!-- <botao color="outlined" text="Ver relatório completo" /> -->
      </div>

      <div class="cols-12">
          <div v-if="!loader" class="grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 gap-4 ">
          <card-estatistica
              v-for="(card, index) in estatisticas"
              :key="index"
              :icone="card.icone"
              :titulo="card.titulo"
              :valor="card.valor"
              :cor="card.cor"
              :link="card.link"
          />
          </div>
      </div>
      <div v-if="loader" class="text-center p-8 text-gray-500">
        Carregando dados...
      </div>

      <div class="cols-12 flex flex-wrap gap-4 justify-center sm:justify-start border-t border-gray-200 dark:border-gray-700 pt-6">
        <botao 
          color="primary" 
          text="Aprovar cadastros" 
          prepend-icon="mdi-check-decagram" 
          @click="$router.push('/analisedocumentos')"
        />
        <botao 
          color="primary" 
          text="Ver relatórios de bancos" 
          prepend-icon="mdi-bank" 
          @click="$router.push('/relatorios')"
        />
        <botao 
          color="primary" 
          text="Gerenciar metas" 
          prepend-icon="mdi-bullseye-arrow" 
          @click="$router.push('/metas')"
        />
      </div>
    </div>
</template>
  

<script>
import axios from 'axios';
import CardEstatistica from './mosaic/CardEstatistica.vue';
import Botao from './mosaic/Botao.vue';

export default {
  name: 'ResumoSistema',
  components: {
    CardEstatistica,
    Botao,
  },
  data() {
    return {
      loader: false,
      estatisticas: [
        { icone: 'mdi-account-group', titulo: 'Total de Usuários', valor: 1, cor: 'blue', link: '/usuarios' },
        { icone: 'mdi-file-document-check', titulo: 'Análises Pendentes', valor: 1, cor: 'red', link: '/analisedocumentos' },
        { icone: 'mdi-bullseye-arrow', titulo: 'Metas Ativas', valor: 1, cor: 'violet', link: '/metas' },
        { icone: 'mdi-check-all', titulo: 'Total de Cotas Vendidas', valor: 1, cor: 'teal', link: '/cotas' },
        { icone: 'mdi-cash-multiple', titulo: 'Comissões Pagas', valor: 1, cor: 'green', link: '/contabilidade' },
        { icone: 'mdi-cash-minus', titulo: 'Comissões Pendentes', valor: 1, cor: 'amber', link: '/contabilidade' },
      ],
    };
  },
  mounted() {
    this.carregarResumo();
  },
  methods: {
    carregarResumo() {
      this.loader = true;
      axios.get('/dashboard/buscar')
        .then(({ data }) => {
          this.estatisticas[0].valor = data.total_usuarios;
          this.estatisticas[1].valor = data.analises_pendentes;
          this.estatisticas[2].valor = data.metas_ativas;
          this.estatisticas[3].valor = data.cotas_vendidas_total;
          this.estatisticas[4].valor = data.comissoes_pagas_total;
          this.estatisticas[5].valor = data.comissoes_pendentes_total;
        })
        .catch(error => {
          console.error("Erro ao carregar resumo do sistema:", error);
          // Colocar 'N/D' em caso de erro
          this.estatisticas.forEach(stat => stat.valor = 'N/D');
        })
        .finally(() => {
          this.loader = false;
        });
    }
  },
};
</script>