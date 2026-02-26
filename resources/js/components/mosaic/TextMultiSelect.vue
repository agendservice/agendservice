<template>
  <div>
    <label class="block text-sm font-medium mb-1">
      {{ label }} <span class="text-red-500" v-show="required">*</span>
    </label>

    <div class="relative">
      <!-- Área de seleção -->
      <div class="form-select w-full flex flex-wrap items-center border rounded-lg px-3 py-1" @click="toggleDropdown">
        <span v-if="!selectedOptions.length" class="text-gray-400">Selecione...</span>

        <!-- Exibir opções selecionadas -->
        <span v-for="(option, index) in selectedOptions" :key="option.value" class="bg-blue-100 text-blue-600 text-sm px-3 py-0 rounded-full mr-2 mb-1 flex items-center">
          {{ option.label }}
          <button @click.stop="removeOption(index)" class="ml-2 text-red-500 hover:text-red-700">
            <icone icone="mdi-close"></icone>
          </button>
        </span>
      </div>

      <!-- Dropdown de opções -->
      <div v-if="showDropdown" class="absolute w-full bg-white border rounded-lg mt-1 shadow-md z-10">
        <div v-for="option in options" :key="option.value" class="p-2 cursor-pointer hover:bg-gray-100 flex items-center">
          <input type="checkbox" :value="option.value" v-model="selectedValues" class="mr-2">
          <span>{{ option.label }}</span>
        </div>
      </div>
    </div>

    <div class="text-xs mt-1 text-red-500" v-show="erro">{{ erro }}</div>
  </div>
</template>

<script>
import Icone from './Icone.vue';

export default {
  props: {
    label: {
      type: String,
      required: true
    },
    options: {
      type: Array,
      required: true
    },
    required: {
      type: Boolean,
      default: false
    },
    erro: {
      type: String,
      default: null
    },
    modelValue: {
      type: Array,
      default: () => []
    }
  },
  components: {
    Icone
  },
  data() {
    return {
      showDropdown: false,
      selectedValues: []
    };
  },
  computed: {
    selectedOptions() {
      return this.options.filter(option => this.selectedValues.includes(option.value));
    }
  },
  mounted() {
    document.addEventListener("click", this.handleClickOutside);
  },
  beforeUnmount() {
    document.removeEventListener("click", this.handleClickOutside);
  },
  watch: {
    modelValue(newValue) {
      this.selectedValues = newValue;
    },
    selectedValues(newValues) {
      this.$emit("update:modelValue", newValues);
    }
  },
  methods: {
    toggleDropdown() {
      this.showDropdown = !this.showDropdown;
    },
    removeOption(index) {
      this.selectedValues.splice(index, 1);
    },
    handleClickOutside(event) {
      if (!this.$el.contains(event.target)) {
        this.showDropdown = false;
      }
    }
  }
};
</script>

<style scoped>
.form-select {
  cursor: pointer;
  min-height: 40px;
}
</style>
