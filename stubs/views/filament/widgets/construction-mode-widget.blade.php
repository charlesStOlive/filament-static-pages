@php
    $settings = app(\App\Settings\AdminSettings::class);
    $title = $settings->construction['titre'] ?? 'Site en maintenance';
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                {{-- Icône et titre --}}
                <div class="flex items-center space-x-3">
                    @if($this->isActive)
                        <x-heroicon-s-exclamation-triangle class="w-8 h-8 text-orange-500" />
                    @else
                        <x-heroicon-s-check-circle class="w-8 h-8 text-green-500" />
                    @endif
                    
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Mode Construction
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            @if($this->isActive)
                                <span class="text-orange-600 dark:text-orange-400 font-medium">Activé</span>
                                - {{ $title }}
                            @else
                                <span class="text-green-600 dark:text-green-400 font-medium">Désactivé</span>
                                - Site accessible à tous
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center space-x-3">
                {{-- Toggle button --}}
                <x-filament::button
                    wire:click="toggleConstruction"
                    :color="$this->isActive ? 'warning' : 'success'"
                    size="sm"
                >
                    @if($this->isActive)
                        <x-heroicon-m-x-mark class="w-4 h-4 mr-2" />
                        Désactiver
                    @else
                        <x-heroicon-m-wrench-screwdriver class="w-4 h-4 mr-2" />
                        Activer
                    @endif
                </x-filament::button>

                {{-- Lien vers les paramètres --}}
                <x-filament::button
                    href="{{ \App\Filament\Pages\AdminSettingsPage::getUrl() }}"
                    color="gray"
                    size="sm"
                    outlined
                    tag="a"
                >
                    <x-heroicon-m-cog-6-tooth class="w-4 h-4 mr-2" />
                    Paramètres
                </x-filament::button>
            </div>
        </div>

        @if($this->isActive)
            <div class="mt-4 p-3 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg">
                <div class="flex items-start space-x-2">
                    <x-heroicon-s-information-circle class="w-5 h-5 text-orange-500 mt-0.5" />
                    <div class="text-sm">
                        <p class="text-orange-800 dark:text-orange-200 font-medium">
                            Attention : Le site affiche la page de maintenance
                        </p>
                        <p class="text-orange-700 dark:text-orange-300 mt-1">
                            Seuls les administrateurs connectés peuvent voir le site normal.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
