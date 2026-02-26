require('./bootstrap');
import '@mdi/font/css/materialdesignicons.css';

import { createApp } from 'vue'
import router from './router'
import App from './components/App.vue'

const app = createApp(App)
app.use(router)
app.mount('#app')
