<template>
  <div :class="['bg-white dark:bg-gray-800 shadow-sm rounded-xl px-3 py-3', classes]">
    <slot/>
  </div>
</template>

<script>
export default {
  props: {
    classes: {
      default: ''
    }
  }
};
</script>
