<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('category'); // kehadiran, kedisiplinan, etika_sosial, agresivitas, integritas, risiko_tinggi
            $table->string('sub_category');
            $table->text('description')->nullable();
            $table->enum('severity', ['ringan', 'sedang', 'berat'])->default('ringan');
            $table->date('violation_date');
            $table->text('follow_up')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->index(['student_id', 'violation_date']);
            $table->index('category');
            $table->index('severity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('violations');
    }
};
