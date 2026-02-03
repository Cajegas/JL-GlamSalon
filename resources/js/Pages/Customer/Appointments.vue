<script setup>
import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import { computed, ref} from 'vue'

const props = defineProps({
  appointments: {
    type: Array,
    default: () => [],
  },
})

const selectedStatus = ref('all')

const filteredAppointments = computed(() => {
  const appointments = props.appointments ?? []

  let list = appointments

  if (selectedStatus.value !== 'all') {
    list = list.filter(appt => appt.status === selectedStatus.value)
  }

  return [...list].sort((a, b) => {
    const dateA = new Date(`${a.appointment_date}T${a.appointment_time}`)
    const dateB = new Date(`${b.appointment_date}T${b.appointment_time}`)
    return dateA - dateB
  })
})

const formatPrice = (value) => Number(value).toLocaleString()

// Cancellation rules
const canCancel = (appointment) => {
  const now = new Date()
  const apptDate = new Date(`${appointment.appointment_date}T${appointment.appointment_time}`)
  const diffHours = (apptDate - now) / (1000 * 60 * 60) // difference in hours

  // Rule 1: Can cancel 24+ hours before
  if (diffHours >= 24) return true

  // Rule 2: Cannot cancel within 24 hours
  if (diffHours < 24) return false

  return false
}

// Cancel appointment function (mark as canceled)
const showCancelModal = ref(false)
const selectedAppointment = ref(null)

const openCancelModal = (appointment) => {
  selectedAppointment.value = appointment
  showCancelModal.value = true
}

const closeCancelModal = () => {
  showCancelModal.value = false
  selectedAppointment.value = null
}

const confirmCancelAppointment = () => {
  if (!selectedAppointment.value) return

  router.post(
    route('customer.appointments.cancel', selectedAppointment.value.id),
    {},
    {
      preserveScroll: true,
      onFinish: () => closeCancelModal()
    }
  )
}
</script>

<template>
  <Head title="My Appointments" />
  <AuthenticatedLayout>
    <CustomerLayout>
      <div class="max-w-6xl mx-auto p-6 space-y-6">

        <div class="flex items-center justify-between mb-4">
          <h1 class="text-2xl text-pink-800 font-semibold mb-4">My Appointments</h1>
          
            <select
              v-model="selectedStatus"
              class="border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-500"
            >
              <option value="all">All</option>
              <option value="pending">Pending</option>
              <option value="confirmed">Confirmed</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
            </select>
          
        </div>
        <div v-if="filteredAppointments && filteredAppointments.length === 0" class="text-center text-gray-500">
          No appointments yet.
        </div>

        <!-- Grid layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div v-for="appt in filteredAppointments" :key="appt.id" 
            :class="[
              'border rounded-lg p-4 shadow-sm bg-white space-y-2',
              appt.status === 'confirmed' ? 'border-green-500' :
              appt.status === 'cancelled' ? 'border-red-500' :
              appt.status === 'completed' ? 'border-blue-500' :
              'border-gray-300'
            ]">
            <!-- Date & Time -->
            <div class="flex justify-between text-gray-700 font-medium">
              <span>Date: {{ new Date(appt.appointment_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) }}</span>
              <span>Time: {{ new Date(`${appt.appointment_date}T${appt.appointment_time}`).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }) }}</span>
            </div>

            <!-- Services -->
            <div class="text-gray-600">
              Services: {{ appt.services.map(s => s.name).join(', ') }}
            </div>

            <!-- Price -->
            <div class="text-gray-600">
              Price: ₱{{ formatPrice(appt.total_price) }}
            </div>

            <!-- Status -->
            <div class="text-gray-600">
              Status: <span :class="[
                'capitalize p-1 rounded text-gray-600',
                appt.status === 'confirmed' ?  'bg-green-500 text-white' :
                appt.status === 'cancelled' ?  'bg-red-500 text-white' :
                appt.status === 'completed' ?  'bg-blue-500 text-white' :
                'bg-white'
                ]">{{ appt.status }}</span>
            </div>

            <!-- Actions -->
            <div class="pt-3">
              <button
                v-if="canCancel(appt) && appt.status !== 'cancelled'"
                @click="openCancelModal(appt)"
                class="px-4 py-1 bg-pink-600 text-white rounded hover:bg-pink-700"
              >
                Cancel Appointment
              </button>
              <span
                v-else
                class="px-4 py-1 bg-gray-300 text-gray-700 rounded cursor-not-allowed"
              >
                No actions
              </span>
            </div>
          </div>
        </div>
      </div>

          <!-- Cancel Confirmation Modal -->
      <div
        v-if="showCancelModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
      >
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 space-y-4">
          <h2 class="text-lg font-semibold text-gray-800">
            Cancel Appointment
          </h2>

          <p class="text-gray-600">
            Are you sure you want to cancel this appointment?
            <br />
            <span class="text-sm italic text-gray-500">
              This action cannot be undone.
            </span>
          </p>

          <div class="flex justify-end gap-3 pt-4">
            <button
              @click="closeCancelModal"
              class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300"
            >
              No, keep it
            </button>

            <button
              @click="confirmCancelAppointment"
              class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
            >
              Yes, cancel
            </button>
          </div>
        </div>
      </div>
    </CustomerLayout>
  </AuthenticatedLayout>
</template>
