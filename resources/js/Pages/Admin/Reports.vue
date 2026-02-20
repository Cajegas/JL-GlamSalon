<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, usePage } from '@inertiajs/vue3'
import Datepicker from 'vue3-datepicker'
import { ref, computed } from 'vue'
import { Chart as ChartJS, Title, Tooltip, Legend, CategoryScale, LinearScale, BarElement } from 'chart.js'
import { Bar } from 'vue-chartjs'

ChartJS.register(Title, Tooltip, Legend, CategoryScale, LinearScale, BarElement)


const startDate = ref(null)
const endDate = ref(null)
</script>

<template>
  <Head title="Reports" />
    <AdminLayout>
    <div class="p-6 space-y-6">
        <div class="col-span-full flex justify-between items-center shadow rounded-xl bg-white p-5">
            <h2 class="text-lg md:text-2xl font-semibold">Reports</h2>
            <div class="flex flex-wrap gap-4">
                <select class="rounded-xl">
                    <option value="monthly">Monthly</option>
                    <option value="Weekly">Weekly</option>
                </select>
                <Datepicker v-model="startDate" placeholder="Start Date" class="border rounded-xl p-2"/>
                <Datepicker v-model="endDate" placeholder="End Date" class="border rounded-xl p-2"/>
            </div>
        </div>

        <div class="bg-white shadow rounded-xl p-4">
            <h3 class="text-sm md:text-lg font-semibold mt-1">Revenue Overview</h3>
            <div class="w-full h-64">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white shadow rounded-xl p-4 text-center shadow-sm transition-all duration-300 ease-in-out hover:scale-105 hover:shadow-xl">
                <p class="text-sm text-gray-500">Total Appointments</p>
                <p class="text-xl font-bold"></p>
            </div>
            <div class="bg-white shadow rounded-xl p-4 text-center shadow-sm transition-all duration-300 ease-in-out hover:scale-105 hover:shadow-xl">
                <p class="text-sm text-gray-500">Completed</p>
                <p class="text-xl font-bold"></p>
            </div>
            <div class="bg-white shadow rounded-xl p-4 text-center shadow-sm transition-all duration-300 ease-in-out hover:scale-105 hover:shadow-xl">
                <p class="text-sm text-gray-500">Pending</p>
                <p class="text-xl font-bold"></p>
            </div>
            <div class="bg-white shadow rounded-xl p-4 text-center shadow-sm transition-all duration-300 ease-in-out hover:scale-105 hover:shadow-xl">
                <p class="text-sm text-gray-500">Cancelled</p>
                <p class="text-xl font-bold"></p>
            </div>
        </div>

        <div class="bg-white shadow rounded-xl p-4">
            <h3 class="text-lg font-semibold mb-2">Top Services</h3>
            <ul class="divide-y divide-gray-200">
            <li class="flex justify-between">
                <span></span>
                <span class="font-medium"></span>
            </li>
            <li class="text-gray-400 text-center py-2">No data</li>
            </ul>
        </div>

         <div class="bg-white shadow rounded-xl p-4">
            <h3 class="text-lg font-semibold mb-2">Top Customers</h3>
            <ul class="divide-y divide-gray-200">
                <li class="flex justify-between">
                <span></span>
                <span class="font-medium"></span>
                </li>
                <li class="text-gray-400 text-center py-2">No data</li>
            </ul>
        </div>
    </div>
    </AdminLayout>
</template>
