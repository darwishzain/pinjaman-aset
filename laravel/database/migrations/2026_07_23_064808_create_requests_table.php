<?php
//! T30_*, T31_*, T40_*
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\RequestStatus;
use App\Enums\ReviewStatus;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('T30_requests', function (Blueprint $table) {
            $table->ulid('T30_id')->primary();
            $table->foreignUlid('T30T10_user_id')
                ->constrained(table:'users',column:'id')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->text('T30_reason');
            $table->date('T30_start_date');
            $table->date('T30_end_date');
            $table->timestamp('T30_scheduled_pickup_at')->nullable();//default to a day before start date
            $table->string('T30_location')->nullable();
            $table->text('T30_remark')->nullable();
            $table->string('T30_type');//loan type: individual/department
            //* Supported details
            $table->foreignUlid('T30T10_support_by_id')->nullable()
                ->constrained(table:'users',column:'id')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->string('T30_support_comment')->nullable();
            $table->enum('T30_support_status', array_column(ReviewStatus::cases(), 'value'))
                ->default(ReviewStatus::PENDING->value);
            $table->timestamp('T30_support_at')->nullable();
            //* Approved details
            $table->foreignUlid('T30T10_approve_by_id')->nullable()
                ->constrained(table:'users',column:'id')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->string('T30_approve_comment')->nullable();
            $table->enum('T30_approve_status', array_column(ReviewStatus::cases(), 'value'))
                ->default(ReviewStatus::PENDING->value);
            $table->timestamp('T30_approve_at')->nullable();
            $table->enum('T30_status', array_column(RequestStatus::cases(), 'value'))
                ->default(RequestStatus::PENDING->value);
            $table->timestamp('T30_created_at')->useCurrent();
            $table->timestamp('T30_updated_at')->useCurrent()->useCurrentOnUpdate();
        });
        Schema::create('T31_request_asset', function (Blueprint $table) {
            $table->ulid('T31_id')->primary();
            $table->foreignUlid('T31T30_request_id')
                ->constrained(table:'T30_requests',column:'T30_id')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('T31T21_asset_category_id')
                ->constrained(table:'T21_asset_categories',column:'T21_id')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->integer('T31_quantity');
        });
        Schema::create('T40_transactions', function (Blueprint $table) {
            $table->ulid('T40_id')->primary();
            $table->foreignUlid('T40T30_request_id')//Link to request
                ->constrained(table:'T30_requests',column:'T30_id')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('T40T20_asset_id')
                ->constrained(table:'T20_assets',column:'T20_id')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->string('T40_action');
            $table->foreignUlid('T40T10_giver_id')//default to handler->id (in) or request->user->id (out)
                ->constrained(table:'users',column:'id')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('T40T10_taker_id')//default to request->user->id (in) or handler->id (out)
                ->constrained(table:'users',column:'id')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('T40T10_handler_id')//Authorize the handover
                ->constrained(table:'users',column:'id')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamp('T40_created_at')->useCurrent();
            $table->timestamp('T40_updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('T30_requests');
        Schema::dropIfExists('T40_transactions');
    }
};
