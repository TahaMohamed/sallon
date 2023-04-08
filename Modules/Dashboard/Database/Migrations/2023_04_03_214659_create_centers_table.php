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
        Schema::create('centers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('current_package_id')->nullable()->constrained('packages')->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('added_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->time('opened_at')->nullable();
            $table->time('closed_at')->nullable();
            $table->string('days_off')->nullable();
            $table->string('image')->nullable();
            $table->string('phone',20);
            $table->string('email',50)->nullable();
            $table->string('address')->nullable();
            $table->string('lat', 7)->nullable();
            $table->string('lng', 7)->nullable();
            $table->timestamps();
        });

        Schema::create('center_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('center_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->string('short_description', 500)->nullable();
            $table->text('description')->nullable();
            $table->string('locale')->index();
            $table->unique(['center_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('center_translations');
        Schema::dropIfExists('centers');
    }
};
