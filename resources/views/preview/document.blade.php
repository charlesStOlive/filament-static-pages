<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSS FRONT uniquement --}}
    <script>
        window.__previewMode = true
    </script>
    @livewireStyles
    @vite(['resources/css/front/front.css', 'resources/js/front/index.js'])

    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            background: white;
            overflow: hidden;
        }

        body {
            width: 100%;
        }
    </style>
</head>

<body x-data="frontApp()">
    @include($blockView, [
        'block' => $block,
        'mode' => $mode,
        'page' => $page,
    ])
    @livewireScripts
    <script>
        (() => {
            const frameId = @js($frameId);
            const parentOrigin = @js($parentOrigin);

            let lastHeight = 0;

            const getHeight = () => {
                const body = document.body;
                const html = document.documentElement;

                return Math.ceil(Math.max(
                    body.scrollHeight,
                    body.offsetHeight,
                    html.clientHeight,
                    html.scrollHeight,
                    html.offsetHeight
                ));
            };

            const sendHeight = () => {
                requestAnimationFrame(() => {
                    const height = getHeight();

                    if (Math.abs(height - lastHeight) < 2) {
                        return;
                    }

                    lastHeight = height;

                    window.parent.postMessage({
                        type: 'static-page-preview:resize',
                        frameId,
                        height,
                    }, parentOrigin);
                });
            };

            const observer = new ResizeObserver(sendHeight);

            observer.observe(document.documentElement);
            observer.observe(document.body);

            window.addEventListener('load', sendHeight);

            if (document.fonts && document.fonts.ready) {
                document.fonts.ready.then(sendHeight);
            }

            // Sécurise les cas images / polices / layout async.
            setTimeout(sendHeight, 50);
            setTimeout(sendHeight, 250);
            setTimeout(sendHeight, 750);
            setTimeout(sendHeight, 1500);

            sendHeight();
        })();
    </script>
</body>

</html>
