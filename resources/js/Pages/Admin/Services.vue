<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Modal from '@/Components/Modal.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { ref, computed, nextTick } from 'vue'

// Props
const props = defineProps({
  services: Array
})

// Utilities
const formatPrice = (value) => Number(value).toLocaleString()

// Group services by category
const servicesByCategory = computed(() => {
  const grouped = {}
  props.services.forEach(service => {
    if (!grouped[service.category]) grouped[service.category] = []
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

const editing = ref(false)
const formRef = ref(null)
const successMessage = ref('')

// Modal for delete
const modalVisible = ref(false)
const serviceToDelete = ref(null)

// Scroll to form
const scrollToForm = () => {
  nextTick(() => {
    if (formRef.value) {
      const yOffset = -80 // adjust for fixed header height
      const y = formRef.value.getBoundingClientRect().top + window.pageYOffset + yOffset
      window.scrollTo({ top: y, behavior: 'smooth' })
    }
  })
}

// Edit a service
const editService = (service) => {
  form.id = service.id
  form.category = service.category
  form.name = service.name
  form.price = service.price
  form.duration = service.duration
  editing.value = true
  scrollToForm()
}

// Cancel editing
const cancelEdit = () => {
  form.reset()
  editing.value = false
}

// Submit (create or update)
const submit = () => {
  if (editing.value && form.id) {
    form.put(route('admin.services.update', form.id), {
      onSuccess: () => {
        form.reset()
        editing.value = false
        successMessage.value = 'Service updated successfully!'
        setTimeout(() => (successMessage.value = ''), 3000)
      },
      onError: (errors) => alert('Complete all fields')
    })
  } else {
    form.post(route('admin.services.store'), {
      onSuccess: () => {
        form.reset()
        successMessage.value = 'Service created successfully!'
        setTimeout(() => (successMessage.value = ''), 3000)
      },
      onError: (errors) => alert('Complete all fields')
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
      setTimeout(() => (successMessage.value = ''), 3000)
    },
    onError: (errors) => console.log(errors)
  })
}
</script>

<template>
  <Head title="Services" />

  <AuthenticatedLayout>
    <AdminLayout>
      <h2 class="text-xl font-semibold text-gray-800 mb-4">Services</h2>

      <div class="max-w-3xl mx-auto space-y-6">
        <!-- Success message -->
        <div v-if="successMessage" class="bg-green-100 text-green-800 p-2 rounded mb-4">
          {{ successMessage }}
        </div>

        <!-- ADD / EDIT SERVICE FORM -->
        <form @submit.prevent="submit" ref="formRef" class="bg-white p-4 rounded-lg shadow space-y-4">
          <div>
            <label class="block text-sm font-medium">Category</label>
            <select v-model="form.category" class="w-full border rounded p-2">
              <option value="">Select</option>
              <option>Hair</option>
              <option>Nail</option>
              <option>Makeup</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium">Service Name</label>
            <input v-model="form.name" class="w-full border rounded p-2" />
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium">Price</label>
              <input type="number" v-model="form.price" class="w-full border rounded p-2" />
            </div>

            <div>
              <label class="block text-sm font-medium">Duration (min)</label>
              <input type="number" v-model="form.duration" class="w-full border rounded p-2" />
            </div>
          </div>

          <div class="flex space-x-2">
            <button
              class="flex-1 bg-pink-600 text-white py-2 rounded hover:bg-pink-700"
              :disabled="form.processing"
            >
              {{ editing ? 'Update Service' : 'Save Service' }}
            </button>

            <button
              v-if="editing"
              type="button"
              @click="cancelEdit"
              class="flex-1 bg-gray-300 text-gray-700 py-2 rounded hover:bg-gray-400"
            >
              Cancel
            </button>
          </div>
        </form>

        <!-- SERVICE LIST (BY CATEGORY) -->
        <div class="space-y-6">
          <div v-for="(services, category) in servicesByCategory" :key="category">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">{{ category }}</h3>

            <div class="space-y-3">
              <div
                v-for="service in services"
                :key="service.id"
                class="bg-white p-4 rounded-lg shadow flex justify-between items-center"
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
    </AdminLayout>

    <!-- Delete confirmation modal -->
    <Modal :show="modalVisible" @close="modalVisible = false" max-width="md">
      <template #default>
        <div class="p-6">
          <h3 class="text-lg font-semibold mb-4">Confirm Delete</h3>
          <p class="mb-6">
            Are you sure you want to delete
            <span class="font-bold">{{ serviceToDelete?.name }}</span>?
            This action cannot be undone.
          </p>
          <div class="flex justify-end space-x-2">
            <button
              @click="modalVisible = false"
              class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
            >
              Cancel
            </button>
            <button
              @click="deleteService"
              class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
            >
              Delete
            </button>
          </div>
        </div>
      </template>
    </Modal>
  </AuthenticatedLayout>
</template>
