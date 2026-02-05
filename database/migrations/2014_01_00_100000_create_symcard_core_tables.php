<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('service_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->text('encrypted_token')->nullable();
            $table->timestamps();

            $table->unique('name');
        });

        /*
        |--------------------------------------------------------------------------
        | GALLERY
        |--------------------------------------------------------------------------
        */
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();

            $table->string('title')->nullable();
            $table->text('description')->nullable();

            $table->string('image_path'); // path image
            $table->string('thumbnail_path')->nullable();

            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | EMAIL VERIFICATIONS
        |--------------------------------------------------------------------------
        */
        Schema::create('email_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('otp', 6);
            $table->timestamp('expires_at');
            $table->boolean('verified')->default(false);
            $table->timestamps();
        });


        /*
        |--------------------------------------------------------------------------
        | ROLES
        |--------------------------------------------------------------------------
        */
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // SUPERADMIN, ADMIN, REVIEWER, USER
            $table->string('slug')->unique(); // admin, superadmin, petugas_tahapan, ...
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        */
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->foreignId('role_id')->constrained()->cascadeOnUpdate();
            $table->boolean('can_multiple_role')->default(false); // Allow multiple roles
            $table->rememberToken();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | PARTICIPANT CATEGORIES
        |--------------------------------------------------------------------------
        */
        Schema::create('participant_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | PARTICIPANTS
        |--------------------------------------------------------------------------
        */
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete()
                ->unique();

            $table->string('nik')->nullable();
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('institution')->nullable();

            $table->foreignId('participant_category_id')->constrained();
            $table->enum('registration_type', ['sponsored', 'non_sponsored'])
                ->default('non_sponsored');

            $table->timestamps();
            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | UNIQUE CONSTRAINT (SOFT DELETE SAFE)
            |--------------------------------------------------------------------------
            | Kombinasi dengan deleted_at supaya:
            | - Data aktif harus unik
            | - Data yang di-soft-delete boleh dipakai ulang
            */

            $table->unique(['nik', 'deleted_at'], 'participants_nik_unique');
            $table->unique(['mobile_phone', 'deleted_at'], 'participants_mobile_unique');
            $table->unique(['email', 'deleted_at'], 'participants_email_unique');
        });

        /*
        |--------------------------------------------------------------------------
        | EVENTS
        |--------------------------------------------------------------------------
        */
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            // =========================
            // BASIC EVENT INFO
            // =========================
            $table->string('name');               // SYMCARD 2026
            $table->string('slug')->unique();     // symcard-2026
            $table->string('theme')->nullable();
            $table->text('description')->nullable();

            // =========================
            // EVENT DATE
            // =========================
            $table->date('start_date');           // 06 June 2026
            $table->date('end_date');             // 08 June 2026
            $table->date('early_bird_end_date')->nullable();

            // =========================
            // SUBMISSION TIMELINE
            // =========================
            $table->timestamp('submission_open_at')->nullable()
                ->comment('When abstract/case submission opens');

            $table->timestamp('submission_deadline_at')->nullable()
                ->comment('Deadline for abstract/case submission');

            $table->timestamp('notification_date')->nullable()
                ->comment('Acceptance notification date');

            $table->timestamp('submission_close_at')->nullable()
                ->comment('Hard close submission (optional)');

            // =========================
            // SUBMISSION CONTROL
            // =========================
            $table->boolean('submission_is_active')->default(true)
                ->comment('Admin override submission open/close');

            // =========================
            // LOCATION
            // =========================
            $table->string('location')->nullable();
            $table->string('venue')->nullable();

            // =========================
            // STATUS
            // =========================
            $table->boolean('is_active')->default(false);

            $table->timestamps();
        });



        /*
        |--------------------------------------------------------------------------
        | EVENT DAYS
        |--------------------------------------------------------------------------
        */
        Schema::create('event_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->string('label')->nullable(); // Hari-1, Hari-2
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | ROOMS
        |--------------------------------------------------------------------------
        */
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // BallRoom, Ruang Mulia 9
            $table->enum('category', ['symposium', 'workshop', 'jeopardy']);
            $table->integer('capacity')->nullable();
            $table->timestamps();
        });



        /*
        |--------------------------------------------------------------------------
        | ACTIVITIES (SYMPOSIUM / WORKSHOP / ETC)
        |--------------------------------------------------------------------------
        */
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();

            $table->enum('category', [
                'plenary',
                'symposium',
                'workshop',
                'jeopardy',
                'poster'
            ]);

            $table->string('code')->nullable(); // SYM-01, WS-01
            $table->text('title');
            $table->text('description')->nullable();

            $table->boolean('is_paid')->default(true);
            $table->integer('quota')->nullable();

            $table->timestamps();
        });

        Schema::create('activity_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();

            $table->string('title');
            $table->enum('type', ['lecture', 'case', 'video', 'discussion']);
            $table->integer('order')->default(1);

            $table->timestamps();
        });



        /*
        |--------------------------------------------------------------------------
        | SESSIONS
        |--------------------------------------------------------------------------
        */
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_day_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();

            $table->time('start_time');
            $table->time('end_time');

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | FACULTIES (SPEAKERS / INSTRUCTORS)
        |--------------------------------------------------------------------------
        */
        Schema::create('faculties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('institution')->nullable();
            $table->string('specialty')->nullable();
            $table->timestamps();
        });


        /*
        |--------------------------------------------------------------------------
        | ACTIVITY FACULTIES (PIVOT)
        |--------------------------------------------------------------------------
        */
        Schema::create('activity_faculties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('faculty_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['speaker', 'moderator', 'instructor']);
            $table->timestamps();
        });

        Schema::create('topic_faculties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_topic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('faculty_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['speaker', 'moderator']);
            $table->timestamps();
        });



        /*
        |--------------------------------------------------------------------------
        | PAPER TYPES
        |--------------------------------------------------------------------------
        */
        Schema::create('paper_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // ABSTRACT, CASE
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });


        /*
        |--------------------------------------------------------------------------
        | PAPERS
        |--------------------------------------------------------------------------
        */
        Schema::create('papers', function (Blueprint $table) {
            $table->id();
            // 🔑 UUID PUBLIC IDENTIFIER
            $table->uuid('uuid')
                ->unique()
                ->comment('Public UUID for paper (safe for URL / API)');

            $table->foreignId('participant_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('paper_type_id')
                ->constrained()
                ->restrictOnDelete();

            // Title sesuai guideline (≤ 300 chars)
            $table->string('title', 300);

            // Abstract (≤ 300 words, validated at application level)
            $table->text('abstract')
                ->comment('Abstract content, maximum 300 words');


            // =========================
            // FILE (GOOGLE DRIVE)
            // =========================
            $table->text('gdrive_link')
                ->comment('Google Drive file link (docx/pdf)');

            $table->enum('file_type', ['docx', 'pdf'])
                ->default('docx');

            // Workflow status
            $table->enum('status', [
                'draft',
                'submitted',
                'under_review',
                'accepted',
                'rejected',
                'withdrawn'
            ])->default('submitted');

            // =========================
            // FINAL PRESENTATION STATUS (PUBLIC)
            // =========================
            $table->enum('final_status', [
                'oral_presentation',
                'poster_presentation',
            ])->nullable()
            ->comment('Final presentation result after review');

            // =========================
            // TIMESTAMPS
            // =========================
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('finalized_at')->nullable();

            $table->timestamps();

            $table->index('uuid');
        });


        /*
        |--------------------------------------------------------------------------
        | PAPER AUTHORS
        |--------------------------------------------------------------------------
        */
        Schema::create('paper_authors', function (Blueprint $table) {
            $table->id();

            $table->foreignId('paper_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('affiliation')->nullable();

            // 🔑 Peran penulis
            $table->boolean('is_corresponding')->default(false);
            $table->boolean('is_presenting')->default(false);

            // Urutan penulis (First author, second, etc)
            $table->unsignedSmallInteger('order')->default(1);

            $table->timestamps();
        });


        /*
        |--------------------------------------------------------------------------
        | PRICING ITEMS
        |--------------------------------------------------------------------------
        */
        Schema::create('pricing_items', function (Blueprint $table) {
            $table->id();

            // WHO
            $table->foreignId('participant_category_id')
                ->constrained()
                ->cascadeOnDelete();

            // WHEN (Early / Late)
            $table->enum('bird_type', ['early', 'late']);

            // WHAT
            $table->boolean('includes_symposium')->default(true);
            $table->unsignedTinyInteger('workshop_count')->default(0); // 0,1,2

            // PRICE
            $table->decimal('price', 12, 2);

            $table->timestamps();

            // 🔒 Prevent duplicate price rules
            $table->unique([
                'participant_category_id',
                'bird_type',
                'workshop_count'
            ], 'unique_pricing_rule');
        });

        /*
        |-------------------------------------------------------------------------- 
        | BANKS
        |-------------------------------------------------------------------------- 
        */
        Schema::create('banks', function (Blueprint $table) {
            $table->id();

            // IDENTITAS BANK
            $table->string('code', 20)->unique();   // BSI, BRI, BNI, BCA, MANDIRI
            $table->string('name');                 // Bank Syariah Indonesia

            // REKENING TUJUAN
            $table->string('account_number');       // 1234567890
            $table->string('account_name');         // Kelakar Unand

            // BIAYA ADMIN (OPSIONAL / FUTURE)
            $table->decimal('admin_fee', 12, 2)->default(0);

            // UI & STATUS
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);

            $table->timestamps();
        });



        /*
        |--------------------------------------------------------------------------
        | REGISTRATIONS
        |--------------------------------------------------------------------------
        */
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('event_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('participant_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('pricing_item_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('bank_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->enum('payment_step', [
                'choose_bank',
                'waiting_transfer',
                'waiting_verification',
                'paid'
            ])->default('choose_bank');

            /*
            |----------------------------------------------------------
            | PAYMENT STATUS
            |----------------------------------------------------------
            */
            $table->enum('status', ['pending', 'paid', 'cancelled'])
                ->default('pending');

            /*
            |----------------------------------------------------------
            | PAYMENT AMOUNT
            |----------------------------------------------------------
            | total_amount = harga paket
            | unique_code  = kode unik transfer (3 digit)
            | total transfer = total_amount + unique_code
            */
            $table->decimal('total_amount', 12, 2);

            $table->unsignedSmallInteger('unique_code')->nullable()
                ->comment('Unique transfer code for manual bank transfer verification');

            $table->timestamps();
        });



        /*
        |--------------------------------------------------------------------------
        | REGISTRATION ITEMS
        |--------------------------------------------------------------------------
        */
        Schema::create('registration_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('registration_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('activity_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('activity_type', ['symposium', 'workshop']);

            $table->timestamps();
        });


        /*
        |--------------------------------------------------------------------------
        | PAYMENTS
        |--------------------------------------------------------------------------
        */
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('registration_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('payment_method');
            $table->decimal('amount', 12, 2);

            $table->string('proof_file')->nullable();

            $table->enum('status', ['pending', 'verified', 'rejected'])
                ->default('pending');

            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });


        /*
        |--------------------------------------------------------------------------
        | PAYMENT VERIFICATIONS
        |--------------------------------------------------------------------------
        */
        Schema::create('payment_verifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('payment_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('verified_by')
                ->constrained('users');

            $table->enum('action', ['approve', 'reject']);

            $table->timestamp('verified_at')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });


    }

    public function down(): void
    {
        Schema::dropIfExists('payment_verifications');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('registration_items');
        Schema::dropIfExists('registrations');
        Schema::dropIfExists('pricing_items');
        Schema::dropIfExists('paper_authors');
        Schema::dropIfExists('papers');
        Schema::dropIfExists('paper_types');
        Schema::dropIfExists('session_faculties');
        Schema::dropIfExists('faculties');
        Schema::dropIfExists('faculty_types');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('activities');
        Schema::dropIfExists('event_days');
        Schema::dropIfExists('events');
        Schema::dropIfExists('participants');
        Schema::dropIfExists('participant_categories');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }
};
