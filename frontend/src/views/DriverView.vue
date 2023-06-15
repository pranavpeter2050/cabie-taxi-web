<template>
  <div class="pt-16">
    <h1 class="text-3xl font-semibold mb-4">Cabie</h1>
    <form action="#" @submit.prevent="handleSaveDriver">
      <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
        <div class="bg-white px-4 py-5 sm:p-6">
          <div>
            <input type="text" name="name" id="name" v-model="driverDetails.name" placeholder="Full Name"
              class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm">
          </div>
          <div class="mt-2">
            <input type="number" name="year" id="year" v-model="driverDetails.year" placeholder="Car Year"
              class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm">
          </div>
          <div class="mt-2">
            <input type="text" name="make" id="make" v-model="driverDetails.make" placeholder="Make"
              class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm">
          </div>
          <div class="mt-2">
            <input type="text" name="model" id="model" v-model="driverDetails.model" placeholder="Model"
              class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm">
          </div>
          <div class="mt-2">
            <input type="text" name="color" id="color" v-model="driverDetails.color" placeholder="Color"
              class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm">
          </div>
          <div class="mt-2">
            <input type="text" name="license_plate" id="license_plate" v-model="driverDetails.license_plate" placeholder="License Plate #"
              class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm">
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
          <button type="submit"
          @submit.prevent="handleSaveDriver"
            class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-3 text-white">
            Continue
          </button>
        </div>
      </div>
    </form>
  </div>  
</template>

<script setup>
import { reactive } from "vue";
import { useRouter } from "vue-router";
import http from "../helpers/http";

const router = useRouter()

const driverDetails = reactive({
  name: "",
  year: null,
  make: "",
  model: "",
  color: "",
  license_plate: ""
})

const handleSaveDriver = () => {
  http().post('/api/driver', driverDetails)
  .then((response) => {
    router.push({
      name: "standby"
    })
  })
  .catch((error) => {
    console.error(error)
  })
}
</script>