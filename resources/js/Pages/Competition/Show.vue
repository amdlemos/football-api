<script setup lang="ts">
import { columnsCard } from '@/Components/Matches/columns-card';
import ShadcnLayout from '@/Layouts/ShadcnLayout.vue';
import { CompetitionData, GameData } from '@/mytypes/generated';
import { Head } from '@inertiajs/vue3';
import { columns } from '../../Components/Matches/columns';
import { default as DataTable } from '../../Components/Matches/data-table.vue';

import { default as DataTableCard } from '../../Components/Matches/data-table-card.vue';
defineProps<{
    competition?: CompetitionData;
    upcomingMatches?: GameData[];
    previousMatches?: GameData[];
}>();
</script>

<template>

    <Head title="Competition" />

    <ShadcnLayout>
        <div v-if="competition?.teams" class="min-h-screen">
            <h1 class="scroll-m-20 p-4 text-3xl font-extrabold tracking-tight lg:text-4xl">
                {{ competition.name }}
            </h1>
            <h1 v-if="competition.teams.length > 0"
                class="scroll-m-20 p-4 text-2xl font-extrabold tracking-tight lg:text-3xl">
                Teams
            </h1>

            <div v-if="competition.teams.length > 0">
                <DataTableCard :columns="columnsCard" :data="competition.teams" />
            </div>
            <div v-else class="flex h-full items-center justify-center rounded-lg border border-dashed shadow-sm">
                <div class="flex flex-col items-center gap-1 text-center">
                    <h3 class="text-2xl font-bold tracking-tight">
                        Teams not found
                    </h3>
                    <p class="text-sm text-muted-foreground">
                        The API request limit has been exceeded or this
                        competition has no teams.
                    </p>
                    <!-- <Button class="mt-4"> Add Product </Button> -->
                </div>
            </div>
            <h1 v-if="upcomingMatches" class="scroll-m-20 p-4 text-2xl font-extrabold tracking-tight lg:text-3xl">
                Next Games
            </h1>

            <div v-if="upcomingMatches" class="grid grid-cols-1 gap-4 md:grid-cols-1 lg:grid-cols-1">
                <!-- <pre>{{ JSON.stringify(upcomingMatches, null, 2) }}</pre> -->
                <DataTable :columns="columns" :data="upcomingMatches" />
            </div>
            <h1 v-if="upcomingMatches" class="scroll-m-20 p-4 text-2xl font-extrabold tracking-tight lg:text-3xl">
                Previous Games
            </h1>

            <div v-if="upcomingMatches" class="grid grid-cols-1 gap-4 md:grid-cols-1 lg:grid-cols-1">
                <!-- <pre>{{ JSON.stringify(upcomingMatches, null, 2) }}</pre> -->
                <DataTable :columns="columns" :data="previousMatches" />
            </div>
        </div>
    </ShadcnLayout>
</template>
