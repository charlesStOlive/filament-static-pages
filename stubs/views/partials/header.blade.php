<nav class="bg-white shadow-sm border-b border-gray-200" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ url('/') }}" class="flex items-center">
                    @if (!empty($siteLogo))
                        <img src="{{ Storage::url($siteLogo) }}" alt="Logo" class="p-2 h-16 w-32 object-contain">
                    @else
                        <svg class="h-8 w-8 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="ml-2 text-xl font-bold text-gray-900">X-Artfil</span>
                    @endif
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ url('/') }}"
                    class="{{ request()->is('/') ? 'text-primary-500 font-bold' : 'text-gray-700 hover:text-primary-500' }} px-3 py-2 text-sm font-medium transition-colors">
                    Accueil
                </a>
                @foreach ($headerPages as $page)
                    <a href="{{ route('page', ['slug' => $page->slug]) }}"
                        class="{{ request()->route('slug') === $page->slug ? 'text-primary-500 font-bold' : 'text-gray-700 hover:text-primary-500' }} px-3 py-2 text-sm font-medium transition-colors">
                        {{ $page->titre }}
                    </a>
                @endforeach
                @if (isset($hasForm) && $hasForm)
                    <a href="#contact"
                        class="text-white bg-primary-500 px-4 py-2 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg cursor-pointer  hover:bg-primary-700 text-sm font-medium scroll-smooth flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        Contact
                    </a>
                @endif

            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div x-show="mobileMenuOpen" x-transition class="md:hidden pb-4">
            <div class="flex flex-col space-y-2">
                <a href="{{ url('/') }}"
                    class="{{ request()->is('/') ? 'text-primary-500 font-bold' : 'text-gray-700 hover:text-primary-700' }} px-3 py-2 text-sm font-medium transition-colors">
                    Accueil
                </a>
                @foreach ($headerPages as $page)
                    <a href="{{ route('page', ['slug' => $page->slug]) }}"
                        class="{{ request()->route('slug') === $page->slug ? 'text-primary-500 font-bold' : 'text-gray-700 hover:text-primary-700' }} px-3 py-2 text-sm font-medium transition-colors">
                        {{ $page->titre }}
                    </a>
                @endforeach
                @if (isset($hasForm) && $hasForm)
                    <a href="#contact"
                        class="text-white bg-primary-500 px-4 py-2 rounded-lg text-sm font-medium scroll-smooth flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        Contact
                    </a>
                @endif
            </div>
        </div>
    </div>
</nav>
