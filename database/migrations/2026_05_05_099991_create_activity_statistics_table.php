<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | ACTIVITY STATISTICS
        |--------------------------------------------------------------------------
        | Snapshot statistik peserta paid per activity:
        | - symposium digabung sebagai 1 baris umum
        | - workshop per masing-masing activity
        */

        Schema::create('activity_statistics', function (Blueprint $table) {
            $table->id();

            $table->foreignId('event_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------
            | ACTIVITY REFERENCE
            |--------------------------------------------------
            | Untuk symposium umum:
            | - activity_id nullable
            | - activity_type = symposium
            |
            | Untuk workshop:
            | - activity_id terisi
            | - activity_type = workshop
            */
            $table->foreignId('activity_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->enum('activity_type', ['symposium', 'workshop']);

            /*
            |--------------------------------------------------
            | SNAPSHOT SUMMARY
            |--------------------------------------------------
            */
            $table->integer('paid_participants')->default(0);

            /*
            |--------------------------------------------------
            | CACHE TIMESTAMP
            |--------------------------------------------------
            */
            $table->timestamp('calculated_at')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------
            | UNIQUENESS
            |--------------------------------------------------
            | - symposium: unik per event + type
            | - workshop: unik per event + activity
            */
            $table->unique(['event_id', 'activity_id', 'activity_type'], 'activity_statistics_unique_row');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_statistics');
    }
};