<template>
  <div v-bind="$attrs">
    <text-input :label="label"
      :loader="loader" icone="mdi-magnify" 
      :type="type" autocomplete="off" :required="required" 
      :disabled="disabled" v-model="valorSelecionado"
      @focus="exibirOpcoes = true" @input="buscar()"
    ></text-input>
    <div style="position: absolute; z-index: 2; border-radius: 10px; max-height: 200px; overflow: auto" class="px-2 py-2 bg-red-50 dark:bg-red-700" v-show="exibirOpcoes">
      <div class="mb-3 last:mb-0">
        <div @click="fechar()" style="cursor: pointer" class="text-xs font-semibold text-gray-400 dark:text-gray-300 uppercase px-2 mb-2">Cancelar</div>
        <ul class="text-sm">
          <li v-show="options.length <= 0">
            <span class="flex items-center p-2 text-gray-800 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700/20 rounded-lg">
              Nenhum resultado encontrado
            </span>
          </li>
          <li v-for="(option, iOption) in options" :key="iOption" @click="alterado(option)" style="cursor: pointer">
            <span class="flex items-center p-2 text-gray-800 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700/20 rounded-lg" v-html="marcarBusca(valorSelecionado, option.label)"></span>
          </li>
        </ul>
      </div>
    </div>
    <div class="text-xs mt-1 text-red-500" v-show="erro">{{erro}}</div>
  </div>
</template>

<script>
import TextInput from './TextInput.vue';
export default {
  data() {
    return {
      valorSelecionado: null,
      timeout: null,
      exibirOpcoes: false
    };
  },
  props: {
    type: {
      type: String,
      default: 'text',
    },
    label: {
      type: String,
      required: true,
    },
    required: {
      type: Boolean,
      default: false,
    },
    loader: {
      type: Boolean,
      default: false,
    },
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
    modelValue: {
      default: null
    },
    options: {
      required: true,
      default: []
    }
  },
  components: {
    TextInput
  },
  mounted () {
    this.valorSelecionado = this.modelValue;
  },
  emits: ['buscar', 'limpar', 'update:modelValue'],
  methods: {
    marcarBusca: function (busca, resultado) {
      try {
        let index = resultado.toUpperCase().indexOf(busca.toUpperCase());
        if (index >= 0) {
          let size = busca.length;
    
          let ini = "";
          if (index > 0) {
            ini = resultado.substr(0, index);
          }
          let found = resultado.substr(index, size);
          let end = resultado.substr(index + size);
    
          return ini+'<strong>'+found+'</strong>'+end;
        }
        return resultado;
      } catch (error) {
        return resultado;
      }
    },
    buscar: function () {
      clearTimeout(this.timeout);
      this.timeout = setTimeout(() => {
        this.$emit('buscar', this.valorSelecionado);
      }, 500);
    },
    alterado: function (opcao) {
      this.$emit('update:modelValue', opcao.value);
      this.valorSelecionado = opcao.label;
      this.exibirOpcoes = false;
    },
    fechar: function () {
      this.exibirOpcoes = false;
      this.valorSelecionado = null;
      this.$emit('limpar');
      this.$emit('update:modelValue', null);
    }
  },
  computed: {
    computedSize () {
      if (this.size == 'large') {
        return 'px-4 py-3';
      }
      if (this.size == 'small') {
        return 'px-2 py-1';
      }

      return '';
    },
    inputClasses() {
      return [
        'form-input w-full pl-10',
        this.computedSize,
        {'border-red-300': this.erro},
        {'dark:disabled:placeholder:text-gray-600 disabled:border-gray-200 dark:disabled:border-gray-700 disabled:bg-gray-100 dark:disabled:bg-gray-800 disabled:text-gray-400 dark:disabled:text-gray-600 disabled:cursor-not-allowed shadow-none': this.disabled }
      ];
    }
  }
};
</script>
