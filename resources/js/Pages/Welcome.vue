<script setup>
import logo from '@/assets/JLGlamSalon.png'
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
    services: Array,
});
const servicesByCategory = computed(() => {
  const grouped = {}
  props.services.forEach(service => {
    if (!grouped[service.category]) grouped[service.category] = []
    grouped[service.category].push(service)
  })
  return grouped
})

const showAll = ref(false);

const topServices = computed(() => {
    return showAll.value ? services.value : services.value.slice(0, 3);
});
</script>

<template>
    <Head title="Welcome" />

    <div class="min-h-screen bg-gray-50 text-gray-800">
     
        <header class="flex items-center justify-between px-6 py-4 bg-white shadow">
            <div class="flex justify-between items-center mb-2">
                <img :src="logo" class="h-12 w-auto"> 
                <h1 class="text-xl font-bold tracking-wide">
                JL Glam Salon
            </h1>
            </div>
            

            <nav class="flex gap-4">
                <Link
                    v-if="$page.props.auth?.user"
                    :href="
                        $page.props.auth.user.role === 'admin'
                            ? route('admin.dashboard')
                            : route('dashboard')
                    "
                    class="font-medium text-pink-600 hover:underline"
                >
                    Dashboard
                </Link>

        <template v-else>
            <div class="flex justify-center items-center gap-5">
                <Link
                v-if="canLogin"
                :href="route('login')"
                class="font-medium hover:text-pink-600"
                >
                Login
                </Link>

                <Link
                v-if="canRegister"
                :href="route('register')"
                class="rounded bg-pink-600 px-4 py-2 text-white hover:bg-pink-700"
                >
                Register
                </Link>
            </div>
        </template>



            </nav>
        </header>

        <section class="px-6 py-20 text-center bg-pink-50">
            <h2 class="text-3xl font-bold mb-4">
                Makeup, Beautiful Hair & Nails—Made Easy!
            </h2>

            <p class="max-w-xl mx-auto mb-6 text-gray-600">
                Book your salon appointment in minutes.  
                Professional stylists. Affordable prices.
            </p>

            <Link
                :href="$page.props.auth?.user ? route('dashboard') : route('login')"
                class="inline-block rounded bg-pink-600 px-6 py-3 text-white text-lg hover:bg-pink-700"
            >
                Book an Appointment
            </Link>
        </section>

       <section class="px-6 py-16 max-w-5xl mx-auto">
            <h3 class="text-2xl font-semibold text-center mb-10">
                Our Services
            </h3>

            <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 mb-6">
                <!-- Loop through categories -->
                <template v-for="(services, category) in servicesByCategory" :key="category">
                <!-- Loop through services in this category -->
                <div
                    v-for="service in (showAll ? services : services.slice(0, 3))"
                    :key="service.id"
                    class="rounded bg-white p-6 shadow text-center"
                >
                    <h4 class="font-semibold text-lg">{{ service.name }}</h4>
                    <p class="text-gray-500 mt-1">₱{{ Number(service.price).toLocaleString() }}</p>
                </div>
                </template>
            </div>

            <!-- Show All button -->
            <div class="text-center">
                <button
                v-if="props.services.length > 3"
                @click="showAll = !showAll"
                class="inline-block rounded bg-pink-600 px-6 py-3 text-white hover:bg-pink-700 transition"
                >
                {{ showAll ? 'Show Less' : 'View All Services' }}
                </button>
            </div>
        </section>

        <section class="bg-white py-16">
            <h3 class="text-2xl font-semibold text-center mb-10">
                How Booking Works
            </h3>

            <div class="max-w-4xl mx-auto grid gap-8 md:grid-cols-3 text-center">
                <div>
                    <div class="text-3xl mb-2">1️⃣</div>
                    <p>Choose a service</p>
                </div>
                <div>
                    <div class="text-3xl mb-2">2️⃣</div>
                    <p>Select date & time</p>
                </div>
                <div>
                    <div class="text-3xl mb-2">3️⃣</div>
                    <p>Confirm booking</p>
                </div>
            </div>
        </section>

        <footer class="py-10 text-center text-sm text-gray-500">
            © {{ new Date().getFullYear() }} JL Glam Salon. All rights reserved.
        </footer>
    </div>
</template>
