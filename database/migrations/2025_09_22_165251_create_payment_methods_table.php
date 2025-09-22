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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['card', 'paypal', 'apple_pay']);
            $table->string('provider')->nullable();                // e.g. 'Visa', 'MasterCard', 'PayPal'
            $table->string('provider_method_id')->nullable();     // gateway token / payment method id (PCI safe)
            $table->string('last_four', 4)->nullable();           // last 4 digits (optional)
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
