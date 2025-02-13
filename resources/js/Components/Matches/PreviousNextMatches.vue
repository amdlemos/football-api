<script setup>
import MatchesTable from './MatchesTable.vue';

defineProps({
    columns: Array,
    upcomingMatches: Array,
    previousMatches: Array,
});
</script>

<template>
    <MatchesTable
        :key="1"
        title="Next Games"
        :columns="columns"
        :matches="upcomingMatches"
    />
    <MatchesTable
        :key="2"
        title="Previous Games"
        :columns="columns"
        :matches="previousMatches"
    />
</template>
