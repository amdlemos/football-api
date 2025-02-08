<script setup lang="ts">
import ShadcnLayout from '@/Layouts/ShadcnLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps<{
    matches?: any;
}>();
</script>

<template>
    <Head title="Competitions" />

    <ShadcnLayout>
        <pre>{{ JSON.stringify(matches, null, 2) }}</pre>
    </ShadcnLayout>
</template>
