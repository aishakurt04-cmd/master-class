<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('master_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('craft_id')->constrained()->onDelete('cascade'); // Связь с видом творчества
            $table->foreignId('leader_id')->constrained('users')->onDelete('cascade'); // Связь с ведущим
            $table->string('name'); // Название мастер-класса
            $table->text('description'); // Описание
            $table->date('date'); // Дата проведения
            $table->time('start_time'); // Время начала (9:00, 11:00, 13:00, 15:00)
            $table->time('end_time'); // Время окончания (+2 часа)
            $table->integer('max_participants'); 
            $table->integer('current_participants')->default(0); 
            $table->unsignedInteger('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_classes');
    }
};
