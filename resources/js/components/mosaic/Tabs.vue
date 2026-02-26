<template>
  <div v-bind="$attrs">
    <div class="row">
      <div class="cols-12">
        <div class="mb-1 border-b border-gray-200 dark:border-gray-700/60">
          <ul class="text-sm w-full font-medium flex flex-nowrap -mx-4 sm:-mx-6 lg:-mx-8 overflow-x-scroll no-scrollbar">
            <tab v-for="(aba, iAba) in abas" :key="iAba" :selecionado="iAba == modelValue" @click="selecionarAba(iAba)">
              {{aba}}
            </tab>
          </ul>
        </div>
      </div>
      <div class="cols-12">
        <slot />
      </div>
    </div>
  </div>
</template>

<script>
import Tab from './Tab.vue';

export default {
  data() {
    return {
    };
  },
  props: {
    abas: {
      default: []
    },
    modelValue: {
      default: 0
    }
  },
  emits: ['update:modelValue'],
  components: {
    Tab
  },
  methods: {
    selecionarAba: function (index) {
      this.$emit('update:modelValue', index);
    }
  }
};
</script>