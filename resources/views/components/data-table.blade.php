{{-- DataTable: tabela reativa com busca, ordenacao, loading, partials por linha e paginacao. Props: columns, rows, search, sortBy, sortDirection, searchable, searchPlaceholder, searchDebounce, emptyMessage, cellViews, actionsView. Uso: <x-livewindui::data-table :columns="$columns" :rows="$rows" /> --}}
@php
    $rowsCollection = $rows instanceof Illuminate\Contracts\Pagination\Paginator ? $rows->items() : collect($rows);
@endphp

<div {{ $attributes->class(['space-y-4']) }}>
    @if ($searchable || isset($filters) || isset($header))
        <div class="flex flex-col gap-3 rounded-lg border border-gray-200 bg-white p-4 shadow-sm lg:flex-row lg:items-end lg:justify-between dark:border-gray-800 dark:bg-gray-900">
            @if ($searchable)
                <div class="w-full lg:max-w-sm">
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300" for="livewindui-data-table-search">
                        Buscar
                    </label>
                    <input
                        id="livewindui-data-table-search"
                        type="search"
                        placeholder="{{ $searchPlaceholder }}"
                        wire:model.live.debounce.{{ $searchDebounce }}ms="{{ $search }}"
                        class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm transition placeholder:text-gray-400 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 dark:placeholder:text-gray-500"
                    />
                </div>
            @endif

            @isset($filters)
                <div class="flex-1">
                    {{ $filters }}
                </div>
            @endisset

            @isset($header)
                <div class="flex justify-end">
                    {{ $header }}
                </div>
            @endisset
        </div>
    @endif

    <div class="relative overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div wire:loading.delay class="absolute inset-0 z-10 flex items-center justify-center bg-white/70 dark:bg-gray-900/70">
            <x-livewindui::spinner />
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        @foreach ($normalizedColumns as $column)
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-normal text-gray-600 dark:text-gray-400">
                                @if ($column['sortable'])
                                    <button
                                        type="button"
                                        class="inline-flex items-center gap-1 rounded-sm focus:outline-none focus:ring-2 focus:ring-accent"
                                        wire:click="sortBy('{{ $column['key'] }}')"
                                    >
                                        <span>{{ $column['label'] }}</span>
                                        <span aria-hidden="true" class="text-gray-400 dark:text-gray-500">
                                            @if ($sortBy === $column['key'])
                                                {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                            @else
                                                ↕
                                            @endif
                                        </span>
                                    </button>
                                @else
                                    {{ $column['label'] }}
                                @endif
                            </th>
                        @endforeach

                        @if ($actionsView || isset($actions))
                            <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-normal text-gray-600 dark:text-gray-400">
                                Acoes
                            </th>
                        @endif
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 bg-white dark:divide-gray-800 dark:bg-gray-900">
                    @forelse ($rowsCollection as $index => $row)
                        <tr wire:key="{{ $rowKey($row, $index) }}" class="transition hover:bg-accent/5 dark:hover:bg-gray-800/60">
                            @foreach ($normalizedColumns as $column)
                                <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    @if (isset($cellViews[$column['key']]))
                                        @include($cellViews[$column['key']], ['row' => $row, 'value' => $rowValue($row, $column['key'])])
                                    @else
                                        {{ $rowValue($row, $column['key']) }}
                                    @endif
                                </td>
                            @endforeach

                            @if ($actionsView || isset($actions))
                                <td class="px-4 py-4 text-right text-sm">
                                    @if ($actionsView)
                                        @include($actionsView, ['row' => $row])
                                    @else
                                        {{ $actions }}
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($normalizedColumns) + (($actionsView || isset($actions)) ? 1 : 0) }}" class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                {{ $emptyMessage }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($isPaginator)
            <x-livewindui::pagination :paginator="$rows" />
        @endif
    </div>
</div>
