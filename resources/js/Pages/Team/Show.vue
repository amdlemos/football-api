<script setup lang="ts">
import ShadcnLayout from '@/Layouts/ShadcnLayout.vue';
import { GameData } from '@/mytypes/generated';
import { Head } from '@inertiajs/vue3';
import { columns } from '../../Components/Matches/columns';
import DataTable from '../../Components/Matches/data-table.vue';

const props = defineProps<{
    upcomingMatches: GameData[];
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
            <!-- <pre>{{ JSON.stringify(upcomingMatches, null, 2) }}</pre> -->
            <DataTable :columns="columns" :data="upcomingMatches" />
        </div>
        <!-- <pre>{{ JSON.stringify(upcomingMatches, null, 2) }}</pre> -->
        <!-- <div class="grid gap-4 md:grid-cols-2 md:gap-8 lg:grid-cols-4"> -->
        <!--     <Card v-for="match in upcomingMatches" :key="match.id"> -->
        <!--         <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2"> -->
        <!--             <CardTitle class="text-sm font-medium"> -->
        <!--                 {{ match.utcDate }} -->
        <!--             </CardTitle> -->
        <!--         </CardHeader> -->
        <!--         {{ match.homeTeam.shortname }} -->
        <!--         {{ match.awayTeam.shortname }} -->
        <!---->
        <!--         <CardContent> -->
        <!--             <div class="text-lg font-semibold"></div> -->
        <!--             <p class="text-xs text-muted-foreground"></p> -->
        <!--         </CardContent> -->
        <!--     </Card> -->
        <!-- </div> -->
    </ShadcnLayout>
</template>
