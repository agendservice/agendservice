<template>
  <component
    :is="link ? 'a' : 'div'"
    :href="link"
    class="flex items-center p-4 bg-white dark:bg-gray-700 rounded-xl shadow-sm border-solid border-2 border-slat-400 dark:border-slat-400"
    :rel="link ? 'noopener noreferrer' : null"
    style="text-decoration: none;"
  >
    <div 
      class="flex-shrink-0 mr-4 h-12 w-12 flex items-center justify-center rounded-full text-white"
      :class="corDeFundoIcone"
    >
      <icone :icone="icone" class="text-2xl" />
    </div>
    
    <div>
      <div class="text-md font-medium text-gray-500 dark:text-gray-100 truncate">
        {{ titulo }}
      </div>
      <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">
        {{ valor }}
      </div>
    </div>
  </component>
</template>

<script>
import Icone from './Icone.vue';

export default {
  name: 'CardEstatistica',
  components: {
    Icone
  },
  props: {
    icone: {
      type: String,
      required: true,
    },
    titulo: {
      type: String,
      required: true,
    },
    valor: {
      type: [String, Number],
      required: true,
    },
    cor: { 
      type: String,
      default: 'blue', 
    },
    link: {
      type: String,
      default: null, 
    }
  },
  computed: {
    corDeFundoIcone() {
      const cores = {
        blue: 'bg-blue-500',
        purple: 'bg-purple-500',
        green: 'bg-green-500',
        amber: 'bg-amber-500',
        orange: 'bg-orange-500',
        lime: 'bg-lime-500',
        emerald: 'bg-emerald-500',
      };
      return cores[this.cor] || cores['blue'];
    }
  }
};
</script>