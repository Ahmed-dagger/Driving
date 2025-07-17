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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learner_id')->constrained('users')->onDelete('cascade');

            // If null = general request for all instructors
            $table->foreignId('instructor_id')->nullable()->constrained('users')->onDelete('set null');

            $table->foreignId('package_id')->nullable()->constrained('packages')->onDelete('set null');

            $table->date('start_date')->nullable();

            $table->string('location_city');
            $table->string('location_area');
            $table->boolean('has_learner_car')->default(false);
            $table->boolean('requires_transport')->default(false);

            $table->decimal('total_price', 10, 2)->default(0);

            $table->enum('type', ['general', 'private']);
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');

            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_requests');
    }
};
