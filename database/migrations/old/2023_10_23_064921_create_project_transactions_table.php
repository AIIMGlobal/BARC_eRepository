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
        Schema::create('project_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('project_id')->nullable();
            $table->integer('type')->nullable()->comment('1=in, 2=out');
            $table->text('purpose')->nullable();
            $table->float('amount', 12, 2)->nullable();
            $table->string('document')->nullable();
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
        Schema::dropIfExists('project_transactions');
    }
};
