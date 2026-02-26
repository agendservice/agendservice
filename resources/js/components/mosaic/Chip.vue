<template>
  <div :class="['inline-flex font-medium rounded-full text-center px-2.5 py-0.5', cor, tamanho]">
    {{modelValue}}
  </div>
</template>

<script>
export default {
  props: {
    modelValue: {
      default: null,
    },
    tamanho: {
      type: String,
      default: 'text-sm'
    },
    cor: {
      type: String,
      default: 'gray'
    }
  }
};
</script>
