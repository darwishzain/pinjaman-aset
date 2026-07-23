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
        Schema::create('T30_requests', function (Blueprint $table) {
            $table->ulid('T30_id')->primary();
            $table->foreignUlid('T30T01_user_id')
                ->constrained(table:'users',column:'id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->text('T30_reason');
            $table->timestamp('T30_timetouse');
            //time/date to receive, refer to staff if its 
            $table->string('T30_location');
            $table->text('T30_remark')->nullable();
            $table->string('T30_type');//loan type: individual/department
            $table->json('T30_asset_amount');//asset amount
            $table->json('T30_manager_support')->nullable();
            $table->json('T30_admin_approve')->nullable();
            $table->json('T30_logs');// maybe need monolog/monolog package
            $table->timestamp('T30_created_at')->useCurrent();
            $table->timestamp('T30_updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('T30_requests');
    }
};
