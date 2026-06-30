<div>
    {{-- Message de succès --}}
    @if ($success)
        <div wire:key="contact-success-message"
            class="mb-6 p-4  bg-green-100 border border-green-500 rounded-lg fade-in-up">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-2 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>

                    <span class="text-green-800 font-medium">
                        {{ $successMessage }}
                    </span>
                </div>

                <button type="button" wire:click="hideSuccess" class="text-green-600 hover:text-green-800"
                    aria-label="Fermer le message de succès">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- Erreur de throttling --}}
    @error('throttle')
        <div wire:key="contact-throttle-error" class="mb-6 p-4 bg-red-100 border border-error-500 rounded-lg fade-in-up">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-600 mr-2 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>

                <span class="text-red-800">
                    {{ $message }}
                </span>
            </div>
        </div>
    @enderror

    {{-- Erreur d'envoi --}}
    @error('send')
        <div wire:key="contact-send-error" class="mb-6 p-4 bg-red-100 border border-error-500 rounded-lg fade-in-up">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-600 mr-2 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>

                <span class="text-red-800">
                    {{ $message }}
                </span>
            </div>
        </div>
    @enderror

    <form wire:submit="submit" class="space-y-6">
        {{-- Prénom et Nom --}}
        <div class="grid md:grid-cols-2 gap-4 fade-in-up">
            <div>
                <label for="prenom" class="block font-medium text-secondary-600 mb-2">
                    Prénom
                </label>

                <input type="text" id="prenom" wire:model="prenom"
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 transition-colors @error('prenom') border-error-500 focus:ring-error-500 @else border-secondary-300 focus:ring-secondary-500 @enderror"
                    placeholder="Votre prénom">

                @error('prenom')
                    <p class="mt-1 text-sm text-error-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="nom" class="block font-medium text-secondary-600 mb-2">
                    Nom
                </label>

                <input type="text" id="nom" wire:model="nom"
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 transition-colors @error('nom') border-error-500 focus:ring-error-500 @else border-secondary-300 focus:ring-secondary-500 @enderror"
                    placeholder="Votre nom">

                @error('nom')
                    <p class="mt-1 text-sm text-error-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        {{-- Email --}}
        <div class="fade-in-up" data-animation-delay="100">
            <label for="email" class="block font-medium text-secondary-600 mb-2">
                Email <span class="text-secondary-700">*</span>
            </label>

            <input type="email" id="email" wire:model="email"
                class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 transition-colors @error('email') border-error-500 focus:ring-error-500 @else border-secondary-300 focus:ring-secondary-500 @enderror"
                placeholder="votre@email.com">

            @error('email')
                <p class="mt-1 text-sm text-error-500">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Téléphone --}}
        <div class="fade-in-up" data-animation-delay="200">
            <label for="telephone" class="block font-medium text-secondary-600 mb-2">
                Téléphone
            </label>

            <input type="tel" id="telephone" wire:model="telephone"
                class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 transition-colors @error('telephone') border-error-500 focus:ring-error-500 @else border-secondary-300 focus:ring-secondary-500 @enderror"
                placeholder="+33 6 12 34 56 78">

            @error('telephone')
                <p class="mt-1 text-sm text-error-500">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Objet --}}
        <div class="fade-in-up" data-animation-delay="300">
            <label for="objet" class="block font-medium text-secondary-600 mb-2">
                Objet <span class="text-secondary-700">*</span>
            </label>

            <input type="text" id="objet" wire:model="objet"
                class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 transition-colors @error('objet') border-error-500 focus:ring-error-500 @else border-secondary-300 focus:ring-secondary-500 @enderror"
                placeholder="Sujet de votre message">

            @error('objet')
                <p class="mt-1 text-sm text-error-500">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Message --}}
        <div class="fade-in-up" data-animation-delay="400">
            <label for="content" class="block font-medium text-secondary-600 mb-2">
                Message <span class="text-secondary-700">*</span>
            </label>

            <textarea id="content" wire:model="content" rows="4"
                class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 transition-colors resize-none @error('content') border-error-500 focus:ring-error-500 @else border-secondary-300 focus:ring-secondary-500 @enderror"
                placeholder="Parlez-moi de vos attentes et de vos objectifs..."></textarea>

            @error('content')
                <p class="mt-1 text-sm text-error-500">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Bouton d'envoi --}}
        <div class="fade-in-up" data-animation-delay="500">
            <button type="submit" wire:loading.attr="disabled" wire:target="submit"
                class="w-full btn-base relative inline-flex items-center justify-center rounded bg-secondary-500 px-4 py-2 text-white pl-10 disabled:opacity-70 disabled:cursor-not-allowed">
                <svg wire:loading wire:target="submit" class="animate-spin h-5 w-5 absolute left-3"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>

                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>

                <span>
                    <span wire:loading.remove wire:target="submit">
                        Envoyer le message
                    </span>

                    <span wire:loading wire:target="submit">
                        Envoi en cours…
                    </span>
                </span>
            </button>
        </div>

        {{-- Information --}}
        <p class="text-sm text-gray-500 text-center fade-in-up" data-animation-delay="600">
            Les champs marqués d'un <span class="text-secondary-700">*</span> sont obligatoires.
        </p>
    </form>
</div>
