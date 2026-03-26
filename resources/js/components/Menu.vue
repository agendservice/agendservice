<template>
  <div class="min-w-fit h-screen">
    <!-- Sidebar -->
    <div
      id="sidebar"
      ref="sidebar"
      class="flex lg:!flex flex-col absolute z-40 left-0 lg:static lg:left-auto top-auto shadow-sm lg:translate-x-0 h-[100dvh] overflow-y-scroll lg:overflow-y-auto no-scrollbar w-64 2xl:!w-64 shrink-0 bg-branco-100 dark:bg-azul-600 p-4 transition-all duration-200 ease-in-out h-full"
      :class="[modelValue ? 'translate-x-0' : '-translate-x-64']"
    >

      <!-- Sidebar header -->
      <div class="flex justify-between pb-6 mb-2 border-b pr-3 sm:px-2">
        <!-- Close button -->
        <!-- Logo -->
        <div class="block flex items-top justify-top" style="justify-content: center">
          <img class="hidden dark:block rounded-full" src="/images/logo12.png" width="100%" alt="icone">
          <img class="dark:hidden rounded-full" src="/images/logo11.png" width="100%" alt="icone">
        </div>
      </div>

      <!-- Links -->
      <div class="space-y-8 overflow-x-auto">
        <ul class="pt-3">
          <SidebarLinkGroup v-for="(menu, iMenu) in menus" :key="iMenu" style="padding-top: 2px !important; padding-bottom: 2px !important" v-slot="parentLink">
            <div class="flex items-center justify-between">
              <a class="block pointer text-azul-700 dark:text-branco-100 truncate transition flex-grow p-2 rounded-md hover:bg-branco-500 dark:hover:bg-branco-600/50 border-azul-500 dark:border-branco-100" @click.prevent="redirecionar(menu.link, menu.external_link)">
                <div class="flex items-center">
                  <icone :icone="menu.icone"></icone>
                  <span class="text-sm font-semibold ml-4 lg:sidebar-expanded 2xl:opacity-100 duration-200">{{menu.titulo}}</span>
                </div>
              </a>

              <button 
                v-if="menu.items" 
                class="flex-shrink-0 ml-2 p-3 rounded-lg text-azul-700 dark:text-branco-100 hover:bg-branco-600 dark:hover:bg-branco-600/50" 
                @click.prevent="parentLink.handleClick()"
              >
                <span class="sr-only">Abrir submenu</span>
                <svg class="w-3 h-3 shrink-0 fill-current text-azul-500 dark:text-branco-600 transition-transform duration-200" :class="parentLink.expanded && 'rotate-180'" viewBox="0 0 12 12">
                  <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                </svg>
              </button>
            </div>

            <div v-show="menu.items" :class="[{'hidden': !parentLink.expanded}]">
              <div v-for="(menuItem, iMenuItem) in menu.items" 
                :key="iMenuItem+'_'+iMenu">
                <a class="block pointer text-azul-500 ml-6 font-semibold dark:text-branco-100 truncate transition text-sm my-1 p-1 hover:bg-branco-500 dark:hover:bg-branco-600/50 border-azul-500 dark:border-branco-100"
                  @click="$router.push(menuItem.link)"
                  v-show="!menuItem.submenus">
                  <icone :icone="menuItem.icone"></icone>
                  {{ menuItem.titulo }}
                </a>
                <div v-show="menuItem.submenus">
                  <div class="flex items-center pointer my-1" @click="menuItem.expanded?(menuItem.expanded = !menuItem.expanded):menuItem.expanded=true">
                    <div class="flex shrink-0">
                      <svg class="w-2 h-2 shrink-0 fill-current" :class="menuItem.expanded && 'rotate-180'" viewBox="0 0 12 12">
                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                      </svg>
                    </div>
                    <span class="text-sm ml-4 font-semibold text-azul-500 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">{{menuItem.titulo}}</span>
                  </div>
                  <ul :class="!menuItem.expanded && 'hidden'">
                    <li v-for="(submenuItem, iSubMenuItem) in menuItem.submenus" :key="iSubMenuItem+'_'+iMenuItem+'_'+iMenu" class="ml-8">
                      <a class="block pointer transition truncate text-azul-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200" @click="$router.push(submenuItem.link)">
                        <span class="text-sm font-medium lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">{{submenuItem.titulo}}</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </SidebarLinkGroup>
        </ul>
      </div>

      <!-- Expand / collapse button -->
      <div class="pt-3 hidden lg:inline-flex 2xl:hidden justify-end mt-auto">
        <div class="w-12 pl-4 pr-3 py-1">
          <button class="text-gray-400 hover:text-azul-500 dark:text-branco-600 dark:hover:text-gray-400" @click.prevent="modelValue = !modelValue">
            <span class="sr-only">Expand / collapse sidebar</span>
            <svg class="shrink-0 fill-current text-gray-400 dark:text-branco-600 sidebar-expanded:rotate-180" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                <path d="M15 16a1 1 0 0 1-1-1V1a1 1 0 1 1 2 0v14a1 1 0 0 1-1 1ZM8.586 7H1a1 1 0 1 0 0 2h7.586l-2.793 2.793a1 1 0 1 0 1.414 1.414l4.5-4.5A.997.997 0 0 0 12 8.01M11.924 7.617a.997.997 0 0 0-.217-.324l-4.5-4.5a1 1 0 0 0-1.414 1.414L8.586 7M12 7.99a.996.996 0 0 0-.076-.373Z" />
            </svg>
          </button>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import { onMounted, onUnmounted, watch } from 'vue'

import SidebarLinkGroup from './mosaic/SidebarLinkGroup.vue'
import axios from 'axios';
import Icone from './mosaic/Icone.vue';

export default {
  name: 'MenuAdmin',
  props: {
    modelValue: {
      default: true
    }
  },
  components: {
    SidebarLinkGroup,
    Icone
  },
  data() {
    return {
      trigger: null,
      sidebar: null,
      menus: [],
    }
  },
  mounted() {
    if (this.modelValue) {
        document.querySelector('body').classList.add('sidebar-expanded');
    } else {
        document.querySelector('body').classList.remove('sidebar-expanded');
    }

    document.addEventListener('click', this.clickHandler);
    document.addEventListener('keydown', this.keyHandler);
    this.buscarMenu();
    
    watch(
      () => this.modelValue,
      (newVal) => {
        localStorage.setItem('sidebar-expanded', newVal);
        if (newVal) {
          document.querySelector('body').classList.add('sidebar-expanded');
        } else {
          document.querySelector('body').classList.remove('sidebar-expanded');
        }
      }
    );
  },
  beforeUnmount() {
    document.removeEventListener('click', this.clickHandler);
    document.removeEventListener('keydown', this.keyHandler);
  },
  methods: {
    clickHandler({ target }) {
      if (!this.sidebar || !this.trigger) return;
      if (
        !this.modelValue ||
        this.sidebar.contains(target) ||
        this.trigger.contains(target)
      ) return;
      this.$emit('close-sidebar');
    },
    keyHandler({ keyCode }) {
      if (!this.modelValue || keyCode !== 27) return;
      this.$emit('close-sidebar');
    },
    buscarMenu() {
      axios.post('/menu', {}).then((response) => {
        this.menus = response.data;
      }, (error) => {
        if (error.status == 401) {
          window.location = '/';
        }
      });
    },
    redirecionar(url, externalLink = false) {
      if (externalLink) {
        window.open(url);
      } else {
        this.$router.push(url);
      }
    }
  }
}
</script>
