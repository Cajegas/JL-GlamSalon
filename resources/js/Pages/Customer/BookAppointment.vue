<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Head, useForm, router, usePage } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const props = defineProps({
  services: Array,
  appointments: Array,
})

/* --------------------
  STATE
-------------------- */
const selectedServices = ref([])
const appointmentDate = ref('')
const appointmentTime = ref('')
const bookingConfirmed = ref(false)
const successMessage = ref('')
const formatPrice = (value) => Number(value).toLocaleString()

/* --------------------
  COMPUTED
-------------------- */

// Group services by category
const servicesByCategory = computed(() => {
  const grouped = {}
  props.services.forEach(service => {
    if (!grouped[service.category]) grouped[service.category] = []
    grouped[service.category].push(service)
  })
  return grouped
})

// Total duration of selected services
const totalDuration = computed(() =>
  selectedServices.value.reduce((sum, s) => sum + s.duration, 0)
)

// Total price
const totalPrice = computed(() =>
  selectedServices.value.reduce((sum, s) => sum + Number(s.price), 0)
)

/* --------------------
  TIME LOGIC
-------------------- */

// Salon hours
const timeSlots = ['09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00']

// Convert HH:mm → minutes
const toMinutes = (time) => {
  const [h, m] = time.split(':').map(Number)
  return h * 60 + m
}

// Block occupied slots (duration-aware)
const availableTimeSlots = computed(() => {
  if (!appointmentDate.value) return []

  const appts = props.appointments ?? []
  return timeSlots.filter(slot => {
    const slotStart = toMinutes(slot)
    const slotEnd = slotStart + totalDuration.value

    return !appts.some(appt => {
      if (!appt || appt.appointment_date !== appointmentDate.value) return false
      const services = appt.services ?? [] // <-- never undefined
      const apptStart = toMinutes(appt.appointment_time)
      const apptDuration = services.reduce((sum, s) => sum + (s.duration ?? 0), 0)
      const apptEnd = apptStart + apptDuration
      return slotStart < apptEnd && slotEnd > apptStart
    })
  })
})


/* --------------------
  FORM
-------------------- */
const page = usePage()

const form = useForm({
  appointment_date: '',
  appointment_time: '',
  services: [],
  total_price: 0,
})

const submitBooking = () => {
  if (selectedServices.value.length === 0) {
    alert('Please select at least one service.')
    return
  }
  if (!appointmentDate.value || !appointmentTime.value) {
    alert('Please select a date and time.')
    return
  }

  form.appointment_date = appointmentDate.value
  form.appointment_time = appointmentTime.value
  form.services = selectedServices.value.map(s => ({ id: s.id, price: s.price }))
  form.total_price = totalPrice.value

  form.post(route('customer.booking.store'), {
    onFinish: () => {
      bookingConfirmed.value = true
      successMessage.value = page.props.flash.success
      // reset form
      selectedServices.value = []
      appointmentDate.value = ''
      appointmentTime.value = ''
    },
  })
}

const goToAppointments = () => {
  router.visit(route('customer.appointments'))
}
</script>

<template>
  <Head title="Booking" />

  <AuthenticatedLayout>
    <CustomerLayout>
      <div class="max-w-3xl mx-auto p-6 space-y-8">

        <!-- SUCCESS CONFIRMATION -->
        <section v-if="bookingConfirmed" class="bg-green-100 p-6 rounded shadow">
        <div v-if="successMessage" class="bg-green-100 text-green-700 p-3 rounded mb-3">
            {{ successMessage }}
        </div>

        <button
            @click="goToAppointments"
            class="bg-pink-600 text-white py-2 px-4 rounded hover:bg-pink-700"
        >
            View My Appointments
        </button>
        </section>

        <!-- BOOKING FORM -->
        <section v-else>
          <!-- SERVICES -->
          <h2 class="text-xl text-pink-800 font-semibold mb-3">Select Services</h2>
          <div v-for="(services, category) in servicesByCategory" :key="category" class="mb-4">
            <h3 class="text-pink-800 font-medium text-gray-700 mb-2">{{ category }}</h3>
            <div class="space-y-2">
              <label
                v-for="service in services"
                :key="service.id"
                :class="['flex justify-between items-center p-3 border rounded cursor-pointer',
                    selectedServices.includes(service) ? 'bg-pink-50 border-pink-400' : 'bg-white'

  ]"
              >
                <div>
                  <p class="font-medium">{{ service.name }}</p>
                  <p class="text-sm text-gray-500">{{ service.duration }} mins · ₱{{ formatPrice(service.price) }}</p>
                </div>
                <input type="checkbox" :value="service" v-model="selectedServices" />
              </label>
            </div>
          </div>

          <!-- DATE -->
          <section v-if="selectedServices.length">
            <h2 class="text-lg text-pink-800 font-semibold mb-3">Select Date</h2>
            <input type="date" v-model="appointmentDate" class="border rounded p-2 w-full" :min="new Date().toISOString().split('T')[0]" />
          </section>

          <!-- TIME -->
          <section v-if="appointmentDate">
            <h2 class="text-lg text-pink-800 font-semibold mt-3 mb-3">Select Time</h2>
            <div class="grid grid-cols-3 gap-2">
              <button
                v-for="time in availableTimeSlots"
                :key="time"
                @click="appointmentTime = time"
                :class="[
                  'p-2 border rounded',
                  appointmentTime === time ? 'bg-pink-600 text-white' : 'bg-white'
                ]"
              >
                {{ time }}
              </button>
            </div>
            <p class="text-sm text-gray-500 mt-2">Total duration: {{ totalDuration }} mins</p>
          </section>

          <!-- REVIEW -->
          <section v-if="appointmentTime">
            <h2 class="text-lg text-pink-800 font-semibold mt-3 mb-2">Review</h2>
            <ul class="text-sm">
              <li v-for="s in selectedServices" :key="s.id">{{ s.name }} — ₱{{ formatPrice(s.price) }}</li>
            </ul>
            <p class="font-semibold mb-3 mt-3">Total: ₱{{ formatPrice(totalPrice) }}</p>
          </section>

          <!-- CONFIRM -->
          <section v-if="appointmentTime">
            <button
              @click="submitBooking"
              class="w-full bg-pink-600 text-white py-2 rounded hover:bg-pink-700"
            >
              Confirm Booking
            </button>
          </section>
        </section>

      </div>
    </CustomerLayout>
  </AuthenticatedLayout>
</template>