<template>
  <div class="flex flex-col col-span-full sm:col-span-6 xl:col-span-4 bg-white dark:bg-gray-800 shadow-sm rounded-xl">
    <header class="px-5 py-4">
      <h2 class="font-semibold text-gray-800 dark:text-gray-100">
        <icone v-show="icone" class="mr-1" :icone="icone"></icone>
        {{titulo}}
      </h2>
    </header>
    <div class="grow p-3">
      <div class="flex flex-col h-full">
        <!-- Card content -->
        <div class="grow">
          <ul class="flex justify-between text-xs uppercase text-gray-400 dark:text-gray-500 font-semibold px-2 space-x-2">
            <li>{{entidade}}</li>
            <li>Valor</li>
          </ul>

          <ul class="space-y-1 text-sm text-gray-800 dark:text-gray-100 mt-3 mb-4">
            <li class="relative px-2 py-1" v-show="valoresOrdenados.length <= 0">
              Nenhum registro encontrado
            </li>
            <!-- Item -->
            <li class="relative px-2 py-1" v-for="(valor, iValor) in valoresOrdenados" :key="iValor">
              <div class="absolute inset-0 bg-violet-100 dark:bg-violet-500/20 rounded-r" aria-hidden="true" :style="'width: '+valor.percentual+'%'"></div>
              <div class="relative flex justify-between space-x-2">
                <div>{{valor.name}}</div>
                <div class="font-medium">{{valor.y}}</div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Icone from './Icone.vue';

export default {
  name: 'Ranking',
  props: ['titulo', 'entidade', 'valores', 'icone'],
  components: {
    Icone
  },
  computed: {
    valoresOrdenados () {
      // Encontra o maior valor
      const maiorValor = Math.max(...this.valores.map(v => v.y));
      
      // Calcula os percentuais e ordena os valores
      return this.valores.map(valor => ({
        ...valor,
        percentual: (valor.y / maiorValor) * 100 // Calcula o percentual
      })).sort((a, b) => b.y - a.y); // Ordena pelo valor original
    }
  }
}
</script>