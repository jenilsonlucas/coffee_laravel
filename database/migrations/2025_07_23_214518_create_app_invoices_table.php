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
        Schema::create('app_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->unsigned();
            $table->foreignId("wallet_id")->unsigned();
            $table->foreignId("category_id")->unsigned();
            $table->foreignId("invoice_of")->unsigned()->nullable();
            $table->string("description", 255);
            $table->string("type", 15);
            $table->decimal("value", 10, 2);
            $table->string("currency", 5)->default("AO");
            $table->date("due_at");
            $table->string("repeat_when", 15);
            $table->string("period", 10)->default("month");
            $table->integer("enrollments")->nullable();
            $table->integer("enrollemnt_of")->nullable();
            $table->string("status", 10)->default("unpaid");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_invoices');
    }
};
