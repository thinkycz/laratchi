@php
    $config = Tomchochola\Laratchi\Config\Config::inject();

    $appName = $config->appName();
@endphp

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>

    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <meta name="author" content="Tomáš Chochola <chocholatom1997@gmail.com>"/>

    <meta name="robots" content="noindex, nofollow"/>

    <title>{{ $appName }}</title>

    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin="anonymous"/>

    <link rel="icon" type="image/png" href="https://cdn.jsdelivr.net/npm/swagger-ui-dist/favicon-32x32.png" sizes="32x32" crossorigin="anonymous"/>
    <link rel="icon" type="image/png" href="https://cdn.jsdelivr.net/npm/swagger-ui-dist/favicon-16x16.png" sizes="16x16" crossorigin="anonymous"/>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swagger-ui-dist/index.min.css" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swagger-ui-dist/swagger-ui.min.css" crossorigin="anonymous"/>

    <script src="https://cdn.jsdelivr.net/npm/swagger-ui-dist/swagger-ui-bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/swagger-ui-dist/swagger-ui-standalone-preset.min.js" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.ui = SwaggerUIBundle({
                url: {!! isset($url) ? "'{$url}'" : 'void 0' !!},
                spec: {!! isset($spec) ? "'{$spec}'" : 'void 0' !!},
                dom_id: '#swagger',
                deepLinking: true,
                presets: [SwaggerUIBundle.presets.apis, SwaggerUIStandalonePreset],
                plugins: [SwaggerUIBundle.plugins.DownloadUrl],
                layout: 'StandaloneLayout',
            });
        });
    </script>
</head>
<body>
    <div id="swagger"></div>
</body>
</html>
