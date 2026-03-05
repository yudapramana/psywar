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
        | ADD EXTRA COLUMNS TO ACTIVITIES
        |--------------------------------------------------------------------------
        */

        Schema::table('activities', function (Blueprint $table) {

            $table->string('moderator')->nullable()->after('title');
            $table->string('lecture')->nullable();
            $table->string('case_presenter')->nullable();
            $table->string('pic')->nullable();
        });


        /*
        |--------------------------------------------------------------------------
        | ACTIVITY SPEAKERS (MULTI)
        |--------------------------------------------------------------------------
        */

        Schema::create('activity_speakers', function (Blueprint $table) {

            $table->id();
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();

            $table->string('name');

            $table->timestamps();
        });


        /*
        |--------------------------------------------------------------------------
        | ACTIVITY PANELISTS (MULTI)
        |--------------------------------------------------------------------------
        */

        Schema::create('activity_panelists', function (Blueprint $table) {

            $table->id();
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();

            $table->string('name');

            $table->timestamps();
        });


        /*
        |--------------------------------------------------------------------------
        | ACTIVITY SPONSORS (MULTI)
        |--------------------------------------------------------------------------
        */

        Schema::create('activity_sponsors', function (Blueprint $table) {

            $table->id();
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();

            $table->string('name');
            $table->string('logo_url')->nullable();

            $table->timestamps();
        });

    }

    public function down(): void
    {

        Schema::table('activities', function (Blueprint $table) {

            $table->dropColumn([
                'moderator',
                'lecture',
                'case_presenter',
                'pic'
            ]);

        });

        Schema::dropIfExists('activity_speakers');
        Schema::dropIfExists('activity_panelists');
        Schema::dropIfExists('activity_sponsors');
    }
};