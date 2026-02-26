<template>
  <div>
    <label class="block text-sm font-medium mb-1" :for="alt">{{label}} <span class="text-red-500" v-show="required">*</span></label>
    <div class="relative" v-if="!money && !percent && !icone && !loader">
      <textarea :rows="rows" v-bind="$attrs" :autocomplete="alt" :class="inputClasses" :type="type" :required="required" :disabled="disabled" v-model="valorSelecionado" @input="alterado()" />
    </div>
    <div class="relative" v-if="money && !percent && !icone && !loader">
      <textarea v-bind="$attrs" :rows="rows" :autocomplete="alt" :class="inputClasses" type="number" :required="required" :disabled="disabled" v-model="valorSelecionado" @input="alterado()" />
      <div class="absolute inset-0 right-auto flex items-center pointer-events-none">
        <span class="text-sm text-gray-400 dark:text-gray-500 font-medium px-2">R$</span>
      </div>
    </div>
    <div class="relative" v-if="percent && !money && !icone && !loader">
      <textarea v-bind="$attrs" :rows="rows" :autocomplete="alt" :class="inputClasses" type="number" :required="required" :disabled="disabled" v-model="valorSelecionado" @input="alterado()" />
      <div class="absolute inset-0 left-auto flex items-center pointer-events-none">
        <span class="text-sm text-gray-400 dark:text-gray-500 font-medium px-3">%</span>
      </div>
    </div>
    <div class="relative" v-if="(!money && !percent && icone) || loader">
      <textarea :rows="rows" v-bind="$attrs" :autocomplete="alt" :class="inputClasses" :type="type" :required="required" :disabled="disabled" v-model="valorSelecionado" @input="alterado()" />
      <div class="absolute inset-0 right-auto flex items-center pointer-events-none">
        <span class="text text-gray-400 dark:text-gray-500 font-medium px-3">
          <svg class="animate-spin fill-current shrink-0" width="16" height="16" viewBox="0 0 16 16" v-show="loader">
            <path d="M8 16a7.928 7.928 0 01-3.428-.77l.857-1.807A6.006 6.006 0 0014 8c0-3.309-2.691-6-6-6a6.006 6.006 0 00-5.422 8.572l-1.806.859A7.929 7.929 0 010 8c0-4.411 3.589-8 8-8s8 3.589 8 8-3.589 8-8 8z" />
          </svg>
          <icone :icone="icone" v-show="!loader"></icone>
        </span>
      </div>
    </div>
    <div class="text-xs mt-1 text-red-500" v-show="erro">{{erro}}</div>
  </div>
</template>

<script>
import Icone from './Icone.vue';
export default {
  data() {
    return {
      valorSelecionado: null,
      timeout: null
    };
  },
  props: {
    rows: {
      default: 2
    },
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
      default: 'default'
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
    upper: {
      type: Boolean,
      default: false,
    },
    money: {
      type: Boolean,
      default: false,
    },
    percent: {
      type: Boolean,
      default: false,
    },
    icone: {
      type: String,
      default: null
    },
    modelValue: {
      default: null
    }
  },
  components: {
    Icone
  },
  mounted () {
    this.valorSelecionado = this.modelValue;
  },
  watch: {
    modelValue(newValue) {
      if (this.upper) {
        this.valorSelecionado = newValue.toUpperCase();
      } else {
        this.valorSelecionado = newValue;
      }
    },
  },
  methods: {
    alterado: function () {
      clearTimeout(this.timeout);
      this.timeout = setTimeout(() => {
        if (this.upper) {
          this.$emit('update:modelValue', this.valorSelecionado.toUpperCase());
        } else {
          this.$emit('update:modelValue', this.valorSelecionado);
        }
      }, 500)
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
        'form-input w-full dark:bg-azul-700 dark:border-gray-700 dark:text-gray-300',
        this.computedSize,
        {'border-red-300': this.erro},
        {'pl-8': this.money},
        {'pr-8': this.percent},
        {'pl-10': this.icone},
        {'dark:disabled:placeholder:text-gray-600 disabled:border-gray-200 dark:disabled:border-gray-700 disabled:bg-gray-100 dark:disabled:bg-azul-550 disabled:text-gray-400 dark:disabled:text-gray-600 disabled:cursor-not-allowed shadow-none': this.disabled },
      ];
    }
  }
};
</script>
