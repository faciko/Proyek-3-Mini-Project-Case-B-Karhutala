<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('fire_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('area_id')->constrained()->restrictOnDelete();
            $table->foreignId('reviewer_id')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->string('title', 120);
            $table->text('description');
            $table->dateTime('occurred_at');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('condition_level', 20);
            $table->string('status', 30)->default('submitted');
            $table->string('evidence_path')->nullable();
            $table->text('review_note')->nullable();
            $table->string('forwarded_to')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fire_report');
    }
};
