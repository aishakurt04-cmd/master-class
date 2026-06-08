@extends('layouts.app')

@section('title', 'Вход')

@section('content')
<div class="main">
    <div class="row">
        <div class="row--small">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <h2>Вход в личный кабинет</h2>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                    @error('email')<small style="color: red;">{{ $message }}</small>@enderror
                </div>

                <div class="form-group">
                    <label>Пароль</label>
                    <input type="password" name="password" required>
                    @error('password')<small style="color: red;">{{ $message }}</small>@enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn">Войти</button>
                </div>

                <p>Нет аккаунта? <a href="{{ route('register') }}">Зарегистрироваться</a></p>
            </form>
        </div>
    </div>
</div>
@endsection