<template>
  <div class="relative inline-flex">
    <overlay v-show="loader"></overlay>
    <button
      ref="trigger"
      class="inline-flex justify-center items-center group"
      aria-haspopup="true"
      @click.prevent="dropdownOpen = !dropdownOpen"
      :aria-expanded="dropdownOpen"
    >
      <div class="flex items-center truncate text-branco-100 p-2 dark:text-branco-100 group-hover:bg-laranja-300 dark:group-hover:bg-branco-600/50 rounded-full">
        <span class="truncate ml-2 text-sm font-medium">{{perfil.nome}}</span>
        <svg class="w-3 h-3 shrink-0 ml-1 fill-current" viewBox="0 0 12 12">
          <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
        </svg>
      </div>
    </button>
    <transition
      enter-active-class="transition ease-out duration-200 transform"
      enter-from-class="opacity-0 -translate-y-2"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition ease-out duration-200"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div v-show="dropdownOpen" class="origin-top-right z-10 absolute top-full min-w-44 bg-white dark:bg-stone-800 border border-gray-200 dark:border-gray-700/60 py-1.5 rounded-lg shadow-lg overflow-hidden mt-1" :class="align === 'right' ? 'right-0' : 'left-0'">
        <div class="pt-0.5 pb-2 px-3 mb-1 border-b border-gray-200 dark:border-gray-700/60 text-azul-500 dark:text-branco-100 hover:text-azul-600 dark:hover:text-branco-600">
          <div class="font-medium">{{perfil.nome}}</div>
          <div class="text-xs italic">{{perfil.email}}</div>
        </div>
        <ul
          ref="dropdown"
          @focusin="dropdownOpen = true"
          @focusout="dropdownOpen = false"
        >
          <li style="cursor: pointer"  @click="logout()">
            <span class="font-medium text-sm text-violet-500 hover:text-violet-600 dark:hover:text-violet-400 flex items-center py-1 px-3">Sair</span>
          </li>
        </ul>
      </div> 
    </transition>
  </div>
</template>

<script>
import axios from 'axios';
import { ref, onMounted, onUnmounted } from 'vue'
import Overlay from '../Overlay.vue';

export default {
  name: 'DropdownProfile',
  props: ['align'],
  data() {
    return {
      perfil: {},
      loader: false
    }
  },
  methods: {
    logout: function () {
      this.loader = true;
      axios.post('/logout', {}).then((response) => {
        window.location = '/';
      })
    },
  },
  mounted() {
  },
  components: {
    Overlay
  },
  setup() {

    const dropdownOpen = ref(false)
    const trigger = ref(null)
    const dropdown = ref(null)

    // close on click outside
    const clickHandler = ({ target }) => {
      if (!dropdownOpen.value || dropdown.value.contains(target) || trigger.value.contains(target)) return
      dropdownOpen.value = false
    }

    // close if the esc key is pressed
    const keyHandler = ({ keyCode }) => {
      if (!dropdownOpen.value || keyCode !== 27) return
      dropdownOpen.value = false
    }

    onMounted(() => {
      document.addEventListener('click', clickHandler)
      document.addEventListener('keydown', keyHandler)
    })

    onUnmounted(() => {
      document.removeEventListener('click', clickHandler)
      document.removeEventListener('keydown', keyHandler)
    })

    return {
      dropdownOpen,
      trigger,
      dropdown,
    }
  }
}
</script>