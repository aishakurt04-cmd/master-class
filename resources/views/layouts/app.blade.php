<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Клуб любителей творчества «ОчУмелые ручки»')</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    @stack('styles')
</head>
<body class="@yield('body-class', '') @guest guest @endguest">
    <div class="header">
        <div class="row grid middle between">
            <div class="logo">
                <img src="{{ asset('img/logo.png') }}" alt="Логотип">
            </div>
            <div class="title">
                Клуб любителей творчества «ОчУмелые ручки»
            </div>
            <div class="auth">
                @auth
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: #00044c; cursor: pointer;">Выход</button>
                    </form>
                    @if(!Auth::user()->isLeader())
                        <a href="{{ route('home') }}" class="btn" style="text-decoration: none;">Главная</a>
                    @else
                        <a href="{{ route('cabinet') }}" class="btn" style="text-decoration: none;">Главная</a>
                    @endif

                @else
                    <a href="{{ route('login') }}">Вход</a>
                @endauth
            </div>
        </div>
    </div>

    <div class="row row--nogutter">
        <div class="line"></div>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 10px; margin: 10px auto; max-width: 1100px;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-error" style="background: #f8d7da; color: #721c24; padding: 10px; margin: 10px auto; max-width: 1100px;">
            {{ session('error') }}
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info" style="background: #d1ecf1; color: #0c5460; padding: 10px; margin: 10px auto; max-width: 1100px;">
            {{ session('info') }}
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    <div class="row row--nogutter">
        <div class="line"></div>
    </div>

    <div class="footer">
        <div class="row">
            <div class="row--small grid between">
                <div class="address">Наш адрес: ул.Мельникайте, 93а</div>
                <div class="tel">Тел: 89615707608</div>
                <div class="copy">(с) Kurtanova, 2026</div>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>