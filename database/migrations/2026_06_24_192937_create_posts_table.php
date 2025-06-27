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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('category_id')->nullable()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('title', 255);
            $table->fullText('title');
            $table->string('uri', 255);
            $table->string('subtitle', 255);
            $table->fullText('subtitle');
            $table->text('content');
            $table->string('cover', 255);
            $table->integer('views', false, true)->default(0);
            $table->string('status', 20)->default('draft')->comment('post, draft, trash');
            $table->timestamp('post_at')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
