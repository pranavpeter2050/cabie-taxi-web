<template>
  <div class="pt-16">
    <h1 class="text-3xl-font-semibold mb-4">Enter your phone number</h1>
    <form action="#" @submit.prevent="handleLogin">
      <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
        <div class="bg-white px-4 py-5 sm:p-6">
          <div>
            <input
              id="phone"
              class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm"
              name="phone"
              placeholder="+91-998 800 1100"
              type="text"
              v-maska data-maska="+91-### ### ####"
              v-model="credentials.phone"
            >
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
          <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-white">
            Continue
          </button>
        </div>
      </div>
    </form>
  </div>  
</template>

<script setup>
import { vMaska } from "maska"
import { reactive } from "vue"
import axios from "axios"

const credentials = reactive({
  phone: null
})

const handleLogin = () => {
  axios.post("http://127.0.0.1:8000/api/login", {
    phone: credentials.phone.replaceAll(" ", "").replace("+91-", "")
  })
    .then((respoonse) => {
      console.log(respoonse.data)
    })
    .catch((error) => {
      console.error(error)
      alert(error.respoonse.data.message)
    })

}
</script>