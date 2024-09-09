<script setup lang="ts">
import { Icon } from '@iconify/vue';
import UserNav from '@/Components/Console/UserNav.vue';
import { Button } from '@/Components/ui/button';
import { useColorMode } from '@vueuse/core';
import Navbar from '@/Components/Console/Navbar.vue';
import NotificationNav from '@/Components/Console/NotificationNav.vue';
import { usePage } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';
import Swal from 'sweetalert2';

const mode = useColorMode();
const page = usePage();
const flashMessage = page.props?.flash?.status;

const getSwalCustomClass = () => {
    return mode.value === 'dark' ? 'swal2-dark' : 'swal2-light';
};

watch(mode, () => {
    console.log('Mode changed:', mode.value);
});

onMounted(() => {
    console.log('Mounted theme:', mode.value);
    if (flashMessage) {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: {
            timerProgressBar: 'custom-timer-progress-bar'
        },
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
                toast.addEventListener('click', () => {
                Swal.close();
            });
            }
        });

        Toast.fire({
            icon: "info",
            title: flashMessage
        });
    }
});

</script>

<template>
    <div class="min-h-screen bg-background">
        <!-- Page Navigation -->
        <nav class="border-b h-16 flex items-center justify-between px-6">
            <div class="flex items-center gap-x-4">
                <Navbar />
            </div>
            <div class="flex items-center gap-x-2">
                <!-- <NotificationNav /> -->
                <Button
                    variant="ghost"
                    size="icon"
                    @click="mode = mode == 'light' ? 'dark' : 'light'"
                >
                    <Icon
                        :icon="mode == 'dark' ? 'ic:outline-light-mode' : 'tdesign:mode-dark'"
                        class="size-5"
                    />
                </Button>
                <UserNav />
            </div>
        </nav>

        <!-- Page Heading -->
        <header class="w-full p-6" v-if="$slots.header">
            <slot name="header" />
        </header>

        <!-- Page Content -->
        <main class="w-full px-6">
            <slot />
        </main>
    </div>
</template>
