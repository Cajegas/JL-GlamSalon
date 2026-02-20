<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import logo  from '@/assets/JLGlamSalon.png'
import {
  HomeIcon,
  CalendarIcon,
  ClipboardDocumentListIcon,
  ChartBarIcon,
  UserIcon,
  ArrowRightOnRectangleIcon
} from '@heroicons/vue/24/outline'

const currentYear = ref(new Date().getFullYear())
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
      class="w-64 h-screen bg-white border-r p-6 fixed overflow-y-auto hidden md:block"
    >
    <div class="flex items-center gap-2 mb-5 mt-3">
      <img :src="logo" alt="JL Glam Salon" class="w-12" />
      <h1 class="text-lg font-bold">JL Glam Salon</h1>
    </div>
    <div class="border-b border-gray-300 mb-7"></div>


      <nav class="flex flex-col space-y-2">
        <Link
        :href="route('admin.dashboard')"
        :class="[
          'flex items-center gap-3 rounded-xl px-3 py-2 transition',
          isActive('admin.dashboard')
            ? 'bg-pink-100 text-pink-600 font-medium'
            : 'hover:bg-pink-50 hover:text-pink-500'
        ]">
        <HomeIcon class="h-5 w-5 text-current" />
        Dashboard
      </Link>
      <Link
        :href="route('admin.appointments')"
        :class="[
          'flex items-center gap-3 rounded-xl px-3 py-2 transition',
          isActive('admin.appointments')
            ? 'bg-pink-100 text-pink-600 font-medium'
            : 'hover:bg-pink-50 hover:text-pink-500'
        ]">
        <CalendarIcon class="h-5 w-5 text-current" />
        Appointments
      </Link>
       <Link
        :href="route('admin.services')"
        :class="[
          'flex items-center gap-3 rounded-xl px-3 py-2 transition',
          isActive('admin.services')
            ? 'bg-pink-100 text-pink-600 font-medium'
            : 'hover:bg-pink-50 hover:text-pink-500'
        ]">
        <ClipboardDocumentListIcon class="h-5 w-5 text-current" />
        Services
      </Link>
      <Link
        :href="route('admin.reports')"
        :class="[
          'flex items-center gap-3 rounded-xl px-3 py-2 transition',
          isActive('admin.reports')
            ? 'bg-pink-100 text-pink-600 font-medium'
            : 'hover:bg-pink-50 hover:text-pink-500'
        ]">
        <ChartBarIcon class="h-5 w-5 text-current" />
        Reports
      </Link>
      <Link
        :href="route('profile.edit')"
        :class="[
          'flex items-center gap-3 rounded-xl px-3 py-2 transition',
          isActive('profile.edit')
            ? 'bg-pink-100 text-pink-600 font-medium'
            : 'hover:bg-pink-50 hover:text-pink-500'
        ]">
        <UserIcon class="h-5 w-5 text-current" />
        Profile
      </Link>
      <Link
        :href="route('logout')"
        method="post"
        as="button"
        :class="[
          'flex items-center gap-3 rounded-xl px-3 py-2 transition',
          isActive('logout')
            ? 'bg-pink-100 text-pink-600 font-medium'
            : 'hover:bg-pink-50 hover:text-pink-500'
        ]">
        <ArrowRightOnRectangleIcon class="h-5 w-5 text-current" />
        Logout
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
          :href="route('admin.dashboard')"
          :class="[
            'flex items-center gap-3 rounded-xl px-3 py-2 transition',
            isActive('admin.dashboard')
              ? 'bg-pink-100 text-pink-600 font-medium'
              : 'hover:bg-pink-50 hover:text-pink-500'
          ]"
        >
          <HomeIcon class="h-5 w-5 text-current" />
          Dashboard
        </Link>
        <Link
          @click="mobileSidebarOpen=false"
          :href="route('admin.appointments')"
          :class="[
            'flex items-center gap-3 rounded-xl px-3 py-2 transition',
            isActive('admin.appointments')
              ? 'bg-pink-100 text-pink-600 font-medium'
              : 'hover:bg-pink-50 hover:text-pink-500'
          ]"
        >
          <CalendarIcon class="h-5 w-5 text-current" />
          Appointments
        </Link>
          <Link
          @click="mobileSidebarOpen=false"
          :href="route('admin.services')"
          :class="[
            'flex items-center gap-3 rounded-xl px-3 py-2 transition',
            isActive('admin.services')
              ? 'bg-pink-100 text-pink-600 font-medium'
              : 'hover:bg-pink-50 hover:text-pink-500'
          ]"
        >
          <ClipboardDocumentListIcon class="h-5 w-5 text-current" />
          Services
        </Link>
        <Link
          @click="mobileSidebarOpen=false"
          :href="route('admin.reports')"
          :class="[
            'flex items-center gap-3 rounded-xl px-3 py-2 transition',
            isActive('admin.reports')
              ? 'bg-pink-100 text-pink-600 font-medium'
              : 'hover:bg-pink-50 hover:text-pink-500'
          ]"
        >
          <ChartBarIcon class="h-5 w-5 text-current" />
          Reports
        </Link>
         <Link
          @click="mobileSidebarOpen=false"
          :href="route('profile.edit')"
          :class="[
            'flex items-center gap-3 rounded-xl px-3 py-2 transition',
            isActive('profile.edit')
              ? 'bg-pink-100 text-pink-600 font-medium'
              : 'hover:bg-pink-50 hover:text-pink-500'
          ]"
        >
          <UserIcon class="h-5 w-5 text-current" />
         Profile
        </Link>
         <Link
          @click="mobileSidebarOpen=false"
          :href="route('logout')"
          method="post"
          as="button"
          :class="[
            'flex items-center gap-3 rounded-xl px-3 py-2 transition',
            isActive('logout')
              ? 'bg-pink-100 text-pink-600 font-medium'
              : 'hover:bg-pink-50 hover:text-pink-500'
          ]"
        >
          <ArrowRightOnRectangleIcon class="h-5 w-5 text-current" />
          Logout
        </Link>
        </nav>
      </aside>
    </div>

    <!-- Main Content -->
    <div class="flex-1 md:ml-64 flex flex-col bg-gray-100 min-h-screen">
      <main ref="scrollContainer" class="flex-1 p-6">
        <slot />
      </main>
      <footer class="bg-gray-100 p-6 border">
          <p class="text-sm text-gray-500 mt-1 text-center">
          © {{ currentYear }} JL Glam Salon. All Rights Reserved
          </p>
      </footer>
    </div>
 </div>
</template>
