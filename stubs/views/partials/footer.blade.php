<footer class="bg-white border-t border-gray-200">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:items-start">
            <!-- X-Artfil - Bloc de gauche -->
            <div class="md:order-1">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">X-Artfil</h3>
                <p class="text-gray-600 text-sm">
                    Une application moderne développée avec Laravel et Filament, offrant des solutions innovantes.
                </p>
                <div class="mt-4">
                    <a href="{{ url('/admin') }}"
                        class="inline-flex items-center text-primary-500 hover:text-primary-700 text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                clip-rule="evenodd" />
                        </svg>
                        Panneau d'administration
                    </a>
                </div>
            </div>

            <!-- Navigation - Bloc du centre -->
            <div class="md:order-2 md:text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Navigation</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-1 gap-2">
                    <ul class="space-y-2">
                        <li><a href="{{ url('/') }}"
                                class="text-gray-600 hover:text-primary-500 transition-colors text-sm">Accueil</a></li>
                        @foreach ($footerPages as $page)
                            <li><a href="{{ route('page', ['slug' => $page->slug]) }}"
                                    class="text-gray-600 hover:text-primary-500 transition-colors text-sm">{{ $page->titre }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Contact - Bloc de droite -->
            <div class="md:order-3 md:text-right">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center text-gray-600 md:justify-end">
                        <svg class="w-4 h-4 mr-2 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                        {{ filament_static_pages_setting('email', 'contact@example.com') }}
                    </div>
                    <div class="flex items-center text-gray-600 md:justify-end">
                        <svg class="w-4 h-4 mr-2 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                        </svg>
                        {{ filament_static_pages_setting('telephone', '+33 X XX XX XX XX') }}
                    </div>
                    <div class="flex items-center text-gray-600 md:justify-end">
                        <svg class="w-4 h-4 mr-2 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                clip-rule="evenodd" />
                        </svg>
                        {!! nl2br(e(filament_static_pages_setting('adresse', '123 Rue de l\'Exemple, 75001 Paris'))) !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="mt-8 pt-8 border-t border-gray-200 text-center">
            <p class="text-gray-600 text-sm">
                &copy; {{ date('Y') }} X-Artfil. Tous droits réservés.
                Développé par
                <a href="https://www.notilac.fr" class="text-primary-500 hover:text-primary-700">Notilac</a> avec ❤️ en
                utilisant
                <a href="https://laravel.com" class="text-primary-500 hover:text-primary-700">Laravel</a> et
                <a href="https://filamentphp.com" class="text-primary-500 hover:text-primary-700">Filament</a>.
            </p>
        </div>
    </div>
</footer>
