@extends('layouts.app')

@section('title', 'Личный кабинет')

@section('body-class', 'dp')

@section('content')
<div class="main">
    <div class="row">
        <div class="hover"></div>
        <div class="title"></div>
        <div class="row--small grid between">
            <div class="content driver-page">
                <div class="driver-page-photo">
                    @if($user->photo)
                        <img src="{{ asset('img/' . $user->photo) }}" alt="{{ $user->name }}">
                    @else
                        <img src="{{ asset('img/driver-page.png') }}" alt="{{ $user->name }}">
                    @endif
                </div>
                <div class="driver-page-name">{{ $user->name }}</div>
                <div class="driver-page-text">
                    <div class="driver-page-my">Мои мастер-классы</div>
                    <table class="driver-page-table">
                        <tbody>
                            @forelse($masterClasses as $masterClass)
                                <tr>
                                    <td>{{ $masterClass->russian_date }} {{ $masterClass->start_time->format('H:i') }}</td>
                                    <td>
                                        <b>{{ $masterClass->name }}</b>
                                        <p>Вид творчества: {{ $masterClass->craft->name }}</p>
                                        <p>Стоимость: {{ $masterClass->price }} руб.</p>
                                        <p>Участники ({{ $masterClass->current_participants }}/{{ $masterClass->max_participants }}):</p>
                                        @foreach($masterClass->participants as $participant)
                                            {{ $loop->iteration }}. {{ $participant->name }}<br>
                                            email: {{ $participant->email }}<br>
                                            tel: {{ $participant->phone }}<br>
                                        @endforeach
                                        <a href="{{ route('master-class.edit', $masterClass->id) }}" class="btn">Редактировать</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="2">У вас пока нет мастер-классов. Создайте первый!</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="driver-page-btn-wrapper">
                    <a href="{{ route('master-class.create') }}">
                        <div class="driver-page-btn btn">Добавить мастер-класс</div>
                    </a>
                </div>
            </div>
            <ul class="menu">
                @foreach(\App\Models\Craft::all() as $craft)
                    <li><a href="{{ route('craft.show', $craft->id) }}">{{ $craft->name }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection