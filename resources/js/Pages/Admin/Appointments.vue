<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Modal from '@/Components/Modal.vue'
import { Head, usePage, router } from '@inertiajs/vue3'
import { ref, watch, computed } from 'vue'


const page = usePage()

const appointments = computed(() => page.props.appointments || []);

const filters = ref({
  status: page.props.filters?.status || '',
  date: page.props.filters?.date || '',
})

const showModal = ref(false)
const selectedAppointment = ref('')
const newStatus = ref('')
const successMessage = ref('')
const errorMessage = ref('')

 function openModal(appt) {
  selectedAppointment.value = appt
  newStatus.value = appt.status
  showModal.value = true

 }

 function closeModal() {
    showModal.value = false
    errorMessage.value = ''
    successMessage.value = ''
    newStatus.value = ''
    selectedAppointment.value = null
}


 function updateStatus(){
  if (!selectedAppointment.value) return

  if (newStatus.value === selectedAppointment.value.status) {
    errorMessage.value = 'Selected status is the same as current status.'
    return
  } 

  router.put(route('admin.appointments.update', selectedAppointment.value.id),
  {status: newStatus.value}, 
  {
    onSuccess: () => {
      successMessage.value = 'Appointment status updated successfully.'
      errorMessage.value = ''

      selectedAppointment.value.status = newStatus.value

      setTimeout(() => {
        successMessage.value = ''
        closeModal()
      }, 2000)

     
    },
     onError: (errors) => {
        errorMessage.value = errors.status || 'Failed to update status.';
      },
  })
 }

function applyFilters() {
  router.get(route('admin.appointments'), filters.value, { preserveState: true, replace: true, });
}

watch(newStatus, () => (errorMessage.value = ''))
watch(filters, applyFilters)

</script>

<template>
  <Head title="Appointments" />

    <AdminLayout>
    <!-- MAIN CONTENT ONLY -->
      <div class="col-span-full justify-between flex shadow rounded-xl bg-white p-6 mb-6">
        <h2 class="text-lg md:text-2xl font-semibold">Manage Appointments</h2>

        <!-- Filters -->
        <div class="flex flex-wrap gap-4">
          <select v-model="filters.status" @change="applyFilters"
                  class="border rounded-xl px-7 py-1">
            <option value="">All Status </option>
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
          </select>

          <input type="date" v-model="filters.date" @change="applyFilters"
                class="border rounded-xl px-3 py-1" />
        </div>
      </div>

      <!-- Appointments Table -->
      <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="w-full table-auto">
          <thead class="bg-gray-200">
            <tr>
              <th class="px-4 py-2">Customer</th>
              <th class="px-4 py-2">Date & Time</th>
              <th class="px-4 py-2">Services</th>
              <th class="px-4 py-2">Status</th>
              <th class="px-4 py-2">Action</th>
            </tr>
          </thead>
          <tbody class="text-center">
            <tr v-for="appt in appointments" :key="appt.id" class="border-b hover:bg-pink-100">
              <td class="px-4 py-2">{{ appt.user.name }}</td>
              <td class="px-4 py-2">
                {{ new Date(appt.appointment_date).toLocaleDateString('en-US', {month:'short', day:'numeric', year:'numeric'}) }}
                at {{ new Date(`${appt.appointment_date}T${appt.appointment_time}`).toLocaleTimeString('en-US', {hour:'2-digit', minute:'2-digit'}) }}
              </td>
              <td class="px-4 py-2">{{ appt.services.map(s => s.name).join(', ') }}</td>
              <td :class="['px-4 py-2 capitalize',
               appt.status === 'confirmed' ? 'text-green-600' :
               appt.status === 'cancelled' ? 'text-red-600' :
               appt.status === 'completed' ? 'text-blue-600' :
               'text-black'
              ]">{{ appt.status }}</td>
              <td class="px-4 py-2">
                <button 
                  v-if="appt.status !== 'completed'" @click="openModal(appt)" class="bg-pink-600 text-white px-3 py-1 rounded-xl hover:bg-pink-700">
                  Edit Status
                </button>

                <span v-else class="text-gray-400 italic">No Actions</span>
              </td>
            </tr>

            <tr v-if="appointments.length === 0">
              <td colspan="5" class="text-center py-4 text-gray-500">No appointments found.</td>
            </tr>
          </tbody>
        </table>
      </div>
 
    </AdminLayout>
    <Modal :show="showModal" @close="closeModal"
>
    
      <div class="pt-8 pl-8 sm:pl-10">
          <h2 class="text-lg font-bold mb-4 text-pink-800">
            Update Status 
          </h2>
      </div>
      <div class="text-center pt-3 pb-10">
        <p class="mb-4 text-l" v-if="selectedAppointment">
          Change status for
          <strong>{{ selectedAppointment?.user?.name }}</strong>'s appointment:
        </p>
          <select v-if="selectedAppointment" v-model="newStatus" class="border rounded-xl px-7 py-1 text-sm">
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
          </select>

          <p v-if="successMessage" class="mt-4 text-green-600 font-medium">
              {{ successMessage }}
          </p>
           <p v-if="errorMessage" class="mt-4 text-red-600 font-medium">
              {{ errorMessage }}
          </p>

      </div>

          <div class="flex justify-end gap-2 pr-10 pb-7">
            <button @click="closeModal" class="px-3 py-1 bg-gray-300 rounded-xl">
                Close
            </button>
            
            <button v-if="selectedAppointment" @click="updateStatus()" class="px-3 py-1 bg-pink-600 text-white rounded-xl hover:bg-pink-700">
              Update
            </button>
          </div>
    </Modal>
</template>
