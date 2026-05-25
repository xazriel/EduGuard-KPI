<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nis', 20)->unique();
            $table->string('nisn', 20)->unique()->nullable();
            $table->string('full_name');
            $table->enum('gender', ['L', 'P']);
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('parent_name')->nullable();
            $table->text('address')->nullable();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->enum('status', ['active', 'inactive', 'graduated'])->default('active');
            $table->timestamps();

            $table->index(['nis', 'nisn']);
            $table->index('class_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
