<x-filament-static-pages.blocks.shared.section data="[]" anchor="contact"
    class="border-t border-gray-200 bg-gray-50 p-4 md:p-16 mx-auto">
    <div class="max-w-7xl mx-auto">
        <x-filament-static-pages.blocks.shared.title title="Formulaire de <strong>contact</strong>"
            couleur-primaire="primary" class="mb-16 fade-in-up" />
        <div class="grid md:grid-cols-2 gap-12">
            <!-- Informations de contact -->
            <div class="fade-in-left">
                <div class="space-y-8">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-primary-500 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold  mb-1">Email</h4>
                            <p class="">{{ filament_static_pages_setting('email', 'contact@example.com') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-secondary-500 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold  mb-1">Téléphone</h4>
                            <p class="">{{ filament_static_pages_setting('telephone', '+33 X XX XX XX XX') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-primary-500 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold  mb-1">Adresse</h4>
                            <p class="">{!! nl2br(e(filament_static_pages_setting('adresse', '123 Rue de l\'Exemple, 75001 Paris'))) !!}</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-secondary-500 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold  mb-1">Horaires</h4>
                            <p class="">{!! nl2br(e(filament_static_pages_setting('horaire', 'Lundi - Vendredi: 9h00 - 18h00'))) !!}</p>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Composant Livewire pour le formulaire de contact --}}
            @livewire('contact-form')

        </div>
    </div>
</x-filament-static-pages.blocks.shared.section>
