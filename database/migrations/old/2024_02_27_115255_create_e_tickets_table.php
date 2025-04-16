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
        Schema::create('e_tickets', function (Blueprint $table) {
            $table->id();
            $table->integer('ticket_type_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->integer('project_id')->nullable();
            $table->string('ticket_no')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('priority')->nullable();
            $table->integer('has_file')->nullable()->comment('1:Yes, 0:No');
            $table->integer('accepted_by')->nullable();
            $table->datetime('accepted_at')->nullable();
            $table->integer('solved_by')->nullable();
            $table->datetime('solved_at')->nullable();
            $table->integer('rejected_by')->nullable();
            $table->datetime('rejected_at')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('status')->nullable()->comment('0:pending, 1:accepted, 2:solved, 3:rejected');
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
        Schema::dropIfExists('e_tickets');
    }
};
