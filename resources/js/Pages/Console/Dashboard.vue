<script setup lang="ts">
    import AuthenticatedLayout from '@/Layouts/Console/AuthenticatedLayout.vue';
    import {Head} from '@inertiajs/vue3';
    import {Card,CardContent,CardHeader,CardTitle} from '@/Components/ui/card';
    import {defineProps} from 'vue';

    const props = defineProps({
        totalUsers: Number,
        totalRoles: Number,
        totalPermissions: Number
    });
</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-bold tracking-tight">Dashboard</h2>
        </template>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <Card>
                <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <CardTitle class="text-sm font-medium"> Total Users </CardTitle>
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                        role="img" class="size-4 text-muted-foreground iconify iconify--lucide" width="1em"
                        height="1em" viewBox="0 0 24 24">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87m-3-12a4 4 0 0 1 0 7.75"></path>
                        </g>
                    </svg>
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold">{{ totalUsers }}</div>
                    <!-- <p class="text-xs text-muted-foreground">+20.1% from last month</p> -->
                </CardContent>
            </Card>
            <Card>
                <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <CardTitle class="text-sm font-medium"> Total Roles </CardTitle>
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                        aria-hidden="true" role="img" class="size-4 text-muted-foreground iconify iconify--oui"
                        width="1em" height="1em" viewBox="0 0 32 32">
                        <path fill="currentColor"
                            d="M19.307 3.21a2.91 2.91 0 1 0-.223 1.94a11.64 11.64 0 0 1 8.232 7.049l1.775-.698a13.58 13.58 0 0 0-9.784-8.291m-2.822 1.638a.97.97 0 1 1 0-1.939a.97.97 0 0 1 0 1.94m-4.267.805l-.717-1.774a13.58 13.58 0 0 0-8.291 9.784a2.91 2.91 0 1 0 1.94.223a11.64 11.64 0 0 1 7.068-8.233m-8.34 11.802a.97.97 0 1 1 0-1.94a.97.97 0 0 1 0 1.94m12.607 8.727a2.91 2.91 0 0 0-2.599 1.62a11.64 11.64 0 0 1-8.233-7.05l-1.774.717a13.58 13.58 0 0 0 9.813 8.291a2.91 2.91 0 1 0 2.793-3.578m0 3.879a.97.97 0 1 1 0-1.94a.97.97 0 0 1 0 1.94M32 16.485a2.91 2.91 0 1 0-4.199 2.599a11.64 11.64 0 0 1-7.05 8.232l.718 1.775a13.58 13.58 0 0 0 8.291-9.813A2.91 2.91 0 0 0 32 16.485m-2.91.97a.97.97 0 1 1 0-1.94a.97.97 0 0 1 0 1.94">
                        </path>
                        <path fill="currentColor"
                            d="M19.19 16.35a3.879 3.879 0 1 0-5.42 0a4.85 4.85 0 0 0-2.134 4.014v1.939h9.697v-1.94a4.85 4.85 0 0 0-2.143-4.014m-4.645-2.774a1.94 1.94 0 1 1 3.88 0a1.94 1.94 0 0 1-3.88 0m-.97 6.788a2.91 2.91 0 1 1 5.819 0z"
                            class="ouiIcon__fillSecondary"></path>
                    </svg>
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold">{{ totalRoles }}</div>
                    <!-- <p class="text-xs text-muted-foreground">+180.1% from last month</p> -->
                </CardContent>
            </Card>
            <Card>
                <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <CardTitle class="text-sm font-medium"> Total Permissions </CardTitle>
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                        aria-hidden="true" role="img"
                        class="size-4 text-muted-foreground iconify iconify--icon-park-outline" width="1em"
                        height="1em" viewBox="0 0 48 48">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="4">
                            <path stroke-linejoin="round"
                                d="M20 10H6a2 2 0 0 0-2 2v26a2 2 0 0 0 2 2h36a2 2 0 0 0 2-2v-2.5"></path>
                            <path d="M10 23h8m-8 8h24"></path>
                            <circle cx="34" cy="16" r="6" stroke-linejoin="round"></circle>
                            <path stroke-linejoin="round" d="M44 28.419C42.047 24.602 38 22 34 22s-5.993 1.133-8.05 3">
                            </path>
                        </g>
                    </svg>
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold">{{ totalPermissions }}</div>
                    <!-- <p class="text-xs text-muted-foreground">+19% from last month</p> -->
                </CardContent>
            </Card>
            <Card>
                <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <CardTitle class="text-sm font-medium"> Active Now </CardTitle>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                        class="h-4 w-4 text-muted-foreground">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                    </svg>
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold">+573</div>
                    <p class="text-xs text-muted-foreground">+201 since last hour</p>
                </CardContent>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
