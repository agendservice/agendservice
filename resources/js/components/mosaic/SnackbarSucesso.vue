<template>
  <div v-bind="$attrs" v-show="modelValue" class="top-5 right-5 fixed z-50 transition-opacity flex justify-end" aria-hidden="true">
    <div role="alert" class="mt-7 mr-5 fill-current shrink-0 relative z-10">
      <div class="inline-flex min-w-80 px-4 py-4 rounded-lg text-sm text-white bg-green-500">
        <div class="flex w-full justify-between items-start">
          <div class="flex">
            <svg class="shrink-0 fill-current opacity-80 mt-[3px] mr-3" width="16" height="16" viewBox="0 0 16 16">
              <path d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zM7 11.4L3.6 8 5 6.6l2 2 4-4L12.4 6 7 11.4z" />
            </svg>
            <div class="font-medium">
              <slot />
            </div>
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
        }, 3000)
      }
    }
  }
};
</script>
