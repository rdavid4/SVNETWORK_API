<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{$company->name}}</title>
        <meta name="description" content="{{$company->description}}">

        <!-- Metadatos Open Graph -->
        <meta property="og:title" content="{{$company->name}}" />
        <meta property="og:description" content="{{$company->description}}" />
        <meta property="og:image" content="{{$company->logo_url}}" />
        <meta property="og:url" content="{{ config('app.app_url').'/companies/'.$company->slug }}" />
        <meta property="og:type" content="website" />

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{$company->name}}">
        <meta name="twitter:description" content="{{$company->description}}">
        <meta name="twitter:image" content="{{$company->logo_url}}">

        <!-- Otros metadatos útiles -->
        <meta name="author" content="TheSVNetwork.com">
        <link rel="canonical" href="{{ config('app.app_url').'/companies/'.$company->slug }}">

        <!-- Estilos CSS, Fuentes, etc. -->
    </head>
<body>
    {{header("Location: ".config('app.app_url').'/companies/'.$company->slug )}}
    {{exit;}}
</body>
</html>
