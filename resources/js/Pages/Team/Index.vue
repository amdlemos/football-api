<script setup lang="ts">
import { columnsCard } from '@/Components/Matches/columns-card';
import ShadcnLayout from '@/Layouts/ShadcnLayout.vue';
import { TeamData } from '@/mytypes/generated';
import { Head } from '@inertiajs/vue3';
import { default as DataTableCard } from '../../Components/Matches/data-table-card.vue';

defineProps<{
    teams?: TeamData[];
}>();
</script>

<template>

    <Head title="Competition" />
    <ShadcnLayout>
        <h1 class="scroll-m-20 p-4 text-3xl font-extrabold tracking-tight lg:text-4xl">
            Teams
        </h1>
        <div v-if="teams" class="min-h-screen">
            <div v-if="teams.length > 0">
                <DataTableCard :columns="columnsCard" :data="teams" />
            </div>
        </div>
        <div v-else class="flex h-full items-center justify-center rounded-lg border border-dashed shadow-sm">
            <div class="flex flex-col items-center gap-1 text-center">
                <h3 class="text-2xl font-bold tracking-tight">
                    Teams not found
                </h3>
                <p class="text-sm text-muted-foreground">
                    The API request limit has been exceeded or this competition
                    has no teams.
                </p>
            </div>
        </div>
    </ShadcnLayout>
</template>
