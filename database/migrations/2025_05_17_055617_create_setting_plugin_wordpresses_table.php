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
        Schema::create('setting_plugin_wordpresses', function (Blueprint $table) {
            $table->id();
            $table->string('plugin_name', 50);
            $table->string('background');
            $table->double('price_of_brick');
            $table->integer('target_brick');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_plugin_wordpresses');
    }
};
