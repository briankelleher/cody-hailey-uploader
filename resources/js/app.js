import './bootstrap';

import { createApp } from 'vue/dist/vue.esm-bundler.js';
import ImageUpload from './components/FileUploader.vue';

createApp({
  components: {
    ImageUpload
  }
}).mount('#app');