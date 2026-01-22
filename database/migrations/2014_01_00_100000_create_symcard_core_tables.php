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
            $table->string('name')->unique();
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
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->foreignId('role_id')->constrained()->cascadeOnUpdate();
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
            $table->string('nik')->nullable()->index();
            $table->string('full_name');
            $table->string('email')->nullable()->index();
            $table->string('mobile_phone')->nullable();
            $table->string('institution')->nullable();
            $table->foreignId('participant_category_id')->constrained();
            $table->enum('registration_type', ['sponsored', 'non_sponsored'])->default('non_sponsored');
            $table->timestamps();
            $table->softDeletes();
        });

        /*
        |--------------------------------------------------------------------------
        | EVENTS
        |--------------------------------------------------------------------------
        */
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('theme')->nullable();
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('location')->nullable();
            $table->string('venue')->nullable();
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
            $table->string('name');
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | PAPERS
        |--------------------------------------------------------------------------
        */
        Schema::create('papers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('paper_type_id')->constrained();
            $table->string('title');
            $table->string('file_path');
            $table->enum('status', ['draft', 'submitted', 'reviewed', 'accepted', 'rejected'])->default('submitted');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | PAPER AUTHORS
        |--------------------------------------------------------------------------
        */
        Schema::create('paper_authors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paper_id')->constrained()->cascadeOnDelete();
            $table->string('author_name');
            $table->string('affiliation')->nullable();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | PRICING ITEMS
        |--------------------------------------------------------------------------
        */
        Schema::create('pricing_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_category_id')->constrained();
            $table->string('package_type');
            $table->integer('workshop_quota')->nullable();
            $table->decimal('price', 12, 2);
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | REGISTRATIONS
        |--------------------------------------------------------------------------
        */
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('participant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pricing_item_id')->constrained();
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->decimal('total_amount', 12, 2);
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | REGISTRATION ITEMS
        |--------------------------------------------------------------------------
        */
        Schema::create('registration_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->cascadeOnDelete();
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
            $table->string('activity_type');
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | PAYMENTS
        |--------------------------------------------------------------------------
        */
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->cascadeOnDelete();
            $table->string('payment_method');
            $table->decimal('amount', 12, 2);
            $table->string('proof_file')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
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
            $table->foreignId('payment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('verified_by')->constrained('users');
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
