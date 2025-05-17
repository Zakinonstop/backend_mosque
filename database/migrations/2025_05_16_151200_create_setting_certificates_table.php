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
        Schema::create('setting_certificates', function (Blueprint $table) {
            $table->id();
            $table->string('background');
            $table->string('logo');
            $table->string('title');
            $table->string('sub_title1');
            $table->string('sub_title2');
            $table->text('description');
            $table->string('certifier');
            $table->string('position');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_certificates');
    }
};
