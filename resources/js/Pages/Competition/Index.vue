<script setup lang="ts">
import ShadcnLayout from '@/Layouts/ShadcnLayout.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Head } from '@inertiajs/vue3';

defineProps<{
    teams?: any;
}>();
</script>

<template>
    <Head title="Competition" />

    <ShadcnLayout>
        <div v-if="teams.teams">
            <h1
                class="scroll-m-20 text-4xl font-extrabold tracking-tight lg:text-5xl"
            >
                <!-- {{ teams.competition.name }} -->
            </h1>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-2">
                <Card>
                    <CardHeader class="flex items-center space-x-4">
                        <div>
                            <CardTitle class="text-lg font-bold">
                                Previous Games
                            </CardTitle>
                            <p class="text-sm text-muted-foreground">
                                See the previows games
                            </p>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <p class="text-sm font-medium">Round n - 1</p>
                        <p class="text-xs text-muted-foreground">
                            1/05/2025 - 7/05/2025
                        </p>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex items-center space-x-4">
                        <div>
                            <CardTitle class="text-lg font-bold">
                                Next Games
                            </CardTitle>
                            <p class="text-sm text-muted-foreground">
                                See the next games
                            </p>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <p class="text-sm font-medium">Round n</p>
                        <p class="text-xs text-muted-foreground">
                            10/05/2025 - 11/05/2025
                        </p>
                    </CardContent>
                </Card>
            </div>
            <h1
                class="scroll-m-20 text-4xl font-extrabold tracking-tight lg:text-5xl"
            >
                Teams
            </h1>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                <Card v-for="team in teams.teams" :key="team.id">
                    <CardHeader class="flex items-center space-x-4">
                        <img
                            :src="team.crest"
                            :alt="team.name"
                            class="h-12 w-12"
                        />
                        <div>
                            <CardTitle class="text-lg font-bold">
                                {{ team.shortName }}
                            </CardTitle>
                            <p class="text-sm text-muted-foreground">
                                Founded: {{ team.founded }}
                            </p>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <p class="text-sm font-medium">
                            <!-- Coach: {{ team.coach.name }} -->
                        </p>
                        <p class="text-xs text-muted-foreground">
                            <!-- {{ team.coach.nationality }} -->
                        </p>
                        <div class="mt-2">
                            <p class="text-sm font-medium">Competitions:</p>
                            <ul class="text-xs text-muted-foreground">
                                <!-- <li -->
                                <!--     v-for="competition in team.runningCompetitions" -->
                                <!--     :key="competition.id" -->
                                <!-- > -->
                                <!--     <img -->
                                <!--         :src="competition.emblem" -->
                                <!--         class="mr-1 inline-block h-4 w-4" -->
                                <!--     /> -->
                                <!--     {{ competition.name }} -->
                                <!-- </li> -->
                            </ul>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>

        <div v-else>
            API did not return data or the limit was exceeded, try again later.
        </div>
    </ShadcnLayout>
</template>
