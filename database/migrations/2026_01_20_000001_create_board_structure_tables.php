<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // =====================
        // BOARD GROUPS
        // =====================
        Schema::create('board_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Pelindung, Advisor, Organizing Committee, dll
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // =====================
        // BOARD SUB SECTIONS
        // =====================
        Schema::create('board_sub_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_group_id')->constrained()->cascadeOnDelete();
            $table->string('name')->nullable(); // Core, Treasurer, Workshop, dll
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // =====================
        // BOARD MEMBERS
        // =====================
        Schema::create('board_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('board_sub_section_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name');
            $table->string('position')->nullable(); // Chairperson, Vice Chairperson, dll
            $table->string('title')->nullable();    // Sp.JP(K), Ph.D, dll (opsional)
            $table->integer('order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('board_members');
        Schema::dropIfExists('board_sub_sections');
        Schema::dropIfExists('board_groups');
    }
};
