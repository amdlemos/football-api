<script setup lang="ts" generic="TData, TValue">
import { Card } from '@/components/ui/card';
import { valueUpdater } from '@/lib/utils';
import type { ColumnDef, ColumnFiltersState } from '@tanstack/vue-table';

import { Input } from '@/components/ui/input';
import {
    FlexRender,
    getCoreRowModel,
    getFilteredRowModel,
    useVueTable,
} from '@tanstack/vue-table';
import { ref } from 'vue';

const props = defineProps<{
    columns: ColumnDef<TData, TValue>[];
    data: TData[];
}>();

const columnFilters = ref<ColumnFiltersState>([]);

const table = useVueTable({
    get data() {
        return props.data;
    },
    get columns() {
        return props.columns;
    },
    getCoreRowModel: getCoreRowModel(),
    onColumnFiltersChange: (updaterOrValue) =>
        valueUpdater(updaterOrValue, columnFilters),
    getFilteredRowModel: getFilteredRowModel(),
    state: {
        get columnFilters() {
            return columnFilters.value;
        },
    },
});
</script>

<template>
    <div class="flex flex-col gap-4">
        <Input class="max-w-sm" placeholder="Filter teams" :model-value="table.getState().globalFilter as string"
            @update:model-value="table.setGlobalFilter($event)" />
        <div v-if="table.getRowModel().rows?.length">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6">
                <Card v-for="row in table.getRowModel().rows" :key="row.id"
                    :data-state="row.getIsSelected() ? 'selected' : undefined">
                    <div v-for="cell in row.getVisibleCells()" :key="cell.id">
                        <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
                    </div>
                </Card>
            </div>
        </div>

        <div v-else class="flex min-h-screen items-center justify-center rounded-lg border border-dashed shadow-sm">
            <div class="flex flex-col items-center gap-1 text-center">
                <h3 class="text-2xl font-bold tracking-tight">
                    Teams not found
                </h3>
                <p class="text-sm text-muted-foreground">
                    The API request limit has been exceeded or this competition
                    has no teams.
                </p>
            </div>
        </div>
    </div>
</template>
