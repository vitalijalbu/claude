@php
$groups = [
    [
        'label' => 'Tipologia corpo',
        'icon' => 'body-shape',
        'taxonomies' => [
            [
                'id' => 1,
                'title' => 'Atletico',
                'icon' => 'athletic',
            ],
            [
                'id' => 2,
                'title' => 'Normale',
                'icon' => 'normal',
            ],
            [
                'id' => 3,
                'title' => 'Magro',
                'icon' => 'slim',
            ],
            [
                'id' => 4,
                'title' => 'Robusto',
                'icon' => 'robust',
            ]
        ]
    ],
    [
        'label' => 'Colore occhi',
        'icon' => 'eye-color',
        'taxonomies' => [
            [
                'id' => 5,
                'title' => 'Azzurri',
                'icon' => 'blue-eye',
            ],
            [
                'id' => 6,
                'title' => 'Verdi',
                'icon' => 'green-eye',
            ],
            [
                'id' => 7,
                'title' => 'Marroni',
                'icon' => 'brown-eye',
            ],
            [
                'id' => 8,
                'title' => 'Neri',
                'icon' => 'black-eye',
            ]
        ]
    ], 
    [
        'label' => 'Genere',
        'icon' => 'gender',
        'taxonomies' => [
            [
                'id' => 5,
                'title' => 'Azzurri',
                'icon' => 'blue-eye',
            ],
            [
                'id' => 6,
                'title' => 'Verdi',
                'icon' => 'green-eye',
            ],
            [
                'id' => 7,
                'title' => 'Marroni',
                'icon' => 'brown-eye',
            ],
            [
                'id' => 8,
                'title' => 'Neri',
                'icon' => 'black-eye',
            ]
        ]
    ],
];
@endphp

<div x-data="{ modalIsOpen: false }">
    <x-button x-on:click="modalIsOpen = true" variant="outline" icon="filters">Filtri</x-button>
    <div x-cloak x-show="modalIsOpen" x-transition.opacity.duration.200ms x-trap.inert.noscroll="modalIsOpen"
        x-on:keydown.esc.window="modalIsOpen = false" x-on:click.self="modalIsOpen = false"
        class="fixed inset-0 z-30 flex items-end justify-center bg-black/70 p-4 pb-8 backdrop-blur-sm sm:items-center lg:p-8"
        role="dialog" aria-modal="true" aria-labelledby="filterModalTitle">
        {{-- Modal Dialog --}}
        <div x-show="modalIsOpen"
            x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity"
            x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
            class="w-full sm:w-10/12 md:w-4/5 lg:w-3/4 max-w-3xl flex flex-col gap-4 overflow-hidden rounded-lg border border-outline bg-surface text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">
            {{-- Dialog Header --}}
            <div
                class="flex items-center justify-between border-b border-outline p-4 dark:border-outline-dark dark:bg-surface-dark/20">
                <h3 id="filterModalTitle"
                    class="font-semibold tracking-wide text-on-surface-strong dark:text-on-surface-dark-strong">Filtra
                    ricerca</h3>
                <x-button x-on:click="modalIsOpen = false" aria-label="Chiudi finestra di dialogo" icon="close" variant="light"
                    size="circle" />
            </div>
            {{-- Dialog Body --}}
            <div class="px-4 py-4 overflow-y-auto max-h-[70vh]">
                <div class="flex flex-col gap-4">
                    <x-input label="Cerca" placeholder="Cerca" icon="search" />
                    
                    <!-- Taxonomies Accordion -->
                    <x-accordion.accordion>
                        @foreach($groups as $group)
                            <x-accordion.item>
                                <x-accordion.heading>
                                    <div class="flex items-center gap-2">
                                        @if(isset($group['icon']))
                                            <x-icon name="{{ $group['icon'] }}" aria-hidden="true"/>
                                        @endif
                                        <span>{{ $group['label'] }}</span>
                                    </div>
                                </x-accordion.heading>
                                <x-accordion.content>
                                    <div class="flex flex-col gap-2 pl-2">
                                        @foreach($group['taxonomies'] as $taxonomy)
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <x-checkbox type="checkbox" id="tax-{{ $taxonomy['id'] }}" name="taxonomies[]" value="{{ $taxonomy['id'] }}" class="rounded text-primary focus:ring-primary"/>
                                                <span>{{ $taxonomy['title'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </x-accordion.content>
                            </x-accordion.item>
                        @endforeach
                    </x-accordion.accordion>
                    
                    <!-- Other filters -->
                    <div class="mt-4">
                        <div class="flex gap-4 mt-4">
                            <x-input label="Prezzo minimo" type="number" placeholder="Prezzo minimo" class="w-1/2" />
                            <x-input label="Prezzo massimo" type="number" placeholder="Prezzo massimo" class="w-1/2" />
                        </div>
                    </div>
                </div>
            </div>
            {{-- Dialog Footer --}}
            <div
                class="flex flex-col-reverse justify-between gap-2 border-t border-outline p-4 dark:border-outline-dark dark:bg-surface-dark/20 sm:flex-row sm:items-center md:justify-end">
                <x-button x-on:click="modalIsOpen = false" type="button"
                    variant="outline">{{ __('site.close') }}</x-button>
                <x-button type="button"
                    variant="primary">{{ __('site.apply_filters') }}</x-button>
            </div>
        </div>
    </div>
</div>