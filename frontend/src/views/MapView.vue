<template>
  <div class="pt-16">
    <h1 class="text-3xl font-semibold mb-4">Here's your trip</h1>
    <form action="#">
      <div class="overfdlow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
        <div class="bg-white px-4 py-5 sm:p-6">
          <div>
            <GMapMap 
              :zoom="11"
              :center="location.destination.geometry"
              style="width: 100%; height: 256px;"
              ref="gMap"
            >
            </GMapMap>
          </div>
          <div class="mt-2">
            <p class="text-xl">Going to <strong>{{ location.destination.name }}</strong></p>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
          <button type="button"
            class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-3 text-white">
            Let's Go!
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

<script setup>
import { useLocationStore } from '@/stores/location';
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';

const location = useLocationStore()
const router = useRouter()
const gMap = ref(null)

onMounted(async () => {
  // does the user have a location set?
  if (location.destination.name === "") {
    router.push({
      name: "location"
    })
  }

  // get the user's current location
  await location.updateCurrentLocation()

  // draw path to destination on the map
  gMap.value.$mapPromise.then((mapObject) => {

    let currentPoint = new google.maps.LatLng(location.current.geometry),
        destinationPoint = new google.maps.LatLng(location.destination.geometry),
        directionsService = new google.maps.DirectionsService,
        directionsDisplay = new google.maps.DirectionsRenderer({
          map: mapObject
        })

    directionsService.route({
      origin: currentPoint,
      destination: destinationPoint,
      avoidTolls: false,
      avoidHighways: false,
      travelMode: google.maps.TravelMode.DRIVING
    }, (res, status) => {
      if (status === google.maps.DirectionsStatus.OK) {
        directionsDisplay.setDirections(res)
      }
      else {
        console.error(status)
      }
    })
  })
})
</script>