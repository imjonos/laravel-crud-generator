<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" type="text/css" href="/css/app.css">
    <script type="text/javascript">
        window.trans = <?php
        $lang_files = File::files(resource_path() . '/lang/' . App::getLocale());
        $trans = [];
        foreach ($lang_files as $f) {
            $filename = pathinfo($f)['filename'];
            $trans[$filename] = trans($filename);
        }
        //пример подключения языков для других пакетов
        //$trans['coders-studio/chat']['chat'] = \Lang::get('coders-studio/chat::chat');
        echo json_encode($trans);
        ?>;
    </script>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark navbar-expand-lg">
        <a class="navbar-brand" href="#">{{ env('APP_NAME') }}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto">
            </ul>
            {{-- <span class="navbar-text">
            </span> --}}
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        @lang('crud.labels.profile')
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        @if(auth()->check())
                            <a class="dropdown-item" href="/logout">@lang('crud.labels.logout')</a>
                        @else
                            <a class="dropdown-item" href="/login">@lang('crud.labels.login')</a>
                        @endif
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container-fluid" id="app">
        <div class="row" v-cloak>
            <div class="col-md-2 bg-white pt-3">
                <ul class="navbar-nav mr-auto">
                    <div class="list-group">
                        @include('codersstudio.crud::layouts.menu')
                    </div>
                </ul>
            </div>
            <div class="col-md-10 pt-3">
                @yield('content')
                <notifications group="system" />
            </div>
        </div>
    </div>
    <script type="text/javascript" src="/js/app.js"></script>
</body>
</html>
