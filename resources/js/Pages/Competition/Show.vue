<script setup lang="ts">
import { columnsCard } from '@/Components/Matches/columns-card';
import ShadcnLayout from '@/Layouts/ShadcnLayout.vue';
import { CompetitionData, GameData } from '@/mytypes/generated';
import { Head } from '@inertiajs/vue3';
import { columns } from '../../Components/Matches/columns';

import { default as PreviousNextMatches } from '../../Components/Matches/PreviousNextMatches.vue';
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
            <h1
                class="scroll-m-20 p-4 text-3xl font-extrabold tracking-tight lg:text-4xl"
            >
                {{ competition.name }}
            </h1>
            <h1
                v-if="competition.teams.length > 0"
                class="scroll-m-20 p-4 text-2xl font-extrabold tracking-tight lg:text-3xl"
            >
                Teams
            </h1>

            <div v-if="competition.teams.length > 0">
                <DataTableCard
                    :columns="columnsCard"
                    :data="competition.teams"
                />
            </div>
            <div
                v-else
                class="flex h-full items-center justify-center rounded-lg border border-dashed shadow-sm"
            >
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
            <PreviousNextMatches
                :upcomingMatches="upcomingMatches"
                :previousMatches="previousMatches"
                :columns="columns"
            />
        </div>
    </ShadcnLayout>
</template>
