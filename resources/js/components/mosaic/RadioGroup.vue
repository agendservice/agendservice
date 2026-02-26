<template>
  <div class="py-2">
    <label class="text-md block mb-1 text-gray-700 dark:text-white" for="id" v-if="label">{{ label }} <span class="text-red-500" v-show="required">*</span></label>
    
    <div class="flex flex-wrap gap-4">
      <div 
        v-for="option in options" 
        :key="option.value" 
        class="relative flex items-center"
      >
        <input 
          type="radio" 
          :class="inputClasses" 
          :required="required" 
          :disabled="disabled || option.disabled"
          :value="option.value"
          v-model="valorSelecionado" 
          :id="alt + '-' + option.value"
          :name="alt" />
        <label class="text-md ml-2 cursor-pointer text-gray-700 dark:text-white" :for="alt + '-' + option.value">{{ option.label }}</label>
      </div>
    </div>

    <div class="text-xs mt-1 text-red-500" v-show="erro">{{ erro }}</div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      valorSelecionado: null
    };
  },
  props: {
    // Lista de opções que o componente irá iterar
    options: {
      type: Array,
      required: true,
      // Espera um array de objetos como: [{ label: 'Opção 1', value: 'valor1' }, ...]
      validator: (options) => options.every(o => 'label' in o && 'value' in o),
    },
    label: {
      type: String,
      default: null, // Mudado para default null, pois o label principal é opcional
    },
    // O 'alt' será usado como o atributo 'name' para agrupar os rádios
    alt: {
      type: String,
      default: 'radio-group',
    },
    required: {
      type: Boolean,
      default: false,
    },
    // Outras props como 'size', 'erro', 'disabled'
    size: {
      type: String,
      default: null,
    },
    erro: {
      type: String,
      default: null,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    // Valor inicial/selecionado do grupo
    modelValue: {
      default: null
    }
  },
  emits: ['update:modelValue'], // Declarando o evento
  mounted() {
    // Define o valor inicial (se houver)
    this.valorSelecionado = this.modelValue;
  },
  watch: {
    // Atualiza o valor interno quando modelValue externo muda
    modelValue(newValue) {
      this.valorSelecionado = newValue;
    },
    // Emite o evento 'update:modelValue' quando a seleção interna muda
    valorSelecionado(newValue) {
      this.$emit('update:modelValue', newValue);
    }
  },
  computed: {
    computedSize() {
      if (this.size == 'large') {
        return 'w-6 h-6'; // Aumenta o tamanho do rádio
      }
      if (this.size == 'small') {
        return 'w-3 h-3'; // Diminui o tamanho do rádio
      }

      return 'w-4 h-4'; // Tamanho padrão
    },
    inputClasses() {
      // Classes para estilizar o radio button (usando form-radio do Tailwind)
      return [
        'form-radio',
        this.computedSize,
        {'border-red-300': this.erro},
        // Estilos para desabilitado
        {'dark:disabled:placeholder:text-gray-600 disabled:border-gray-200 dark:disabled:border-gray-700 disabled:bg-gray-100 dark:disabled:bg-gray-800 disabled:text-gray-400 dark:disabled:text-gray-600 disabled:cursor-not-allowed shadow-none': this.disabled },

        {'dark:bg-branco-300 dark:checked:bg-laranja-500 hover:bg-azul-500 focus:bg-laranja-500 checked:bg-laranja-500 checked:hover:bg-laranja-500 checked:focus:bg-laranja-500': this.valorSelecionado},
      ];
    }
  }
};
</script>