<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('city_id');
            $table->string('title', 150);
            $table->string('slug', 180)->unique();
            $table->text('description');
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('is_negotiable')->default(false);
            // Using string instead of enum for broader MySQL compatibility
            $table->string('condition', 20)->default('good');
            $table->string('status', 20)->default('active');
            // Using longText instead of json for MySQL < 5.7.8 compatibility
            $table->longText('images')->nullable();
            $table->unsignedBigInteger('views_count')->default(0);
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->cascadeOnDelete();

            $table->foreign('category_id')
                  ->references('id')->on('categories')
                  ->restrictOnDelete();

            $table->foreign('city_id')
                  ->references('id')->on('cities')
                  ->restrictOnDelete();

            // Performance indexes
            $table->index('status');
            $table->index('price');
            $table->index('created_at');
            // NOTE: fullText() removed — not supported on all MySQL/XAMPP versions
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
