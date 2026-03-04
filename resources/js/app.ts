// import './bootstrap';
import {createApp} from 'vue';
import router from './router/index.js';
import createVuetify from './plugins/vuetify.js'
import { createPinia } from 'pinia'

import App from './components/App.vue';

createApp(App)
    .use(createVuetify)
    .use(createPinia())
    .use(router)
    .mount('#app');
