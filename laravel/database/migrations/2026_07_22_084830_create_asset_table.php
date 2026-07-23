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
        Schema::create('T21_asset_categories', function (Blueprint $table) {
            $table->ulid('T21_id')->primary();
            $table->string('T21_name');//category names
            $table->timestamp('T21_created_at')->useCurrent();
            $table->timestamp('T21_updated_at')->useCurrent()->useCurrentOnUpdate();
        });
        Schema::create('T20_assets', function (Blueprint $table) {
            $table->ulid('T20_id')->primary();//system identifier
            $table->string('T20_tag')->unique();//department/company asset tag
            $table->foreignUlid('T20T21_category_id')
                ->constrained(table: 'T21_asset_categories', column: 'T21_id')//category id for category type
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('T20_brand')->nullable();
            $table->string('T20_model')->nullable();
            $table->string('T20_serial_number')->nullable()->unique();
            $table->json('T20_attributes')->nullable();
            $table->string('T20_status');
            $table->timestamp('T20_created_at')->useCurrent();
            $table->timestamp('T20_updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('T20_asset');
        Schema::dropIfExists('T21_asset_categories');
    }
};
