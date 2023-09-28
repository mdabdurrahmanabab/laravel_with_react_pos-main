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
        Schema::create('sales_managers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('nid')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->longText('bio')->nullable();
            $table->string('photo')->nullable();
            $table->string('nid_photo')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->casecadeOnDelete();
            $table->foreignId('shop_id')->constrained()->cascadeOnUpdate()->casecadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_managers');
    }
};
