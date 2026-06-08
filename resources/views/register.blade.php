@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
<div class="main">
    <div class="row">
        <div class="row--small">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <h2>Форма регистрации</h2>

                <div class="form-group">
                    <label>ФИО</label>
                    <input type="text" name="name" value="{{ old('name') }}" required>
                    @error('name')<small style="color: red;">{{ $message }}</small>@enderror
                </div>

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
                    <label>Номер телефона</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="88005553555" required>
                    @error('phone')<small style="color: red;">{{ $message }}</small>@enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn">Отправить</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection