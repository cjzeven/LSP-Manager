<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="{{ url('') }}">LSP Manager</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('living') }}">Living</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('saving') }}">Saving</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('playing') }}">Playing</a>
                    </li>
                </ul>
            </div>
        </nav>

        @yield('content')

    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>