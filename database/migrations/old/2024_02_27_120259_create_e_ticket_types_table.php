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
        Schema::create('e_ticket_types', function (Blueprint $table) {
            $table->id();
            $table->integer('sl')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->integer('status')->nullable()->comment('0:inactive, 1:active, 2:delete');
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
        Schema::dropIfExists('e_ticket_types');
    }
};
