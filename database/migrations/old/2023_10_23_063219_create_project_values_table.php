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
        Schema::create('project_values', function (Blueprint $table) {
            $table->id();
            $table->integer('project_id')->nullable();
            $table->float('project_value', 12, 2)->nullable();
            $table->float('vat_tax', 12, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->integer('status')->nullable()->comment('0:inactive, 1:on going, 2:canceled, 3:completed');
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
        Schema::dropIfExists('project_values');
    }
};
