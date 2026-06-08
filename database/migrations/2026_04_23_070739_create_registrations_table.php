<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Пользователь
            $table->foreignId('master_class_id')->constrained()->onDelete('cascade'); // Мастер-класс
            $table->string('status')->default('confirmed');
            $table->timestamps();
            
            // Запрещаем повторную запись на один мастер-класс
            $table->unique(['user_id', 'master_class_id']);
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
