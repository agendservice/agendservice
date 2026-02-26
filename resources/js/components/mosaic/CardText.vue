<template>
  <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl px-3 py-3">
    <header class="pb-2 mb-2 border-b border-gray-100 dark:border-gray-700/60" v-show="titulo">
      <h2 class="font-semibold text-gray-800 dark:text-gray-100">
        <icone class="mr-1" :icone="icone" v-show="icone"></icone>
        {{titulo}}
      </h2>
    </header>
    <slot name="header"/>
    <div v-show="!!$slots.text" :class="{
      'px-1 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase mb-1':true,
      'pb-2 border-b border-gray-100 dark:border-gray-700/60 mb-2': !!this.$slots.actions
      }">
      <slot name="text"></slot>
    </div>
    <div v-show="!!$slots.body" :class="{
      'px-1 mb-1':true,
      'pb-2 border-b border-gray-100 dark:border-gray-700/60 mb-2': hasActions&&exibirAcoes
      }">
      <slot name="body"></slot>
    </div>
    <div v-show="!!$slots.actions && exibirAcoes" class="flex justify-end">
      <slot name="actions"></slot>
    </div>
  </div>
</template>

<script>
import Icone from './Icone.vue';

export default {
  props: {
    titulo: {
      type: String,
      default: null,
    },
    exibirAcoes: {
      default: true
    },
    icone: {
      default: null
    }
  },
  components: {
    Icone
  },
  computed: {
    hasActions() {
      return !!this.$slots.actions;
    },
  }
};
</script>
