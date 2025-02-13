import { GameData } from '@/mytypes/generated';
import { ColumnDef } from '@tanstack/vue-table';
import { format, parseISO } from 'date-fns';
import { ptBR } from 'date-fns/locale';
import { h } from 'vue';

export const columns: ColumnDef<GameData>[] = [
    {
        accessorKey: 'utcDate',
        header: () => h('div', { class: 'text-center max-w-full' }, 'Data'),
        cell: ({ row }) => {
            const utcDate = row.getValue('utcDate') as string;

            const dateObj = parseISO(utcDate.replace(' ', 'T'));

            const formattedDate = format(dateObj, 'EEE, dd/MM/yyyy', {
                locale: ptBR,
            });

            const formattedTime =
                format(dateObj, 'HH:mm') === '00:00'
                    ? 'A confirmar'
                    : format(dateObj, 'HH:mm');

            return h('div', { class: 'text-center font-medium max-w-full' }, [
                h(
                    'div',
                    {
                        class: '',
                    },
                    formattedDate,
                ),

                h(
                    'div',
                    {
                        class: '',
                    },
                    formattedTime,
                ),
            ]);
        },
    },
    {
        accessorKey: 'homeTeam',
        accessorFn: (row) => row.homeTeam.name,
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
                        row.getValue('homeTeam'),
                    ),
                    h('img', {
                        src: row.original.homeTeam.crest,
                        class: 'h-8 w-8 ml-2',
                    }),
                    h(
                        'div',
                        {
                            class: 'h-8 w-8 ml-2 text-right',
                        },
                        row.original.homeScoreFullTime || '',
                    ),
                ]),
            );
        },
    },

    {
        accessorKey: 'awayTeam',
        accessorFn: (row) => row.awayTeam.name,
        header: () => h('div', { class: 'text-left' }, 'Away'),
        cell: ({ row }) => {
            return h(
                'div',
                { class: 'font-medium' },
                h('div', { class: 'flex items-center justify-start gap2' }, [
                    h(
                        'div',
                        {
                            class: 'h-8 w-8 ml-2',
                        },
                        row.original.awayScoreFullTime || '',
                    ),
                    h('img', {
                        src: row.original.awayTeam.crest,
                        class: 'h-8 w-8 mr-2',
                    }),
                    h(
                        'div',
                        {
                            class: '',
                        },
                        row.getValue('awayTeam'),
                    ),
                ]),
            );
        },
    },
];
