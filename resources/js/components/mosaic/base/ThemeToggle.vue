<template>
  <div>
    <input type="checkbox" name="light-switch" id="light-switch" v-model="isDark" class="light-switch sr-only" />
    <label
      class="flex items-center justify-center text-branco-100 cursor-pointer p-2 hover:bg-laranja-300 dark:hover:bg-branco-600/50 rounded-full"
      for="light-switch">
      <icone class="dark:hidden" icone="mdi-weather-sunny"></icone>
      <icone class="hidden dark:block" icone="mdi-weather-night"></icone>
    </label>
  </div>
</template>

<script setup>
import { useDark } from "@vueuse/core";
import Icone from "../Icone.vue";

const isDark = useDark({
  selector: 'html',
})
</script>
