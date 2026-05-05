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
        | PACKAGE STATISTICS
        |--------------------------------------------------------------------------
        | Snapshot statistik peserta paid per tipe package
        | berdasarkan logika pricing_items:
        | - Workshop for Nurse
        | - Symposium
        | - Symposium + 1 Workshop
        | - Symposium + 2 Workshops
        | dst
        */

        Schema::create('package_statistics', function (Blueprint $table) {
            $table->id();

            $table->foreignId('event_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------
            | PACKAGE SIGNATURE
            |--------------------------------------------------
            | package_code disimpan stabil untuk query/filter dashboard
            */
            $table->string('package_code', 50);
            $table->string('package_label');

            $table->boolean('includes_symposium')->default(false);
            $table->unsignedInteger('workshop_count')->default(0);

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

            $table->unique(['event_id', 'package_code'], 'package_statistics_event_package_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_statistics');
    }
};