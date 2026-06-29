<footer class="bg-gray-900 text-gray-400 py-8 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        @php $settings = app(\App\Settings\AdminSettings::class); @endphp
        <p class="text-sm">{{ $settings->footerText }}</p>
    </div>
</footer>
