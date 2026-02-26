<template>
  <div v-bind="$attrs" v-show="modelValue" class="fixed inset-0 z-50 flex items-center justify-center p-4" aria-hidden="true">
    
    <div class="absolute inset-0 bg-black bg-opacity-30"></div>

    <div role="alert" class="relative z-10">
      <div class="inline-flex min-w-[320px] max-w-sm px-4 py-4 rounded-lg text-sm text-white bg-purple-700 shadow-2xl">
        <div class="flex w-full justify-between items-start">
          <div class="flex">
            <div class="font-medium">
              <slot /> </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
    }
  },
  props: {
    modelValue: {
      default: false
    }
  },
  emits: ['update:modelValue'],
  watch: {
    modelValue: function (val, oldVal) {
      if (val) {
        setTimeout(() => {
          this.$emit('update:modelValue', false);
        }, 3000) // Desaparece após 3 segundos
      }
    }
  }
};
</script>