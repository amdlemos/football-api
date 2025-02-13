import { CardHeader, CardTitle } from '@/components/ui/card';
import { TeamData } from '@/mytypes/generated';
import { Link } from '@inertiajs/vue3';
import { ColumnDef } from '@tanstack/vue-table';
import { h } from 'vue';

export const columnsCard: ColumnDef<TeamData>[] = [
    {
        accessorKey: 'name',
        cell: ({ row }) => {
            const team = row.original; // Supondo que 'original' é o objeto completo com as propriedades do time

            return h(
                Link,
                { href: '/team', 'data-id': team.id }, // Atributos do Link
                {
                    default: () => [
                        h(
                            CardHeader,
                            { class: 'flex items-center' },
                            {
                                default: () => [
                                    h('img', {
                                        src: team.crest,
                                        alt: team.name,
                                        class: 'h-12 w-12',
                                    }),
                                    h(
                                        CardTitle,
                                        {
                                            class: 'space-x-0 text-lg font-bold',
                                        },
                                        { default: () => team.shortname },
                                    ),
                                ],
                            },
                        ),
                    ],
                },
            );
        },
    },
];
