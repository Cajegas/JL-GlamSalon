<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Modal from '@/Components/Modal.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { ref, computed, watch } from 'vue'
import { PlusIcon } from '@heroicons/vue/24/outline'

// Props
const props = defineProps({
  services: {
    type: Array,
    default: () => []
  }
})

const editing = ref(false)
const formRef = ref(null)
const successMessage = ref('')
const errorMessage = ref('')

const modalVisible = ref(false)
const showServiceModal = ref(false)
const serviceToDelete = ref(null)

// Utilities
const formatPrice = (value) => Number(value).toLocaleString()

const search = ref('')

const filteredServices = computed(() => {
  if(!search.value?.trim) return props.services

  const term = search.value.toLowerCase().trim()

  return props.services.filter(service => 
    service.name?.toLowerCase().includes(term) ||
    service.category?.toLowerCase().includes(term) 
  )
})

// Group services by category
const servicesByCategory = computed(() => {
  const grouped = {}
  filteredServices.value.forEach(service => {
    if (!grouped[service.category]) {
      grouped[service.category] = []
    }
    grouped[service.category].push(service)
  })
  return grouped
})

// Form setup
const form = useForm({
  id: null,
  category: '',
  name: '',
  price: '',
  duration: ''
})

const allFieldsFilled = computed(() => {
  return form.category && form.name && form.price && form.duration

})

watch(allFieldsFilled, (filled) =>{
  if (filled) {
    errorMessage.value = ''
  }
})

watch(showServiceModal, (show) => {
  if(!show) {
    errorMessage.value = ''
    form.reset()
  }
})

// Edit a service
const editService = (service) => {
  showServiceModal.value = true
  form.id = service.id
  form.category = service.category
  form.name = service.name
  form.price = service.price
  form.duration = service.duration
  editing.value = true
}

// Cancel editing
const cancelEdit = () => {
  form.reset()
  editing.value = false
  showServiceModal.value = false
}

const capitalizeWords = (str) => {
  if (!str) return ''
  return str
    .split(' ')                     // split the string into words
    .map(word => {
      if (!word) return ''          // handle extra spaces
      return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()
    })
    .join(' ')                      // join back into a single string
}


// Submit (create or update)
const submit = () => {
  form.name = capitalizeWords(form.name)
  
  if (editing.value && form.id) {
    form.put(route('admin.services.update', form.id), {
      onSuccess: () => {
        form.reset()
        editing.value = false
        showServiceModal.value = false
        successMessage.value = 'Service updated successfully!'
        setTimeout(() => {
          successMessage.value = ''
          errorMessage.value = ''
        }, 3000)
      },
      onError: (errors) => errorMessage.value = 'Complete all fields'
    })
  } else {
    form.post(route('admin.services.store'), {
      onSuccess: () => {
        form.reset()
        showServiceModal.value = false
        successMessage.value = 'Service created successfully!'
        setTimeout(() => {
          successMessage.value = ''
          errorMessage.value = ''
        }, 3000)
      },
      onError: (errors) =>  errorMessage.value = 'Complete all fields'
    })
  }
}

// Confirm delete modal
const confirmDelete = (service) => {
  serviceToDelete.value = service
  modalVisible.value = true
}

// Delete service
const deleteService = () => {
  if (!serviceToDelete.value) return

  form.delete(route('admin.services.destroy', serviceToDelete.value.id), {
    onSuccess: () => {
      modalVisible.value = false
      serviceToDelete.value = null
      successMessage.value = 'Service deleted successfully!'
      setTimeout(() => {
        successMessage.value = ''
        errorMessage.value = ''
      }, 3000)
    },
    onError: (errors) =>   errorMessage.value = 'Unable to delete the service.'
  })
}
</script>

