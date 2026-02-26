<template>
  <div class="py-2">
    <div class="relative">
      <input 
        type="checkbox" 
        :class="inputClasses" 
        :required="required" 
        :disabled="disabled" 
        v-model="valorSelecionado" 
        :id="alt"
      />
      <label class="text-md ml-2 text-gray-700 dark:text-white" :for="alt">{{label}} <span class="text-red-500" v-show="required">*</span></label>
    </div>
    <div class="text-xs mt-1 text-red-500" v-show="erro">{{erro}}</div>
  </div>
</template>

<script>
import { random } from 'lodash';
import Icone from './Icone.vue';
export default {
  data() {
    return {
      valorSelecionado: null
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
    alt: {
      type: String,
      default: 'default',
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
    checkedValue: {
      type: String,
      default: 'ativo',
    },
    uncheckedValue: {
      type: String,
      default: 'inativo',
    }
  },
  components: {
    Icone
  },
  mounted () {
    this.valorSelecionado = this.sToB(this.modelValue);
  },
  watch: {
    modelValue(newValue) {
      this.valorSelecionado = this.sToB(newValue);
    },
    valorSelecionado(newValue) {
      this.$emit('update:modelValue', this.bToS(this.valorSelecionado));
    }
  },
  methods: {
    sToB: function (valor) {
      if (valor === this.checkedValue) {
        return true;
      }
      return false;
    },
    bToS: function (valor) {
      if (valor === true) {
        return this.checkedValue;
      }
      return this.uncheckedValue;
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
        'form-checkbox',
        this.computedSize,
        {'border-red-300': this.erro},
        {'dark:disabled:placeholder:text-gray-600 disabled:border-gray-200 dark:disabled:border-gray-700 disabled:bg-gray-100 dark:disabled:bg-gray-800 disabled:text-gray-400 dark:disabled:text-gray-600 disabled:cursor-not-allowed shadow-none': this.disabled },
        {'bg-laranja-600 hover:bg-laranja-500 focus:bg-laranja-500 dark:bg-laranja-500 dark:checked:hover:bg-laranja-400 checked:bg-laranja-500 checked:hover:bg-azul-500 checked:focus:bg-laranja-500': this.valorSelecionado},
      ];
    }
  }
};
</script>
