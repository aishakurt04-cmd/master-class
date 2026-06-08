@extends('layouts.app')

@section('title', 'Редактирование мастер-класса')

@section('content')
<div class="main">
    <div class="row">
        <div class="row--small">
            <form method="POST" action="{{ route('master-class.update', $masterClass->id) }}">
                @csrf
                @method('PUT')
                
                <h2>Редактирование мастер-класса</h2>

                <div class="form-group">
                    <label>Название мастер-класса</label>
                    <input type="text" value="{{ $masterClass->name }}" disabled style="background: #f0f0f0;">
                </div>

                <div class="form-group">
                    <label>Вид творчества</label>
                    <input type="text" value="{{ $masterClass->craft->name }}" disabled style="background: #f0f0f0;">
                </div>

                <div class="form-group">
                    <label>Дата и время</label>
                    <input type="text" value="{{ $masterClass->russian_date}} {{ $masterClass->start_time->format('H:i') }}" disabled style="background: #f0f0f0;">
                </div>

                <div class="form-group">
                    <label>Количество мест</label>
                    <input type="text" value="{{ $masterClass->max_participants }}" disabled style="background: #f0f0f0;">
                </div>

                <div class="form-group">
                    <label>Описание мастер-класса</label>
                    <textarea name="description" required>{{ old('description', $masterClass->description) }}</textarea>
                    @error('description')<small style="color: red;">{{ $message }}</small>@enderror
                </div>

                <div class="form-group">
                    <label>Стоимость мастер-класса (руб.)</label>
                    <input type="number" step="1" name="price" value="{{ old('price', $masterClass->price) }}" min="0" required>
                    @error('price')<small style="color: red;">{{ $message }}</small>@enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn">Сохранить изменения</button>
                    <a href="{{ route('cabinet') }}" class="btn" style="text-decoration: none;">Отмена</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection