<template>
  <li class="pb-3 mr-6 last:mr-0 first:pl-4 sm:first:pl-6 lg:first:pl-8 last:pr-4 sm:last:pr-6 lg:last:pr-8" @click="$emit('click')">
    <span style="cursor: pointer" :class="[{'text-violet-500 whitespace-nowrap': selecionado,
                  'text-gray-500 dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 whitespace-nowrap': !selecionado
                }]">
      <slot />
    </span>
  </li>
</template>

<script>

export default {
  data() {
    return {
    };
  },
  props: {
    selecionado: {
      default: false,
    }
  },
  emits: ['click']
};
</script>