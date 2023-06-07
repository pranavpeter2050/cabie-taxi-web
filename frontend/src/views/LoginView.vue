<template>
  <div class="pt-16">
    <h1 class="text-3xl-font-semibold mb-4">Enter your phone number</h1>
    <form v-if="!waitingOnVerification" action="#" @submit.prevent="handleLogin">
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
    
    <!-- OTP verifiction -->
    <form v-else action="#" @submit.prevent="handleVerification">
      <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
        <div class="bg-white px-4 py-5 sm:p-6">
          <div>
            <input
              id="phone"
              class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm"
              name="phone"
              placeholder="123456"
              type="text"
              v-maska data-maska="######"
              v-model="credentials.login_code"
            >
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
          <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-white">
            Verify
          </button>
        </div>
      </div>
    </form>
  </div>  
</template>

<script setup>
import { vMaska } from "maska"
import { onMounted, reactive, ref, computed } from "vue"
import axios from "axios"
import { useRouter } from "vue-router"

const router = useRouter()

const credentials = reactive({
  phone: null,
  login_code: null
})
const waitingOnVerification = ref(false)

onMounted(() => {
  if (localStorage.getItem("token")) {
    router.push({
      name: "landing"
    })
  }
})

/* const formattedCredentials = computed(() => {
  return {
    phone: credentials.phone.replaceAll(" ", "").replace("+91-", ""),
    login_code: credentials.login_code
  }
}) */

const getFormattedCredentials = () => {
  return {
    phone: credentials.phone.replaceAll(" ", "").replace("+91-", ""),
    login_code: credentials.login_code
  }
}

const handleLogin = () => {
  axios.post("http://127.0.0.1:8000/api/login", getFormattedCredentials())
    .then((respoonse) => {
      console.log(respoonse.data)
      waitingOnVerification.value = true
    })
    .catch((error) => {
      console.error(error)
      alert(error.response.data.message)
    })
}

const handleVerification = () => {
  axios.post("http://127.0.0.1:8000/api/login/verify", getFormattedCredentials())
  .then((respoonse) => {
    console.log(respoonse.data) // this should be an auth token
    localStorage.setItem("token", respoonse.data)
    router.push({
      name: "landing"
    })
  })
  .catch((error) => {
    console.error(error)
    alert(error.response.data.message)
  })
}
</script>