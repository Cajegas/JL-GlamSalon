<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, usePage } from '@inertiajs/vue3'
import { ref, onMounted, onUnmounted } from 'vue'

const page = usePage()

const formatPrice = (value) => {
  const num = Number(value)
  return isNaN(num) ? 0 : num.toLocaleString();
}

// --- Current date & time ---
const currentDateTime = ref('')

const updateDateTime = () => {
  const now = new Date()
  currentDateTime.value = now.toLocaleString('en-US', {
    weekday: 'short',
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
    hour12: false
  })
}

let intervalId = null

onMounted(() => {
  updateDateTime()
  intervalId = setInterval(updateDateTime, 1000) // update every second
})

onUnmounted(() => {
  clearInterval(intervalId)
})


</script>

<template>
  <Head title="Dashboard" />

    <AdminLayout>
    
    <!-- MAIN CONTENT ONLY -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

      <div class="col-span-full justify-between items-center flex shadow rounded-xl bg-white p-6">
        <p class="text-gray-700 text-lg md:text-2xl">Welcome <span class="text-pink-700">{{ page.props.auth?.user?.name ?? 'User' }}</span>!</p>
        <p class="text-gray-500 italic text-sm md:text-lg">{{ currentDateTime }}</p>
      </div>
   
      <div class="bg-white shadow rounded-xl p-6 hover:bg-pink-100 shadow-sm transition-all duration-300 ease-in-out hover:scale-105 hover:shadow-xl">
        <h3 class="font-semibold text-gray-700">Today's Appointments</h3>
        <p class="mt-2 text-gray-500 italic">{{ page.props.todaysAppointments ?? 0 }} confirmed appointment/s for today.</p>
       
      </div>

       <div class="bg-white shadow rounded-xl p-6 hover:bg-pink-100 shadow-sm transition-all duration-300 ease-in-out hover:scale-105 hover:shadow-xl"> 
            <h3 class="font-semibold text-gray-700">Monthly Income</h3> 
            <p v-if="page.props.monthlyIncome" class="mt-2 text-gray-500 italic">₱{{ formatPrice(page.props.monthlyIncome)}}</p> 
            <p v-else class="mt-2 text-gray-500 italic">No income recorded this month.</p>
        </div> 

         <div class="bg-white shadow rounded-xl p-6 hover:bg-pink-100 shadow-sm transition-all duration-300 ease-in-out hover:scale-105 hover:shadow-xl">
            <h3 class="font-semibold text-gray-700">Pending Appointments</h3> 
            <p class="mt-2 text-gray-500 italic">{{ page.props.pendingAppointments ?? 0 }} pending appointment/s as of now.</p> 
        </div>

    </div>

    <div class="col-span-full grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

      <div class="bg-white shadow rounded-xl p-6 hover:bg-pink-50 min-h-[400px] shadow-sm transition-all duration-300 ease-in-out hover:scale-105 hover:shadow-xl">
        <h3 class="font-semibold text-gray-700 text-lg mb-4">Monthly Report</h3>
      </div>

      <div class="bg-white shadow rounded-xl p-6 hover:bg-pink-50 min-h-[400px] shadow-sm transition-all duration-300 ease-in-out hover:scale-105 hover:shadow-xl">
        <h3 class="font-semibold text-gray-700 text-lg mb-4">Customer Report</h3>
      </div>

    </div>

    </AdminLayout>
</template>
