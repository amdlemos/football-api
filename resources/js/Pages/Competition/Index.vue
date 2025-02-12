<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import ShadcnLayout from '@/Layouts/ShadcnLayout.vue';
import { CompetitionData } from '@/mytypes/generated';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps<{
    competitions: CompetitionData[];
}>();
</script>

<template>

    <Head title="Competitions" />

    <ShadcnLayout>
        <h1 class="scroll-m-20 p-4 text-3xl font-extrabold tracking-tight lg:text-4xl">
            Competitions
        </h1>
        <div class="grid gap-4 md:grid-cols-2 md:gap-8 lg:grid-cols-4">
            <Card v-for="competition in competitions" :key="competition.id">
                <Link href="/competition" :data="{ code: competition.code }">
                <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <CardTitle class="text-sm font-medium">
                        {{ competition.name }}
                    </CardTitle>
                    <img :src="competition.emblem" alt="Competition Emblem" class="h-8 w-8" />
                </CardHeader>

                <CardContent>
                    <div class="text-lg font-semibold">
                        {{ competition.area?.name }}
                    </div>
                    <p class="text-xs text-muted-foreground">
                        Temporada:
                        {{ competition.currentSeason?.start_date }} -
                        {{ competition.currentSeason?.end_date }}
                    </p>
                </CardContent>
                </Link>
            </Card>
        </div>
    </ShadcnLayout>
</template>
