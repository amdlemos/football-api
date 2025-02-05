<script setup lang="ts">
import ShadcnLayout from '@/Layouts/ShadcnLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps<{
    response?: any;
}>();
</script>

<template>
    <Head title="Competitions" />

    <ShadcnLayout>
        competitions
        {{ response }}
    </ShadcnLayout>
</template>
