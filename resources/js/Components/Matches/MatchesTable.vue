<template>
    <div v-if="matches">
        <h1
            class="scroll-m-20 p-4 text-2xl font-extrabold tracking-tight lg:text-3xl"
        >
            {{ title }}
        </h1>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-1 lg:grid-cols-1">
            <DataTable :columns="columns" :data="matches" />
        </div>
    </div>
</template>

<script setup lang="ts" generic="TData, TValue">
import { ColumnDef } from '@tanstack/vue-table';
import { default as DataTable } from '../../Components/Matches/data-table.vue';

const props = defineProps<{
    columns: ColumnDef<TData, TValue>[];
    matches: TData[];
    title: string;
}>();
</script>
