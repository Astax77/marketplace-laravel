<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('announcement_id');
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('seller_id');
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();

            $table->foreign('announcement_id')
                  ->references('id')->on('announcements')
                  ->cascadeOnDelete();

            $table->foreign('buyer_id')
                  ->references('id')->on('users')
                  ->cascadeOnDelete();

            $table->foreign('seller_id')
                  ->references('id')->on('users')
                  ->cascadeOnDelete();

            $table->unique(['announcement_id', 'buyer_id']);
            $table->index('buyer_id');
            $table->index('seller_id');
            $table->index('last_message_at');
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id');
            $table->unsignedBigInteger('sender_id');
            $table->text('body');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('conversation_id')
                  ->references('id')->on('conversations')
                  ->cascadeOnDelete();

            $table->foreign('sender_id')
                  ->references('id')->on('users')
                  ->cascadeOnDelete();

            $table->index(['conversation_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversations');
    }
};
