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
        Schema::create('appwallets', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->primary('id');
            $table->foreignId('user_id')->unsigned()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('wallet', 255);
            $table->tinyInteger('free')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appwallets');
    }
};
