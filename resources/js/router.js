import { createRouter, createWebHistory } from 'vue-router'

const routerHistory = createWebHistory()

import Login from './views/Login.vue';
import ResetSenha from './views/RedefinirSenha.vue';


const router = createRouter({
  history: routerHistory,
  routes: [
    
    // Rota padrão redireciona para login
    {
      path: '/',
      component: Login
    },
    {
      path: '/redefinir-senha',
      name: 'Resetar Senha',
      component: ResetSenha
    },
  ]
})

export default router