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
        Schema::table('favorites', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('doctor_id');
            $table->unique(['user_id', 'doctor_id']);
        });

        Schema::table('doctor_profiles', function (Blueprint $table) {
            $table->unique('user_id');
            $table->index('specialty_id');
            $table->index('hospital_id');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->unique('type');
        });

        Schema::table('notification_settings', function (Blueprint $table) {
            $table->unique('user_id');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->index(['doctor_id', 'rating']);
            $table->index('user_id');
        });

        Schema::table('appointment_slots', function (Blueprint $table) {
            $table->index(['doctor_id', 'start_time']);
            $table->unique(['doctor_id', 'start_time', 'end_time']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->unique('appointment_slot_id');
            $table->index(['doctor_id', 'status', 'scheduled_at']);
            $table->index(['patient_id', 'status', 'scheduled_at']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->index(['appointment_id', 'status']);
        });

        Schema::table('payment_methods', function (Blueprint $table) {
            $table->index(['user_id', 'is_default']);
            $table->index('type');
        });

        Schema::table('otps', function (Blueprint $table) {
            $table->index(['user_id', 'is_used']);
            $table->index('expires_at');
        });

        Schema::table('search_history', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('searched_at');
        });

        Schema::table('hospitals', function (Blueprint $table) {
            $table->index(['latitude', 'longitude']);
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->index('user_id');
            $table->index(['latitude', 'longitude']);
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->index(['user_id', 'is_read']);
        });

        Schema::table('appointment_cancellations', function (Blueprint $table) {
            $table->index('appointment_id');
            $table->index('cancelled_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('favorites', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'doctor_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['doctor_id']);
        });

        Schema::table('doctor_profiles', function (Blueprint $table) {
            $table->dropUnique(['user_id']);
            $table->dropIndex(['specialty_id']);
            $table->dropIndex(['hospital_id']);
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropUnique(['type']);
        });

        Schema::table('notification_settings', function (Blueprint $table) {
            $table->dropUnique(['user_id']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['doctor_id', 'rating']);
            $table->dropIndex(['user_id']);
        });

        Schema::table('appointment_slots', function (Blueprint $table) {
            $table->dropUnique(['doctor_id', 'start_time', 'end_time']);
            $table->dropIndex(['doctor_id', 'start_time']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropUnique(['appointment_slot_id']);
            $table->dropIndex(['doctor_id', 'status', 'scheduled_at']);
            $table->dropIndex(['patient_id', 'status', 'scheduled_at']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['appointment_id', 'status']);
        });

        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'is_default']);
            $table->dropIndex(['type']);
        });

        Schema::table('otps', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'is_used']);
            $table->dropIndex(['expires_at']);
        });

        Schema::table('search_history', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['searched_at']);
        });

        Schema::table('hospitals', function (Blueprint $table) {
            $table->dropIndex(['latitude', 'longitude']);
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['latitude', 'longitude']);
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'is_read']);
        });

        Schema::table('appointment_cancellations', function (Blueprint $table) {
            $table->dropIndex(['appointment_id']);
            $table->dropIndex(['cancelled_by']);
        });
    }
};
