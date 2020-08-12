<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LSP &raquo; @yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="{{ url('') }}">LSP Manager</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <?php 
                $__activeNav = function($name) {
                    return strpos(url()->current(), $name) !== false ? 'active' : '';
                };
            ?>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item <?= $__activeNav('/living') ?>">
                        <a class="nav-link" href="{{ url('living') }}">Living</a>
                    </li>
                    <li class="nav-item <?= $__activeNav('/saving') ?>">
                        <a class="nav-link" href="{{ url('saving') }}">Saving</a>
                    </li>
                    <li class="nav-item <?= $__activeNav('/playing') ?>">
                        <a class="nav-link" href="{{ url('playing') }}">Playing</a>
                    </li>
                </ul>
            </div>
        </nav>

        @yield('content')

    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.19/lodash.min.js"></script>
</body>
</html>