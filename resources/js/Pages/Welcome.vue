<script setup>
import logo from '@/assets/JLGlamSalon.png'
import model3 from '@/assets/model3-removebg2.png'
import services3 from '@/assets/services3.png'
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import {
  ScissorsIcon,
  SparklesIcon,
  PaintBrushIcon,
  EyeIcon,
} from '@heroicons/vue/24/outline'

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    services: Array,
});
const showHeading = ref(false)

onMounted(() => {
    setTimeout(() => {
        showHeading.value = true
    }, 500)
})

</script>

<template>
    <Head title="Welcome" />

    <div class="min-h-screen bg-gray-50 text-gray-800">
     
        <header class="flex items-center justify-between px-6 py-4 bg-white shadow">
            <div class="flex justify-between items-center mb-2">
                <img :src="logo" class="h-12 w-auto"> 
                <h1 class="text-md md:text-xl font-bold tracking-wide">
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
                    <div class="flex justify-center items-center gap-3 md:gap-5">
                        <Link
                        v-if="canLogin"
                        :href="route('login')"
                        class="font-medium text-md md:text-lg hover:text-pink-600"
                        >
                        Login
                        </Link>

                        <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="rounded-xl bg-pink-600 px-4 py-2 text-white text-md md:text-lg hover:bg-pink-700"
                        >
                        Register
                        </Link>
                    </div>
                </template>

            </nav>
        </header>

        <section
            class="w-full h-[50vh] md:h-[550px] bg-left bg-cover bg-no-repeat relative bg-[center_40%] shadow-lg"
            :style="{backgroundImage: `url(${model3})`}">
            <div class="absolute inset-0 bg-black/30"></div>
                <div class="relative flex-col z-10 flex items-center justify-center h-full gap-3 transition-all duration-700 ease-out"
                    :class="showHeading ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'">
                    <h1 class="text-white text-1xl md:text-3xl font-bold text-center">
                    WELCOME TO JL GLAM SALON
                    </h1>
                    <h1 class="text-white text-2xl md:text-5xl font-bold text-center">
                    Makeup, Beautiful Hair & Nails — Made Easy!
                    </h1>
                     <Link
                        :href="$page.props.auth?.user ? route('dashboard') : route('login')"
                        class="inline-block rounded-xl bg-pink-600 px-6 py-3 text-white text-base md:text-lg hover:bg-pink-700 mt-3"
                    >
                        Book an Appointment
                    </Link>
                </div>
        </section>

        <section class="flex justify-center px-4 py-14">

            <div class="max-w-6xl w-full flex flex-col md:flex-row items-center justify-center gap-12">

                <div class="md:w-2/5 text-center md:text-left space-y-6">
                    <h2 class="text-2xl md:text-3xl font-semibold">
                        Why Our Clients Choose Us
                    </h2>

                    <div class="w-16 h-1 bg-pink-400 rounded mx-auto md:mx-0"></div>

                    <p>
                        JL Glam Salon brings expert stylists and personalized care together in a warm, welcoming atmosphere where beauty meets confidence.

                    </p>

                    <p>
                        From hair and nails to flawless makeup, every service is designed to make you look and feel your best. 
                        Step in, relax, and let us pamper you with style and confidence.
                    </p>

                    <button class="text-md rounded-3xl px-6 py-3 border border-pink-400 hover:bg-pink-400 hover:text-white transition">
                        READ MORE
                    </button>
                </div>

                <div class="md:w-3/5 flex justify-center">
                    <img
                        :src="services3"
                        class="w-full max-w-xl rounded-3xl shadow-xl object-cover"
                    />
                </div>

            </div>

            </section>

            <section class="flex flex-col items-center bg-gray-100 px-6 py-14">
                <div class="text-center md:w-1/2 space-y-5">
                    <p class="text-2xl md:text-3xl font-semibold">Our Services</p>
                    <div class="flex flex-col items-center justify-center">
                        <div class="w-16 h-1 bg-pink-400 rounded mx-auto md:mx-0"></div>
                        <p class="p-6">We offer haircuts & styling, coloring, eyebrow and eyelash tinting, perming, hair straightening, 
                        professional nail care, and flawless makeup services — all tailored to enhance your beauty and style.</p>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row gap-10 pt-8">
                    <div class="md:w-1/2 flex items-center justify-between gap-6">
                        <p class="text-gray-500 text-base leading-relaxed max-w-sm">Precision cuts, custom color, styling, perming, and straightening — tailored to elevate your everyday look.</p>
                        <div class="flex flex-col items-center text-center flex-shrink-0">
                            <div class="w-20 h-20 rounded-full bg-pink-100 flex items-center justify-center">
                                <ScissorsIcon class="w-10 h-10 text-pink-800"/>
                            </div>
                            <span class="mt-3 text-md font-medium font-semibold">
                                Hair Services
                            </span>
                        </div>
                    </div>
                    <div class="hidden md:block w-px h-32 bg-gray-300"></div>

                    <div class="md:w-1/2 flex items-center justify-between gap-6">
                        <div class="flex flex-col items-center text-center flex-shrink-0">
                            <div class="w-20 h-20 rounded-full bg-pink-100 flex items-center justify-center">
                                <SparklesIcon class="w-10 h-10 text-pink-800"/>
                            </div>
                            <span class="mt-3 text-md font-medium font-semibold">
                                Brows & Lashes
                            </span>         
                        </div>
                        <p class="text-gray-500 text-base leading-relaxed max-w-sm text-right">Shaping, tinting, and lifting treatments to define your eyes and frame your face beautifully.</p>
                    </div>
                </div>
                <div class="hidden md:flex items-center gap-16 w-3/4 p-8">
                    <div class="w-full md:w-1/2 h-px bg-gray-300 my-8"></div>
                    <div class="w-1/2 h-px bg-gray-300 my-8"></div>
                </div>
                <div class="flex flex-col md:flex-row gap-10">
                    <div class="md:w-1/2 flex items-center justify-between gap-6">
                        <p class="text-gray-500 text-base leading-relaxed max-w-sm">Soft glam to full glam — expertly applied for any occasion, enhancing your natural beauty with flawless, long-lasting results.</p>
                        <div class="flex flex-col items-center text-center flex-shrink-0">
                            <div class="w-20 h-20 rounded-full bg-pink-100 flex items-center justify-center">
                                <PaintBrushIcon class="w-10 h-10 text-pink-800"/>
                            </div>
                            <span class="mt-3 text-md font-medium font-semibold">
                                Makeup
                            </span>
                        </div>
                    </div>
                    <div class="hidden md:block w-px h-32 bg-gray-300"></div>

                    <div class="md:w-1/2 flex items-center justify-between gap-6">
                        <div class="flex flex-col items-center text-center flex-shrink-0">
                            <div class="w-20 h-20 rounded-full bg-pink-100 flex items-center justify-center">
                                <EyeIcon class="w-10 h-10 text-pink-800"/>
                            </div>
                            <span class="mt-3 text-md font-medium font-semibold">
                                Nail Care
                            </span>         
                        </div>
                        <p class="text-gray-500 text-base leading-relaxed max-w-sm text-right">Clean, polished manicures and pedicures with gel finishes and refined nail art for a flawless touch.</p>
                    </div>
                </div>
            </section>
                

        <footer class="py-10 text-center text-sm text-gray-500">
            © {{ new Date().getFullYear() }} JL Glam Salon. All rights reserved.
        </footer>
    </div>
</template>
