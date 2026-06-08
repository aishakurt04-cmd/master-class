@extends('layouts.app')

@section('title', $craft->name)

@section('content')
<div class="main">
    <div class="row">
        <div class="hover"></div>
        <div class="title">{{ $craft->name }}</div>
        <div class="row--small grid between">
            <div class="content">
                @if($craft->image)
                    <img src="{{ asset('img/' . $craft->image) }}" alt="{{ $craft->name }}" style="max-width: 200px; height: auto; margin: 10px;">
                @endif
                {!! nl2br(e($craft->description)) !!}
            </div>
            <ul class="menu">
                @foreach($crafts as $c)
                    <li><a href="{{ route('craft.show', $c->id) }}">{{ $c->name }}</a></li>
                @endforeach
            </ul>
        </div>

        <div class="row shedule">
            <div class="row--small">
                <h2>Расписание</h2>
                <div class="drivers">
                    @forelse($masterClasses as $masterClass)
                        <div class="driver grid">
                            <div class="driver-left grid">
                                <div class="driver-photo">
                                    @if($masterClass->leader->photo)
                                        <img src="{{ asset('img/' . $masterClass->leader->photo) }}" alt="{{ $masterClass->leader->name }}">
                                    @else
                                        <img src="{{ asset('img/driver-page.png') }}" alt="{{ $masterClass->leader->name }}">
                                    @endif
                                </div>
                                <div class="driver-text" style="width:600px">
                                    <div class="driver-name">{{ $masterClass->leader->name }}</div>
                                    <div class="driver-desc">{{ $masterClass->description }}</div>
                                </div>
                            </div>
                            <div class="driver-right">
                                @auth
                                    @if(!Auth::user()->isLeader())
                                        @php
                                            $isRegistered = Auth::user()->registeredMasterClasses->contains($masterClass->id);
                                        @endphp
                                        @if(!$isRegistered && $masterClass->hasFreePlaces())
                                            <a href="{{ route('registration.create', $masterClass->id) }}">
                                                <button class="driver-btn">Записаться</button>
                                            </a>
                                        @elseif($isRegistered)
                                            <button class="driver-btn" disabled style="opacity: 0.5;">вы уже записаны</button>
                                        @else
                                            <button class="driver-btn" disabled style="opacity: 0.5;">мест нет</button>
                                        @endif
                                    @endif
                                @endauth
                                <div class="driver-time">
                                    {{ $masterClass->russian_date }} {{ $masterClass->start_time->format('H:i') }}<br>
                                    Стоимость: {{ number_format($masterClass->price, 0, ',', ' ') }} руб.<br>
                                    Мест: {{ $masterClass->getAvailablePlaces() }}/{{ $masterClass->max_participants }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <p style="color: white; text-align: center;">Нет доступных мастер-классов</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection