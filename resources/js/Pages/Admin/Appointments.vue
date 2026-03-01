<script setup>
import AppointmentsCalendar from '@/Components/AppointmentsCalendar.vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Modal from '@/Components/Modal.vue'
import { Head, usePage, router } from '@inertiajs/vue3'
import { ref, watch, computed } from 'vue'


const page = usePage()

const appointments = computed(() => page.props.appointments || []);

// const filters = ref({
//   status: page.props.filters?.status || '',
//   date: page.props.filters?.date || '',
// })

// const selectedAppointment = ref('')
// const successMessage = ref('')
// const errorMessage = ref('')

const showTextarea = ref(false)
const showEditDiv = ref(false)
const selectedEvent = ref(null)
const showModal = ref(false)
const newStatus = ref('')
const message = ref('')


const handleAppointmentClick = (event) => {
  selectedEvent.value = event
  showModal.value = true
}

const formattedAppointment = computed(() => {
  if (!selectedEvent.value) return { dateLine: '', timeLine: '' }

  const s = new Date(selectedEvent.value.start)
  const e = new Date(selectedEvent.value.end)

  return {
    dateLine: s.toLocaleDateString(undefined, {
      weekday: 'long',
      month: 'long',
      day: 'numeric'
    }),
    timeLine: `${s.toLocaleTimeString([], {
      hour: 'numeric',
      minute: '2-digit',
      hour12: true
    })} – ${e.toLocaleTimeString([], {
      hour: 'numeric',
      minute: '2-digit',
      hour12: true
    })}`
  }
})

const formatDate = (date) => {
  if (!date) return ''

  const d = date instanceof Date ? date : new Date(date)

  return d.toLocaleDateString(undefined, {
    weekday: 'long',
    month: 'long',
    day: 'numeric'
  })
}

//  function openModal(appt) {
//   selectedAppointment.value = appt
//   newStatus.value = appt.status
//   showModal.value = true

//  }

 function closeModal() {
    showModal.value = false
    showTextarea.value = false
    showEditDiv.value = false
    newStatus.value = ''
}

watch([showModal, showEditDiv], ([modal, editDiv]) => {
  if (!modal) {
    newStatus.value = ''
    showTextarea.value = false
    showEditDiv.value = false
  } else if (!editDiv) {
    newStatus.value = ''
  }
})

watch(newStatus, (value) => {
  showTextarea.value = value === 'cancelled'

  if(showTextarea.value) {
    message.value = `Hello ${selectedEvent.value.title},
        Thank you for your appointment scheduled on ${formatDate(selectedEvent.value.start)}.
        Unfortunately, we are unable to proceed due to limited resources at the selected date.
        You may book another available schedule at your convenience.

        Thank you for your understanding.

        Best Regards,
        JL Glam Salon`
  }else {
    message.value = ''
  }
})

//  function updateStatus(){
//   if (!selectedAppointment.value) return

//   if (newStatus.value === selectedAppointment.value.status) {
//     errorMessage.value = 'Selected status is the same as current status.'
//     return
//   } 

//   router.put(route('admin.appointments.update', selectedAppointment.value.id),
//   {status: newStatus.value}, 
//   {
//     onSuccess: () => {
//       successMessage.value = 'Appointment status updated successfully.'
//       errorMessage.value = ''

//       selectedAppointment.value.status = newStatus.value

//       setTimeout(() => {
//         successMessage.value = ''
//         closeModal()
//       }, 2000)

     
//     },
//      onError: (errors) => {
//         errorMessage.value = errors.status || 'Failed to update status.';
//       },
//   })
//  }

// function applyFilters() {
//   router.get(route('admin.appointments'), filters.value, { preserveState: true, replace: true, });
// }

// watch(newStatus, () => (errorMessage.value = ''))
// watch(filters, applyFilters)

</script>

<template>
  <Head title="Appointments" />

    <AdminLayout>
    <!-- MAIN CONTENT ONLY -->
      <div class="col-span-full justify-between flex shadow rounded-xl bg-white p-6 mb-6">
        <h2 class="text-lg md:text-2xl font-semibold">Manage Appointments</h2>
      </div> 

      <AppointmentsCalendar :appointments="appointments" @appointment-selected="handleAppointmentClick"/>
    </AdminLayout>

     <Modal :show="showModal" @close="showModal = false">
        <h3 class="text-center text-lg md:text-xl font-semibold mt-5">Appointment Details</h3>

        <div class="space-y-2 py-4 px-6">

            <div class="flex flex-col md:flex-row md:items-center md:space-x-2 space-x-1 md:py-2">
                <strong>Name:</strong>
                <input class="border border-gray-200 rounded-xl w-full ml-2" disabled :value="selectedEvent?.title"> 
            </div>

            <div class="flex flex-col md:flex-row md:items-center md:space-x-2 space-x-1 md:py-2">
                <strong>Time:</strong>
                <input class="border border-gray-200 rounded-xl w-full ml-2" disabled :value="formattedAppointment.dateLine + ' - ' + formattedAppointment.timeLine"> 
            </div>
               
            <div class="flex flex-col md:flex-row md:items-center md:space-x-2 space-x-1 md:py-2"> 
                <strong>Services:</strong>
                <input class="border border-gray-200 rounded-xl w-full ml-2" disabled :value="selectedEvent?.extendedProps.services">
            </div>
               
            <div class="flex flex-col md:flex-row md:items-center md:space-x-2 space-x-1 md:py-2 capitalize gap-2">
                <strong>Status:</strong>
                <span class="rounded-xl text-white text-center px-3 ml-2 inline-block"  :style="{ backgroundColor: selectedEvent.extendedProps.color }">
                    {{ selectedEvent?.extendedProps?.status}}
                </span>
                <button @click="showEditDiv = !showEditDiv" class="rounded-lg hover:bg-pink-600 hover:text-white px-2"><span class="underline">Edit Status</span></button>
            </div>
            <div v-if="showEditDiv" class="border rounded-lg p-2 flex md:justify-center flex-col">
                <select v-model="newStatus" class="border-gray-300 rounded-xl">
                  <option value="">Select Status</option>
                  <option value="completed">Completed</option>
                  <option value="cancelled">Cancelled</option>
                </select>
                <div v-if="showTextarea" class="pb-2 pt-2">
                  <span class="italic">Message to customer:</span>
                  <textarea class="border border-gray-200 rounded-xl w-full p-3 resize-none" rows="4" v-model="message">
                  </textarea>
                </div>
            </div>
           
              
            <div class="flex justify-end gap-4 pb-2 pt-3">
                <button @click="closeModal" class="px-3 py-2 bg-gray-300 rounded-xl">Close</button>
                <button v-if="newStatus" @click="" class="bg-pink-600 text-white py-2 px-3 rounded-xl hover:bg-pink-700">Update Status</button>
            </div>

        </div>
    </Modal>
</template>