<template>
  <Head title="Services" />

    <AdminLayout>
      <div class="col-span-full justify-between flex items-center shadow rounded-xl bg-white p-5 mb-6">
        <h2 class="text-md md:text-2xl font-semibold text-gray-800">Manage Services</h2>
          <input type="text" v-model="search" placeholder="Search services..." 
         class="w-full max-w-md border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500"/>
      </div>

      <div class="max-w-3xl mx-auto space-y-6">
    
        <!-- SERVICE LIST (BY CATEGORY) -->
        <div class="space-y-6">
          <div class="flex justify-end">
            <button
              @click="showServiceModal = true"
              class="group flex items-center gap-2 text-pink-800 text-sm md:text-lg px-3 py-2 rounded-xl
                    transition-all duration-300 hover:bg-pink-700 hover:text-white">
              <PlusIcon
                class="h-6 w-6 transition-transform duration-300 group-hover:rotate-90 group-hover:scale-110"/>
              <span>Add Service</span>
            </button>
          </div>

           <!-- Success message -->
          <div v-if="successMessage" class="bg-green-100 text-green-800 p-2 rounded-xl mb-4">
            {{ successMessage }}
          </div>


          <div v-if="Object.keys(servicesByCategory).length === 0" class=" text-center">
            <hr class="my-6 border-gray-300"/>
            <span class="italic">No service available.</span>
          </div>
          
          <div v-else>
            <div v-for="(services, category) in servicesByCategory" :key="category">
              <h3 class="text-lg font-semibold text-gray-700 mb-2">{{ category }}</h3>

              <div v-if="services.length === 0" class="text-center">
                <hr class="my-6 border-gray-300"/>
                <span class="italic">No service available.</span>
              </div>

              <div v-else class="space-y-3">
                <div
                  v-for="service in services"
                  :key="service.id"
                  class="bg-white p-4 rounded-xl shadow flex justify-between items-center"
                >
                  <div>
                    <p class="font-semibold">{{ service.name }}</p>
                    <p class="text-sm text-gray-500">{{ service.duration }} mins</p>
                  </div>

                  <div class="flex items-center space-x-2">
                    <div class="font-bold text-pink-600">₱{{ formatPrice(service.price) }}</div>
                    <span class="text-gray-400">|</span>
                    <button
                      @click="editService(service)"
                      class="text-blue-600 hover:underline text-sm"
                    >
                      Edit
                    </button>
                    <span class="text-gray-400">|</span>
                    <button
                      @click="confirmDelete(service)"
                      class="text-red-600 hover:underline text-sm"
                    >
                      Delete
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AdminLayout>

    <!-- Delete confirmation modal -->
    <Modal :show="modalVisible" @close="modalVisible = false" max-width="md">
      <template #default>
        <div class="p-6">
          <h3 class="text-lg font-semibold mb-4">Confirm Delete</h3>
          <p class="mb-6">
            Are you sure you want to delete
            <span class="font-bold">{{ serviceToDelete?.name }}</span> service?
            This action cannot be undone.
          </p>
          <div class="flex justify-end space-x-2">
            <button
              @click="modalVisible = false"
              class="px-4 py-2 bg-gray-300 rounded-xl hover:bg-gray-400"
            >
              Cancel
            </button>
            <button
              @click="deleteService"
              class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700"
            >
              Delete
            </button>
          </div>
        </div>
      </template>
    </Modal>

    <!-- Add service modal-->
     <Modal :show="showServiceModal" @close="showServiceModal = false" max-width="xl">
        <template #default>
      <!-- ADD / EDIT SERVICE FORM -->

         <!-- Error message -->
          <div v-if="errorMessage" class="bg-red-100 text-red-800 mx-2 m-2 p-2 text-center rounded-xl">
            {{ errorMessage }}
          </div>

          <form @submit.prevent="submit" ref="formRef" class="bg-white p-4 rounded-xl shadow space-y-4">
            <div>
              <label class="block text-sm font-medium">Category <span class="text-red-800 text-md">*</span></label>
              <select v-model="form.category" class="w-full border rounded-xl p-2">
                <option value="">Select</option>
                <option>Hair</option>
                <option>Nail</option>
                <option>Makeup</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium">Service Name <span class="text-red-800 text-md">*</span></label>
              <input v-model="form.name" class="w-full border rounded-xl p-2" />
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium">Price <span class="text-red-800 text-md">*</span></label>
                <input type="number" v-model="form.price" class="w-full border rounded-xl p-2" />
              </div>

              <div>
                <label class="block text-sm font-medium">Duration (min) <span class="text-red-800 text-md">*</span></label>
                <input type="number" v-model="form.duration" class="w-full border rounded-xl p-2" />
              </div>
            </div>

            <div class="flex space-x-2">
               <button type="button" @click="cancelEdit"
                class="flex-1 bg-gray-300 text-gray-700 py-2 rounded-xl hover:bg-gray-400">
                Cancel
              </button>

              <button class="flex-1 bg-pink-600 text-white py-2 rounded-xl hover:bg-pink-700"
                :disabled="form.processing">{{ editing ? 'Update Service' : 'Save Service' }}
              </button>

            </div>
          </form>
        </template>
     </Modal>
</template>