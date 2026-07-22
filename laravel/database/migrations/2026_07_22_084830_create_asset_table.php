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
        Schema::create('T3_asset', function (Blueprint $table) {
            $table->id('T3_id');
            $table->string('T3_name');
            $table->timestamps('T3_datetime_created');
            $table->timestamps('T3_datetime_updated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset');
    }
};
