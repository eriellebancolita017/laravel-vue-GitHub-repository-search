import "./bootstrap";
import { createApp } from 'vue'
import App from './App.vue';
import VueAxios from 'vue-axios';
import axios from 'axios';
import {LoadingPlugin} from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';

createApp(App)
  .use(VueAxios, axios)
  .use(LoadingPlugin)
  .mount('#app')