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
  <div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside
      class="w-64 bg-white border-r p-6 fixed top-16 bottom-0 left-0 overflow-y-auto hidden md:block"
    >
      <nav class="flex flex-col space-y-2">
        <Link
        :href="route('admin.dashboard')"
        :class="[
          'flex items-center gap-3 rounded px-3 py-2 transition',
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
          'flex items-center gap-3 rounded px-3 py-2 transition',
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
          'flex items-center gap-3 rounded px-3 py-2 transition',
          isActive('admin.services')
            ? 'bg-pink-100 text-pink-600 font-medium'
            : 'hover:bg-pink-50 hover:text-pink-500'
        ]">
        <CalendarIcon class="h-5 w-5 text-current" />
        Services
      </Link>
      <Link
        :href="route('admin.reports')"
        :class="[
          'flex items-center gap-3 rounded px-3 py-2 transition',
          isActive('admin.reports')
            ? 'bg-pink-100 text-pink-600 font-medium'
            : 'hover:bg-pink-50 hover:text-pink-500'
        ]">
        <ClipboardDocumentListIcon class="h-5 w-5 text-current" />
        Income Report
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
            'flex items-center gap-3 rounded px-3 py-2 transition',
            isActive('admin.dashboard')
              ? 'bg-pink-100 text-pink-600 font-medium'
              : 'hover:bg-pink-50 hover:text-pink-500'
          ]"
        >
          <HomeIcon class="h-5 w-5 text-current" />
          Dashboard
        </Link>
        \<Link
          @click="mobileSidebarOpen=false"
          :href="route('admin.appointments')"
          :class="[
            'flex items-center gap-3 rounded px-3 py-2 transition',
            isActive('admin.appointments')
              ? 'bg-pink-100 text-pink-600 font-medium'
              : 'hover:bg-pink-50 hover:text-pink-500'
          ]"
        >
          <HomeIcon class="h-5 w-5 text-current" />
          Appointments
        </Link>
          <Link
          @click="mobileSidebarOpen=false"
          :href="route('admin.services')"
          :class="[
            'flex items-center gap-3 rounded px-3 py-2 transition',
            isActive('admin.services')
              ? 'bg-pink-100 text-pink-600 font-medium'
              : 'hover:bg-pink-50 hover:text-pink-500'
          ]"
        >
          <HomeIcon class="h-5 w-5 text-current" />
          Services
        </Link>
              <Link
          @click="mobileSidebarOpen=false"
          :href="route('admin.reports')"
          :class="[
            'flex items-center gap-3 rounded px-3 py-2 transition',
            isActive('admin.reports')
              ? 'bg-pink-100 text-pink-600 font-medium'
              : 'hover:bg-pink-50 hover:text-pink-500'
          ]"
        >
          <HomeIcon class="h-5 w-5 text-current" />
          Income Report
        </Link>
        </nav>
      </aside>
    </div>

    <!-- Main Content -->
    <main ref="scrollContainer" class="flex-1 md:ml-64 pt-6 px-6 bg-gray-50">
      <slot />
    </main>
  </div>
</template>
