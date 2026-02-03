<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import {
  HomeIcon,
  CalendarIcon,
  ClipboardDocumentListIcon,
} from '@heroicons/vue/24/outline'

const mobileSidebarOpen = ref(false)

const isActive = (name) => route().current(name)


</script>

<template>

    <!-- Mobile Header / Toggle -->
    <div class="md:hidden flex items-center justify-between p-4 bg-white border-b">
      <button
        @click="mobileSidebarOpen = true"
        class="text-gray-700 hover:text-pink-600 focus:outline-none"
      >
        <!-- Hamburger Icon -->
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </div>

  <div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside
    class="w-64 bg-white border-r p-6 fixed top-16 bottom-0 left-0 overflow-y-auto hidden md:block">
    <nav class="flex flex-col space-y-2">
      <Link
        :href="route('dashboard')"
        :class="[
          'flex items-center gap-3 rounded px-3 py-2 transition',
          isActive('dashboard')
            ? 'bg-pink-100 text-pink-600 font-medium'
            : 'hover:bg-pink-50 hover:text-pink-500'
        ]">
        <HomeIcon class="h-5 w-5 text-current" />
        Dashboard
      </Link>

      <Link
        :href="route('customer.bookappointment')"
        :class="[
          'flex items-center gap-3 rounded px-3 py-2 transition',
          isActive('customer.bookappointment')
            ? 'bg-pink-100 text-pink-600 font-medium'
            : 'hover:bg-pink-50 hover:text-pink-500'
        ]"
      >
        <CalendarIcon class="h-5 w-5 text-current" />
        Book Appointment
      </Link>

      <Link
        :href="route('customer.appointments')"
        :class="[
          'flex items-center gap-3 rounded px-3 py-2 transition',
          isActive('customer.appointments')
            ? 'bg-pink-100 text-pink-600 font-medium'
            : 'hover:bg-pink-50 hover:text-pink-500'
        ]"
      >
        <ClipboardDocumentListIcon class="h-5 w-5 text-current" />
        My Appointments
      </Link>
    </nav>
  </aside>


    <!-- Mobile Sidebar Overlay -->
    <div
      class="fixed inset-0 z-40 md:hidden"
      v-show="mobileSidebarOpen"
      @click="mobileSidebarOpen = false"
    >
      <div class="absolute inset-0 bg-black opacity-50"></div>
      <aside class="absolute top-16 left-0 w-64 bg-white border-r p-6 overflow-y-auto">
        <nav class="flex flex-col space-y-2">
          <Link
          @click="mobileSidebarOpen=false"
          :href="route('dashboard')"
          :class="[
            'flex items-center gap-3 rounded px-3 py-2 transition',
            isActive('dashboard')
              ? 'bg-pink-100 text-pink-600 font-medium'
              : 'hover:bg-pink-50 hover:text-pink-500'
          ]"
        >
          <HomeIcon class="h-5 w-5 text-current" />
          Dashboard
        </Link>
        <Link
          @click="mobileSidebarOpen=false"
          :href="route('customer.bookappointment')"
          :class="[
            'flex items-center gap-3 rounded px-3 py-2 transition',
            isActive('customer.bookappointment')
              ? 'bg-pink-100 text-pink-600 font-medium'
              : 'hover:bg-pink-50 hover:text-pink-500'
          ]"
        >
          <CalendarIcon class="h-5 w-5" />
          Book Appointment
        </Link>
        <Link
          @click="mobileSidebarOpen=false"
          :href="route('customer.appointments')"
          :class="[
            'flex items-center gap-3 rounded px-3 py-2 transition',
            isActive('customer.appointments')
              ? 'bg-pink-100 text-pink-600 font-medium'
              : 'hover:bg-pink-50 hover:text-pink-500'
          ]"
        >
          <ClipboardDocumentListIcon class="h-5 w-5" />
          Appointments
        </Link>
        </nav>
      </aside>
    </div>

    <!-- Main Content -->
    <main ref="scrollContainer" class="flex-1 md:ml-64 pt-6 px-6 bg-gray-50">
      <slot />
    </main>
  </div>
   <footer class="bg-gray-100 pt-3 pb-9 mt-auto">
        <p class="text-sm text-gray-500 italic mt-1 text-center">
          Need to cancel? Please do so at least 24 hours before your appointment. Last-minute cancellations will not be processed, so plan ahead!
          <a href="" class="text-pink-600 hover:underline">Contact support</a> if you need assistance.
        </p>
      </footer>
</template>
