<template>
  <header class="top-0 left-0 absolute bg-white z-30 w-full shadow-md">
    <div class="px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
        <!-- Header: Left side -->
        <div class="flex">

          <!-- Hamburger button -->
          <button class="text-gray-500 hover:text-gray-600 hover:bg-gray-200 dark:hover:text-gray-400" @click="$emit('update:modelValue', !modelValue)" aria-controls="sidebar" :aria-expanded="modelValue">
            <span class="sr-only">Open sidebar</span>
            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <rect x="4" y="5" width="16" height="2" />
              <rect x="4" y="11" width="16" height="2" />
              <rect x="4" y="17" width="16" height="2" />
            </svg>
          </button>

        </div>

        <!-- Header: Right side -->
        <div class="flex items-center space-x-3">
          <ThemeToggle />
          <!-- Divider -->
          <hr class="w-px h-6 bg-gray-200 dark:bg-gray-700/60 border-none" />
          <UserMenu align="right" />
        </div>

      </div>
  </header>
</template>

<script>
import ThemeToggle from './mosaic/base/ThemeToggle.vue'
import UserMenu from './mosaic/base/DropdownProfile.vue'

export default {
  name: 'Header',
  data() {
    return {
    };
  },
  props: {
    modelValue: {
      default: true
    }
  },
  components: {
    ThemeToggle,
    UserMenu,
  }
}
</script>