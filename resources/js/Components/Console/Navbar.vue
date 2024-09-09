<script setup lang="ts">
    import {
        Sheet,
        SheetClose,
        SheetContent,
        SheetDescription,
        SheetFooter,
        SheetHeader,
        SheetTitle,
        SheetTrigger,
    } from '@/Components/ui/sheet';
    import {
        Icon
    } from '@iconify/vue';
    import NavLink from './NavLink.vue';
    import NavLinkGroup from './NavLinkGroup.vue';
    import {
        ScrollArea
    } from '@/Components/ui/scroll-area';
    import {
        Button
    } from '@/Components/ui/button';
</script>

<template>
    <Sheet>
        <SheetTrigger as-child>
            <div
                class="px-2 md:px-4 py-2 border rounded-lg cursor-pointer flex items-center gap-x-4 transition-colors bg-background hover:bg-accent">
                <span class="text-base font-bold text-primary hidden md:flex">Energeek X</span>
                <Icon icon="line-md:menu-fold-right" class="size-5" />
            </div>
        </SheetTrigger>
        <SheetContent side="left">
            <SheetHeader>
                <SheetTitle>Energeek X</SheetTitle>
                <SheetDescription></SheetDescription>
            </SheetHeader>
            <ScrollArea class="h-[calc(100%-6rem)] mt-4">
                <div class="flex flex-col gap-y-2">
                    <NavLink :href="route('console.dashboard')" :active="route().current('console.dashboard')"
                        label="Home" icon="lucide:pie-chart" />
                    <NavLinkGroup label="users">
                        <NavLink :href="route('console.user.view')" :active="route().current('console.user.view')"
                            label="User" icon="lucide:users" />
                        <NavLink :href="route('console.role.view')" :active="route().current('console.role.view')"
                            label="Role" icon="oui:app-users-roles" />
                        <NavLink :href="route('console.permission.view')" :active="route().current('console.permission.view')"
                            label="Permission" icon="icon-park-outline:permissions" />
                    </NavLinkGroup>
                    <NavLinkGroup label="to do">
                        <NavLink :href="route('console.user.view')" :active="route().current('console.user.view')"
                            label="Tasks" icon="lucide:users" />
                        <NavLink :href="route('console.role.view')" :active="route().current('console.role.view')"
                            label="Category" icon="oui:app-users-roles" />
                    </NavLinkGroup>
                </div>
            </ScrollArea>
            <SheetFooter class="mt-4">
                <SheetClose as-child>
                    <Button variant="outline" as="a" class="w-full" :href="route('dashboard')">Go To
                        Web</Button>
                </SheetClose>
            </SheetFooter>
        </SheetContent>
    </Sheet>
</template>
