import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

import VueGoogleMaps from '@fawmi/vue-google-maps'

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.use(VueGoogleMaps, {
  load: {
    key: 'AIzaSyDLd6L7bMC2u5a5UC1UCOhANIlqz4h3KIE',
    libraries: "places"
  },
})

app.mount('#app')
