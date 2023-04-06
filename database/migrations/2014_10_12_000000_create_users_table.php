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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('identity_number')->nullable();
            $table->string('phone')->unique();
            $table->timestamp('phone_verified_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('banned_at')->nullable();
            $table->timestamp('unbanned_at')->nullable();
            $table->boolean('is_banned')->default(false);
            $table->text('ban_reason')->nullable();
            $table->string('password');
            $table->string('image')->nullable();
            $table->string('user_type',30)->default('customer')->comment(join(',',\App\Models\User::TYPES));
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
