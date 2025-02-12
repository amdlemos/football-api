<script setup lang="ts">
import { ToggleMode } from '@/components/ui/toggle-mode';
import { Link } from '@inertiajs/vue3';

import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroup,
    SidebarGroupLabel,
    SidebarHeader,
    SidebarInset,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarProvider,
    SidebarRail,
    SidebarTrigger,
} from '@/components/ui/sidebar';
import { GalleryVerticalEnd, Swords, Trophy, Users } from 'lucide-vue-next';
import { ref } from 'vue';

// This is sample data.
const data = {
    user: {
        name: 'shadcn',
        email: 'm@example.com',
        avatar: '/avatars/shadcn.jpg',
    },
    teams: [
        {
            name: 'amdlemos',
            logo: GalleryVerticalEnd,
            plan: 'Enterprise',
        },
    ],
    projects: [
        {
            name: 'Competitions',
            url: '/competitions',
            icon: Trophy,
        },
        {
            name: 'Matches',
            url: '/matches',
            icon: Swords,
        },
        {
            name: 'Teams',
            url: '/teams',
            icon: Users,
        },
    ],
};

const activeTeam = ref(data.teams[0]);

function setActiveTeam(team: (typeof data.teams)[number]) {
    activeTeam.value = team;
}
</script>
<script lang="ts">
export default {
    name: 'ShadcnLayout',
};
</script>

<template>

    <Head title="Shadcn" />
    <SidebarProvider>
        <Sidebar collapsible="icon">
            <SidebarHeader>
                <SidebarMenu> </SidebarMenu>
            </SidebarHeader>
            <SidebarContent>
                <SidebarGroup class="">
                    <SidebarGroupLabel>Football Data</SidebarGroupLabel>
                    <SidebarMenu>
                        <SidebarMenuItem v-for="item in data.projects" :key="item.name">
                            <SidebarMenuButton as-child>
                                <Link :href="item.url">
                                <component :is="item.icon" />
                                <span>{{ item.name }}</span>
                                </Link>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    </SidebarMenu>
                </SidebarGroup>
            </SidebarContent>
            <SidebarFooter> </SidebarFooter>
            <SidebarRail />
        </Sidebar>
        <SidebarInset>
            <header
                class="flex h-16 shrink-0 items-center gap-2 transition-[width,height] ease-linear group-has-[[data-collapsible=icon]]/sidebar-wrapper:h-12">
                <div class="flex items-center gap-2 px-4">
                    <SidebarTrigger class="-ml-1" />
                    <!-- <Separator orientation="vertical" class="mr-2 h-4" /> -->
                    <!-- <Breadcrumb> -->
                    <!--     <BreadcrumbList> -->
                    <!--         <BreadcrumbItem class="hidden md:block"> -->
                    <!--             <BreadcrumbLink href="#"> -->
                    <!--                 Building Your Application -->
                    <!--             </BreadcrumbLink> -->
                    <!--         </BreadcrumbItem> -->
                    <!--         <BreadcrumbSeparator class="hidden md:block" /> -->
                    <!--         <BreadcrumbItem> -->
                    <!--             <BreadcrumbPage>Data Fetching</BreadcrumbPage> -->
                    <!--         </BreadcrumbItem> -->
                    <!--     </BreadcrumbList> -->
                    <!-- </Breadcrumb> -->
                </div>
                <ToggleMode />
            </header>
            <div class="flex flex-1 flex-col gap-4 p-4 pt-0">
                <slot />
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
