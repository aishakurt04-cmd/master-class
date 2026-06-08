@extends('layouts.app')

@section('title', 'Главная')

@section('content')
<div class="main">
    <div class="row">
        <div class="hover"></div>
        <div class="title"></div>
        
        <div class="row--small grid between">
            {{-- ЛЕВАЯ КОЛОНКА --}}
            <div class="content driver-page">
                @auth
                    @if(!Auth::user()->isLeader())
                        <div class="driver-page-my">Мои записи на мастер-классы</div>
                        
                        @if($hasRegistrations)
                            <table class="driver-page-table">
                                <tbody>
                                    @foreach($userRegistrations as $masterClass)
                                        <tr>
                                            <td style="white-space: nowrap;">
                                                {{ $masterClass->russian_date }}<br>
                                                {{ $masterClass->start_time->format('H:i') }}
                                            </td>
                                            <td>
                                                <b>{{ $masterClass->name }}</b>
                                                <p>Вид творчества: {{ $masterClass->craft->name }}</p>
                                                <p>Ведущий: {{ $masterClass->leader->name }}</p>
                                                <p>Стоимость: {{ $masterClass->price }} руб.</p>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p style="color: #20416c; text-align: center; padding: 30px;">
                                Вы пока не записаны ни на один мастер-класс
                            </p>
                        @endif
                    @endif
                @endauth
            </div>
            
            <ul class="menu">
                @foreach($crafts as $craft)
                    <li><a href="{{ route('craft.show', $craft->id) }}">{{ $craft->name }}</a></li>
                @endforeach
            </ul>
        </div>
        
        @guest
            <script>
                window.location.href = "{{ route('craft.show', $crafts->first()->id ?? 1) }}";
            </script>
        @endguest
    </div>
</div>
@endsection