import { GameData } from '@/mytypes/generated';
import { ColumnDef } from '@tanstack/vue-table';
import { h } from 'vue';

export const columns: ColumnDef<GameData>[] = [
    {
        accessorKey: 'utcDate',
        header: () => h('div', { class: 'text-right' }, 'Data'),
        cell: ({ row }) => {
            return h(
                'div',
                { class: 'text-right font-medium' },
                row.original.utcDate,
            );
        },
    },
    {
        accessorKey: 'Home',
        header: () => h('div', { class: 'text-right' }, 'Home'),
        cell: ({ row }) => {
            return h(
                'div',
                { class: 'font-medium' },
                h('div', { class: 'flex items-center justify-end gap2' }, [
                    h(
                        'div',
                        {
                            class: '',
                        },
                        row.original.homeTeam.name,
                    ),
                    h('img', {
                        src: row.original.homeTeam.crest,
                        class: 'h-8 w-8 ml-2',
                    }),
                ]),
            );
        },
    },

    {
        accessorKey: 'Away',
        header: () => h('div', { class: 'text-left' }, 'Away'),
        cell: ({ row }) => {
            return h(
                'div',
                { class: 'font-medium' },
                h('div', { class: 'flex items-center justify-start gap2' }, [
                    h('img', {
                        src: row.original.awayTeam.crest,
                        class: 'h-8 w-8 mr-2',
                    }),
                    h(
                        'div',
                        {
                            class: '',
                        },
                        row.original.awayTeam.name,
                    ),
                ]),
            );
        },
    },
];
