<template>
  <div class="relative">
    <div v-for="(urlImagem, iImagem) in modelValue" :key="iImagem">
      <img :src="urlImagem"></img>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    modelValue: {
      default: null,
    }
  }
};
</script>
