@extends('layouts.app')

@section('title', 'Добавление мастер-класса')

@section('content')
<div class="main">
    <div class="row">
        <div class="row--small">
            <form method="POST" action="{{ route('master-class.store') }}" id="masterClassForm">
                @csrf
                <h2>Форма добавления мастер-класса</h2>

                <div class="form-group">
                    <label>Вид творчества</label>
                    <select name="craft_id" required>
                        <option value="">Выберите вид</option>
                        @foreach($crafts as $craft)
                            <option value="{{ $craft->id }}" {{ old('craft_id') == $craft->id ? 'selected' : '' }}>{{ $craft->name }}</option>
                        @endforeach
                    </select>
                    @error('craft_id')<small style="color: red;">{{ $message }}</small>@enderror
                </div>

                <div class="form-group">
                    <label>Название мастер-класса</label>
                    <input type="text" name="name" value="{{ old('name') }}" required>
                    @error('name')<small style="color: red;">{{ $message }}</small>@enderror
                </div>

                <div class="form-group">
                    <label>Описание мастер-класса</label>
                    <textarea name="description" required>{{ old('description') }}</textarea>
                    @error('description')<small style="color: red;">{{ $message }}</small>@enderror
                </div>

                <div class="form-group">
                    <label>Дата</label>
                    <input type="date" name="date" id="dateInput" value="{{ old('date', $selectedDate ?? '') }}" required>
                    @error('date')<small style="color: red;">{{ $message }}</small>@enderror
                </div>

                <div class="form-group">
                    <label>Время</label>
                    <select name="start_time" id="startTimeSelect" required>
                        <option value="">Выберите время</option>
                        @foreach($timeSlots as $value => $label)
                            <option value="{{ $value }}" 
                                {{ old('start_time') == $value ? 'selected' : '' }}
                                @if(in_array($value, $occupiedSlots ?? [])) disabled style="background:#ccc;" @endif>
                                {{ $label }} @if(in_array($value, $occupiedSlots ?? [])) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('start_time')<small style="color: red;">{{ $message }}</small>@enderror
                </div>

                <div class="form-group">
                    <label>Количество человек в группе</label>
                    <input type="number" name="max_participants" value="{{ old('max_participants', 10) }}" min="1" max="30" required>
                    @error('max_participants')<small style="color: red;">{{ $message }}</small>@enderror
                </div>

                <div class="form-group">
                    <label>Стоимость мастер-класса (руб.)</label>
                    <input type="number" step="1" name="price" value="{{ old('price') }}" min="0" required>
                    @error('price')<small style="color: red;">{{ $message }}</small>@enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn">Отправить</button>
                    <a href="{{ route('cabinet') }}" class="btn" style="text-decoration: none;">Отмена</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const dateInput = document.getElementById('dateInput');
    const startTimeSelect = document.getElementById('startTimeSelect');

    async function updateTimeSlots(date) {
        if (!date) return;
        try {
            const response = await fetch(`/master-class/available-slots?date=${date}`);
            const data = await response.json();
            
            // Обновляем options в select
            const timeSlots = {
                '09:00': '9:00 - 11:00',
                '11:00': '11:00 - 13:00',
                '13:00': '13:00 - 15:00',
                '15:00': '15:00 - 17:00'
            };
            
            let optionsHtml = '<option value="">Выберите время</option>';
            for (const [value, label] of Object.entries(timeSlots)) {
                const isAvailable = data.available.includes(value);
                optionsHtml += `<option value="${value}" ${isAvailable ? '' : 'disabled style="background:#ccc;"'}>
                                    ${label}
                                </option>`;
            }
            startTimeSelect.innerHTML = optionsHtml;
            
            if (data.allSlotsOccupied) {
                alert('На выбранную дату все временные слоты заняты. Пожалуйста, выберите другую дату.');
                dateInput.value = '';
            }
        } catch (error) {
            console.error('Ошибка загрузки слотов:', error);
        }
    }

    dateInput.addEventListener('change', function() {
        updateTimeSlots(this.value);
    });
</script>
@endpush
@endsection