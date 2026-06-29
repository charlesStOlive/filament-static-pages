<div class="w-full">
    <iframe id="{{ $frameId }}" src="{{ $src }}" class="w-full rounded-lg border border-gray-200 bg-white"
        style="height: 240px; min-height: 120px; overflow: hidden;" loading="lazy"
        sandbox="allow-scripts allow-same-origin"></iframe>
</div>

@once
    <script>
        window.__staticPagePreviewResizeListener ??= true;

        window.addEventListener('message', function(event) {
            const data = event.data || {};

            if (data.type !== 'static-page-preview:resize') {
                return;
            }

            if (!data.frameId) {
                return;
            }

            const iframe = document.getElementById(data.frameId);

            if (!iframe) {
                return;
            }

            // Sécurité : on accepte uniquement les messages venant de cette iframe précise.
            if (event.source !== iframe.contentWindow) {
                return;
            }

            const height = Math.max(120, Math.ceil(Number(data.height) || 0));

            iframe.style.height = height + 'px';
        });
    </script>
@endonce
