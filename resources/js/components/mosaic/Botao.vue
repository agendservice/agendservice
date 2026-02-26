<template>
  <button 
    :class="buttonClasses" 
    :disabled="disabled"
  >
    <icone v-show="prependIcon" class="mr-2" :icone="prependIcon"></icone>
    <icone v-show="icone" :icone="icone"></icone>
    {{ text }}
    <icone v-show="appendIcon" class="ml-2" :icone="appendIcon"></icone>
  </button>
</template>

<script>
import Icone from './Icone.vue';
export default {
  props: {
    size: {
      type: String,
      default: null,
    },
    color: {
      type: [String, Object],
      default: 'bg-azul-500 dark:bg-laranja-500 text-white border-laranja-500 rounded-lg',
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    text: {
      type: String,
      default: null
    },
    prependIcon: {
      type: String,
      default: null
    },
    icone: {
      type: String,
      default: null
    },
    appendIcon: {
      type: String,
      default: null
    }
  },
  components: {
    Icone
  },
  computed: {
    computedSize () {
      if (this.size == 'x-small') {
        return 'px-2 py-1 text-xs';
      }
      
      if (this.size == 'small') {
        return 'px-3 py-1.5 text-sm';
      }

      if (this.size == 'large') {
        return 'px-6 py-3 text-lg';
      }

      return 'px-4 py-2 text-base';
    },
    computedColor() {
      // Mapeia cores predefinidas ou usa cores do Tailwind diretamente
      const colorMap = {
        'primary': 'bg-blue-600 hover:bg-blue-700 text-white',
        'secondary': 'bg-gray-600 hover:bg-gray-700 text-white',
        'success': 'bg-green-600 hover:bg-green-700 text-white',
        'danger': 'bg-red-600 hover:bg-red-700 text-white',
        'warning': 'bg-yellow-500 hover:bg-yellow-600 text-white',
        'info': 'bg-cyan-600 hover:bg-cyan-700 text-white',
        'light': 'bg-gray-200 hover:bg-gray-300 text-gray-800',
        'dark': 'bg-gray-800 hover:bg-gray-900 text-white',
      };

      // Se a cor estiver no mapa, usa ela
      if (colorMap[this.color]) {
        return colorMap[this.color];
      }

      // Caso contrário, assume que é uma classe do Tailwind e retorna diretamente
      return this.color;
    },
    buttonClasses() {
      return [
        'font-medium transition-colors duration-200 inline-flex items-center justify-center',
        this.computedSize,
        this.computedColor,
        { 'opacity-50 cursor-not-allowed': this.disabled }
      ];
    }
  }
};
</script>
