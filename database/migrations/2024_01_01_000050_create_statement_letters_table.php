<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('statement_letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('violation_id')->constrained('violations')->onDelete('cascade');
            $table->string('generated_pdf')->nullable();
            $table->string('scanned_signed_file')->nullable();
            $table->text('extracted_text')->nullable();
            $table->timestamps();

            $table->index('violation_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statement_letters');
    }
};
