<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head } from '@inertiajs/vue3'
</script>

<template>
  <Head title="Appointments" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold text-gray-800">
        Admin Dashboard
      </h2>
    </template>

    <AdminLayout>
    <!-- MAIN CONTENT ONLY -->
    <p>Appointments</p>
    </AdminLayout>
  </AuthenticatedLayout>
</template>
