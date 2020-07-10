<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title')@yield('title') - @endif{{ config('app.name', 'Laravel') }}</title>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @stack('head')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-umy shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('images/icon.png') }}" width="30" height="30" class="d-inline-block align-bottom mr-1" alt="{{ config('app.name', 'Laravel') }}">
                    <span>{{ config('app.name', 'Laravel') }}</span>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false"
                        aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                        <span>{{ __('Data') }}</span> <span class="caret"></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-left">
                                        <a class="dropdown-item" href="{{ route('user.index') }}">{{ __('Pengguna') }}</a>
                                        <a class="dropdown-item" href="{{ route('lecturer.index') }}">{{ __('Dosen') }}</a>
                                        <a class="dropdown-item" href="{{ route('activity.index') }}">{{ __('Jenis Kegiatan') }}</a>
                                        <a class="dropdown-item" href="{{ route('participant.index') }}">{{ __('Jenis Peserta') }}</a>
                                        <a class="dropdown-item" href="{{ route('financial.index') }}">{{ __('Jenis Biaya') }}</a>
                                        <a class="dropdown-item" href="{{ route('attachment.index') }}">{{ __('Jenis Lampiran') }}</a>
                                        <a class="dropdown-item" href="{{ route('faculty.index') }}">{{ __('Fakultas') }}</a>
                                        <a class="dropdown-item" href="{{ route('study.index') }}">{{ __('Program Studi') }}</a>
                                        <a class="dropdown-item" href="{{ route('category.index') }}">{{ __('Kategori') }}</a>
                                    </div>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('submission.index') }}">
                                    <span>{{ __('Pengajuan') }}</span>
                                </a>
                            </li>
                        @endauth
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @if(session('notice'))
                <div class="container">
                    <x-alert
                        :dismissible="session('notice')['dismissible'] ?? false"
                        :content="session('notice')['content'] ?? null"
                        :type="session('notice')['type']">
                    </x-alert>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>

    @stack('body')

    <script type="text/javascript">
        $('#delete').on('show.bs.modal', function (e) {
            let refBtn = e.relatedTarget;
            e.currentTarget.querySelector('form').action = refBtn.href;
        }).on('hide.bs.modal', function (e) {
            e.currentTarget.querySelector('form').action = '#';
        });
    </script>
</body>
</html>
