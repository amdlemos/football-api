<script setup lang="ts">
import ShadcnLayout from '@/Layouts/ShadcnLayout.vue';
import { GameData } from '@/mytypes/generated';
import { Head } from '@inertiajs/vue3';
import { columns } from '../../Components/Matches/columns';

import { default as PreviousNextMatches } from '../../Components/Matches/PreviousNextMatches.vue';

const props = defineProps<{
    upcomingMatches: GameData[];
    previousMatches: GameData[];
}>();
</script>

<template>
    <Head title="Competitions" />

    <ShadcnLayout>
        <h1
            class="scroll-m-20 p-4 text-3xl font-extrabold tracking-tight lg:text-4xl"
        >
            Team Name
        </h1>

        <div
            v-if="upcomingMatches"
            class="grid grid-cols-1 gap-4 md:grid-cols-1 lg:grid-cols-1"
        >
            <PreviousNextMatches
                :upcomingMatches="upcomingMatches"
                :previousMatches="previousMatches"
                :columns="columns"
            />
        </div>
    </ShadcnLayout>
</template>
