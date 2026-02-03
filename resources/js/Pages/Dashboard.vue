<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { computed } from 'vue';

// Props
const props = defineProps({
  appointments: {
    type: Array,
    default: () => [],
  },
  user: {
    type: Object,
    required: true
  }
})

// Format price helper (optional)
const formatPrice = (value) => Number(value).toLocaleString()

// Computed counts
const upcomingCount = computed(() => props.appointments.filter(a => new Date(`${a.appointment_date}T${a.appointment_time}`) > new Date() && a.status === 'confirmed').length)
const pastCount = computed(() => props.appointments.filter(a => new Date(`${a.appointment_date}T${a.appointment_time}`) < new Date() && a.status === 'completed').length)
const pendingCount = computed(() => props.appointments.filter(a => a.status === 'pending').length)

// Next appointments (sorted by date ascending)
const nextAppointments = computed(() => {
  return [...props.appointments]
    .filter(a => a.status === 'confirmed' && new Date(`${a.appointment_date}T${a.appointment_time}`) >= new Date())
    .sort((a, b) => new Date(`${a.appointment_date}T${a.appointment_time}`) - new Date(`${b.appointment_date}T${b.appointment_time}`))
    .slice(0, 5) // show next 5 appointments
})

// View appointments function
const ViewAppointment = () => {
  router.visit(route('customer.appointments'))
}
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <CustomerLayout>
      <div class="p-6 space-y-6">
        <!-- Welcome -->
        <h1 class="text-2xl font-semibold">Welcome, <span class="text-pink-700">{{ user.name }}</span>!</h1>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div class="bg-white shadow p-4 rounded">
            <p class="text-gray-500">Upcoming Appointments</p>
            <p class="text-xl font-bold">{{ upcomingCount }}</p>
          </div>
          <div class="bg-white shadow p-4 rounded">
            <p class="text-gray-500">Past Appointments</p>
            <p class="text-xl font-bold">{{ pastCount }}</p>
          </div>
          <div class="bg-white shadow p-4 rounded">
            <p class="text-gray-500">Pending Appointments</p>
            <p class="text-xl font-bold">{{ pendingCount }}</p>
          </div>
        </div>

        <!-- Next Appointments -->
        <div class="bg-white shadow p-4 rounded">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-medium text-lg mb-2">Next Appointments</h2>
                <button
                        @click="ViewAppointment"
                        class="text-pink-600 hover:underline text-sm"
                    >
                        View More
                    </button>
            </div>
            <!-- If there ARE upcoming appointments -->
            <ul
                v-if="nextAppointments && nextAppointments.length"
                class="divide-y divide-pink-200"
            >
                <li
                v-for="appointment in nextAppointments"
                :key="appointment.id"
                class="py-2 flex justify-between items-center"
                >
                <span>
                    {{
                    new Date(appointment.appointment_date).toLocaleDateString(
                        'en-US',
                        { month: 'short', day: 'numeric', year: 'numeric' }
                    )
                    }}
                    at
                    {{
                    new Date(
                        `${appointment.appointment_date}T${appointment.appointment_time}`
                    ).toLocaleTimeString('en-US', {
                        hour: '2-digit',
                        minute: '2-digit',
                    })
                    }}
                    – {{ appointment.services.map(s => s.name).join(', ') }}
                </span>

                
                </li>
            </ul>

            <!-- If there are NO upcoming appointments -->
            <p
                v-else
                class="text-gray-500 text-sm italic"
            >
                You have no confirmed upcoming appointments.
            </p>
        </div>

      </div>
    </CustomerLayout>
  </AuthenticatedLayout>
</template>
