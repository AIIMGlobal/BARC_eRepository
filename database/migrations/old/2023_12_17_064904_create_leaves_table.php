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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('leave_category_id')->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->integer('day_count')->nullable();
            $table->longText('reason')->nullable();
            $table->longText('hod_comment')->nullable();
            $table->integer('approved_by')->nullable();
            $table->integer('rejected_by')->nullable();
            $table->date('approved_date')->nullable();
            $table->date('rejected_date')->nullable();
            $table->integer('status')->nullable()->comment('0:pending, 1:approved, 2:declined');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
