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
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('requests')->onDelete('cascade');

            // You could also store instructor per session if needed:
            $table->foreignId('instructor_id')->nullable()->constrained('users')->onDelete('set null');

            $table->date('date');
            $table->time('start_time');
            $table->time('end_time')->nullable();

            $table->decimal('price', 10, 2)->default(0);

            $table->enum('status', ['pending', 'accepted', 'completed', 'canceled', 'rejected'])->default('pending');

            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->decimal('rate', 10, 2)->default(0);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
