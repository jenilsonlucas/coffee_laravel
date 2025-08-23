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
        Schema::create('appCategories', function (Blueprint $table) {
            $table->id();
            $table->integer("sub_of", false, true)->nullable();
            $table->string("name", 255);
            $table->string("type", 15);
            $table->integer("order_by")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appCategories');
    }
};
