<template>
  <div class="flex flex-col h-screen relative">
    
    <header class="top-0 left-0 bg-laranja-500 dark:bg-azul-500 z-30 w-full shadow-md">
      <div class="px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
          <div class="flex">
            <button class="text-branco-100 cursor-pointer p-2 hover:bg-laranja-300 dark:hover:bg-branco-600/50 rounded-full" @click="toggleSidebar()" aria-controls="sidebar">
              <span class="sr-only">Open sidebar</span>
              <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <rect x="4" y="5" width="16" height="2" />
                <rect x="4" y="11" width="16" height="2" />
                <rect x="4" y="17" width="16" height="2" />
              </svg>
            </button>
          </div>

          <div class="flex items-center space-x-3">
            <ThemeToggle />
            <hr class="w-px h-6 bg-branco-100 dark:bg-gray-700/60 border-none" />
            <UserMenu align="right" />
          </div>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden">
      
      <Menu v-model="sidebarOpen" />

      <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
        
        <main class="grow">
          <div class="w-full min-h-full bg-gray-100 dark:bg-azul-700">
            
            <div class="flex justify-between items-center mb-4 bg-azul-500 dark:bg-laranja-500 px-8 py-4">

              <div class="mb-4 sm:mb-0" v-show="exibirTitulo">
                <h1 class="text-xl text-white font-bold">
                  <icone v-show="icone" class="mr-2 h-20" :icone="icone"></icone>
                  {{$route.name}}
                </h1>
              </div>

              <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">

                <slot name="botoes-cabecalho" />

                <modal titulo="Filtrar" v-model="modalFiltros" :exibir-limpar="true" texto-confirmar="Buscar" @submit:confirmar="$emit('submit:buscar')" @submit:limpar="$emit('submit:limpar')">
                  <slot name="filtros"></slot>
                </modal>

                <botao icone="mdi-filter-outline" color="bg-laranja-500 rounded-lg text-white dark:bg-azul-600" v-show="exibirFiltros && !exibirFormulario" @click="modalFiltros = !modalFiltros"></botao>
                <botao text="Adicionar" color="bg-laranja-500 rounded-lg text-white dark:bg-azul-600" v-show="exibirCadastro && !exibirFormulario" @click="exibirFormulario = true;$emit('submit:cadastrar')" prepend-icon="mdi-plus"></botao>
                <botao text="Cancelar" color="bg-gray-500 rounded-lg text-white dark:bg-gray-600" v-show="exibirFormulario" @click="exibirFormulario = false" prepend-icon="mdi-undo"></botao>
              </div>

            </div>

            <modal titulo="Remover Registro" v-model="exibirRemover" texto-confirmar="Remover" @submit:confirmar="$emit('submit:remover')">
              <span style="font-size: 22px; text-align:center; display: block" class="my-6">
                Tem certeza disso?
              </span>
            </modal>

            <div class="px-6 pb-20"> <div v-if="!exibirFormulario">
                <slot name="resultados"/>
              </div>
              <div v-else>
                <slot name="formulario"/>
              </div>
            </div>

          </div>
        </main>
      </div> 
    </div>

    </div>
</template>

<script setup>
import Menu from './Menu.vue';
import Botao from './mosaic/Botao.vue';
import Modal from './mosaic/Modal.vue';
import Icone from './mosaic/Icone.vue';
import UserMenu from './mosaic/base/DropdownProfile.vue'
import ThemeToggle from './mosaic/base/ThemeToggle.vue'
import { ref } from 'vue';

// CORREÇÃO 2: Declarar os eventos que o componente pode emitir
defineEmits([
  'submit:buscar', 
  'submit:limpar', 
  'submit:cadastrar', 
  'submit:remover'
]);

// --- Props ---
defineProps({
  exibirFiltros: { type: Boolean, default: true },
  exibirCadastro: { type: Boolean, default: true },
  exibirTitulo: { type: Boolean, default: true },
  icone: { type: String, default: null }
});

// --- State Sidebar ---
const getInitialSidebarState = () => {
    const MOBILE_BREAKPOINT = 1024;
    const storedExpanded = localStorage.getItem('sidebar-expanded');
    if (window.innerWidth < MOBILE_BREAKPOINT) return false;
    if (storedExpanded !== null) return storedExpanded === 'true'; 
    return true; 
}
const sidebarOpen = ref(getInitialSidebarState());
const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value;
  localStorage.setItem('sidebar-expanded', sidebarOpen.value);
};

// --- State Geral ---
const exibirFormulario = ref(false);
const exibirRemover = ref(false);
const modalFiltros = ref(false);

// --- Expor propriedades para o template ref ---
defineExpose({
  exibirFormulario,
  exibirRemover,
  modalFiltros
});
</script>

<style scoped>
.animate-bounce-slow {
  animation: bounce 3s infinite;
}

@keyframes bounce {
  0%, 100% {
    transform: translateY(-5%);
    animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
  }
  50% {
    transform: translateY(0);
    animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
  }
}
</style>
