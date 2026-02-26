<template>
  <i style="font-size: 20px" :class="buttonClasses"></i>
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
