import { createRouter, createWebHistory } from 'vue-router'

const routerHistory = createWebHistory()

import Login from './views/Login.vue';
import Dashboard from './views/usuario/dashboard/Home.vue';
import Usuarios from './views/usuario/usuarios/Home.vue';
import Empresas from './views/usuario/empresas/Home.vue';
import Servicos from './views/usuario/servicos/Home.vue';
import Funcionarios from './views/usuario/funcionarios/Home.vue';
import Clientes from './views/usuario/clientes/Home.vue';
import Agendamentos from './views/usuario/agendamentos/Home.vue';


const router = createRouter({
  history: routerHistory,
  routes: [
    
    // Rota padrão redireciona para login
    {
      path: '/',
      component: Login
    },
    {
      path: '/dashboard',
      name: 'Dashboard',
      component: Dashboard
    },
    {
      path: '/usuarios',
      name: 'Usuarios',
      component: Usuarios
    },
    {
      path: '/empresas',
      name: 'Empresas',
      component: Empresas
    },
    {
      path: '/servicos',
      name: 'Serviços',
      component: Servicos
    },
    {
      path: '/funcionarios',
      name: 'Funcionários',
      component: Funcionarios
    },
    {
      path: '/clientes',
      name: 'Clientes',
      component: Clientes
    },
    {
      path: '/agendamentos',
      name: 'Agendamentos',
      component: Agendamentos
    },
  ]
})

export default router
