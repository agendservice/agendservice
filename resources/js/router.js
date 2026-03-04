import { createRouter, createWebHistory } from 'vue-router'

const routerHistory = createWebHistory()

import Login from './views/Login.vue';
import ResetSenha from './views/RedefinirSenha.vue';
import Dashboard from './views/usuario/dashboard/Home.vue';
import CrudBlank from './views/usuario/crudBlank/Home.vue';


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
    {
      path: '/dashboard',
      name: 'Dashboard',
      component: Dashboard
    },
    {
      path: '/crud-blank',
      name: 'CRUD Blank',
      component: CrudBlank
    },
  ]
})

export default router