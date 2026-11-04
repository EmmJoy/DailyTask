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
        Schema::create('daily_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('record_date');
            $table->string('category', 32);
            $table->string('title');
            $table->text('notes')->nullable();
            $table->string('mood', 16)->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('direction', 16)->nullable();
            $table->timestamps();

            $table->index(['user_id', 'record_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_records');
    }
};
