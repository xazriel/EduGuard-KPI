<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_kpis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->unique()->constrained('students')->onDelete('cascade');
            $table->decimal('attendance_score', 5, 2)->default(100);
            $table->decimal('discipline_score', 5, 2)->default(100);
            $table->decimal('social_ethics_score', 5, 2)->default(100);
            $table->decimal('aggression_score', 5, 2)->default(100);
            $table->decimal('integrity_score', 5, 2)->default(100);
            $table->decimal('high_risk_score', 5, 2)->default(100);
            $table->decimal('behavior_trend_score', 5, 2)->default(100);
            $table->decimal('overall_score', 5, 2)->default(100);
            $table->enum('warning_status', ['green', 'yellow', 'red'])->default('green');
            $table->timestamp('updated_at')->nullable();

            $table->index('overall_score');
            $table->index('warning_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_kpis');
    }
};
