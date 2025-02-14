<script setup lang="ts">
import ShadcnLayout from '@/Layouts/ShadcnLayout.vue';
import { Head } from '@inertiajs/vue3';
import MatchesTable from '../../Components/Matches/MatchesTable.vue';
import { columns } from './../../Components/Matches/columns';

defineProps<{
    matches?: any;
}>();
</script>

<template>

    <Head title="Competitions" />

    <ShadcnLayout>
        <h1>Matches</h1>
        <div class="flex">
            <MatchesTable :key="1" title="Next Games" :columns="columns" :matches="matches" class="w-full" />
        </div>
    </ShadcnLayout>
</template>
