<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head } from '@inertiajs/vue3'
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold text-gray-800">
        Admin Dashboard
      </h2>
    </template>

    <AdminLayout>
    <!-- MAIN CONTENT ONLY -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
      <div class="bg-white shadow rounded-lg p-6">
        <h3 class="font-semibold text-gray-700">Today's Appointments</h3>
      </div>
      <!-- Monthly Income Widget --> 
       <div class="bg-white shadow rounded-lg p-6"> 
            <h3 class="font-semibold text-gray-700">Monthly Income</h3> 
            <p class="mt-2 text-gray-500">$12,500</p> 
        </div> 
        <!-- Pending Appointments Widget --> 
         <div class="bg-white shadow rounded-lg p-6">
            <h3 class="font-semibold text-gray-700">Pending Appointments</h3> 
            <p class="mt-2 text-gray-500">3 pending</p> 
        </div>
    </div>
    </AdminLayout>
  </AuthenticatedLayout>
</template>
