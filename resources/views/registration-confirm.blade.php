@extends('layouts.app')

@section('title', 'Подтверждение записи')

@section('content')
<div class="main">
    <div class="row">
        <div class="row--small">
            <form method="POST" action="{{ route('registration.store', $masterClass->id) }}">
                @csrf
                <h2>Подтверждение записи на мастер-класс</h2>

                <div class="form-group">
                    <label>ФИО пользователя:</label>
                    <p><strong>{{ Auth::user()->name }}</strong></p>
                </div>

                <div class="form-group">
                    <label>Вид творчества:</label>
                    <p><strong>{{ $masterClass->craft->name }}</strong></p>
                </div>

                <div class="form-group">
                    <label>ФИО мастера:</label>
                    <p><strong>{{ $masterClass->leader->name }}</strong></p>
                </div>

                <div class="form-group">
                    <label>Дата и время:</label>
                    <p><strong>{{ $masterClass->date->format('d.m.Y') }} {{ $masterClass->start_time->format('H:i') }}</strong></p>
                </div>

                <div class="form-group">
                    <button type="submit" name="action" value="confirm" class="btn">Подтвердить</button>
                    <button type="submit" name="action" value="cancel" class="btn">Отмена</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection