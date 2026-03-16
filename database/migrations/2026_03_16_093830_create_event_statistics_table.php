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
        | EVENT STATISTICS (DASHBOARD SUMMARY)
        |--------------------------------------------------------------------------
        | Snapshot statistik dashboard untuk mempercepat query
        */

        Schema::create('event_statistics', function (Blueprint $table) {
            $table->id();

            $table->foreignId('event_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------
            | PARTICIPANT SUMMARY
            |--------------------------------------------
            */
            $table->integer('total_participants')->default(0);
            $table->integer('paid_participants')->default(0);
            $table->integer('unpaid_participants')->default(0);

            /*
            |--------------------------------------------
            | REGISTRATION SUMMARY
            |--------------------------------------------
            */
            $table->integer('total_registrations')->default(0);
            $table->integer('already_registered')->default(0);
            $table->integer('not_yet_registered')->default(0);

            /*
            |--------------------------------------------
            | CACHE TIMESTAMP
            |--------------------------------------------
            */
            $table->timestamp('calculated_at')->nullable();

            $table->timestamps();

            $table->unique('event_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_statistics');
    }
};