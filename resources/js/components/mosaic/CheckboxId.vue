<template>
  <div class="py-2">
    <div class="relative">
      <input 
        type="checkbox" 
        :class="inputClasses" 
        :required="required" 
        :disabled="disabled" 
        :checked="isChecked"
        @change="handleChange"
        :id="alt"
      />
      <label class="text-md ml-2 text-gray-700 dark:text-white" :for="alt">
        {{label}} 
        <span class="text-red-500" v-show="required">*</span>
      </label>
    </div>
    <div class="text-xs mt-1 text-red-500" v-show="erro">{{erro}}</div>
  </div>
</template>

<script>
import Icone from './Icone.vue';
export default {
  data() {
    return {
      isChecked: false
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
    itemId: {
      type: [String, Number],
      required: true
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
      type: Array,
      default: () => []
    }
  },
  components: {
    Icone
  },
  mounted() {
    this.initCheckboxState();
  },
  watch: {
    modelValue(newValue) {
      this.initCheckboxState();
    }
  },
  methods: {
    initCheckboxState() {
      if (Array.isArray(this.modelValue)) {
        this.isChecked = this.modelValue.includes(parseInt(this.itemId));
      } else {
        this.isChecked = this.modelValue === this.itemId;
      }
    },
    handleChange(event) {
      if (Array.isArray(this.modelValue)) {
        // Se modelValue é um array, adiciona ou remove o id
        const newValue = [...this.modelValue];
        if (event.target.checked) {
          newValue.push(this.itemId);
        } else {
          const index = newValue.findIndex(item => String(item) === String(this.itemId));
          if (index > -1) {
            newValue.splice(index, 1);
          }
        }
        this.$emit('update:modelValue', newValue);
      } else {
        // Se modelValue é um valor único
        this.$emit('update:modelValue', event.target.checked ? this.itemId : null);
      }
    }
  },
  computed: {
    computedSize() {
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
        { 'border-red-300': this.erro },
        { 'dark:disabled:placeholder:text-gray-600 disabled:border-gray-200 dark:disabled:border-gray-700 disabled:bg-gray-100 dark:disabled:bg-gray-800 disabled:text-gray-400 dark:disabled:text-gray-600 disabled:cursor-not-allowed shadow-none': this.disabled },
        { 'bg-laranja-600 hover:bg-laranja-500 focus:bg-laranja-500 dark:bg-laranja-500 dark:checked:hover:bg-laranja-400 checked:bg-laranja-500 checked:hover:bg-azul-500 checked:focus:bg-laranja-500': this.isChecked },
      ];
    }
  }
};
</script>
