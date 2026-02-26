<template>
  <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <slot/>
  </div>
</template>

<script>
export default {
  props: {
    icone: {
      type: String,
      default: null,
    }
  },
  computed: {
    buttonClasses() {
      return [
        'mdi',
        this.icone
      ];
    }
  }
};
</script>
