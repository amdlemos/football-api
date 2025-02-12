<script setup lang="ts">
import ShadcnLayout from '@/Layouts/ShadcnLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps<{
    matches?: any;
    competitionMatches?: any;
    teams?: any;
}>();
</script>

<template>

    <Head title="Competitions" />

    <ShadcnLayout>
        <h1>Teams</h1>
        <pre>{{ JSON.stringify(teams, null, 2) }}</pre>
    </ShadcnLayout>
</template>
