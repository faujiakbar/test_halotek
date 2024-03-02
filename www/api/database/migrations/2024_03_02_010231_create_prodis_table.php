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
        Schema::create('prodis', function (Blueprint $table) {
            $table->id();
            $table->string('reg_no', 50)->comment("Autogenerate with from some prefix");
            $table->string('prodi_name')->comment("Prodi");
            $table->string('prodi_program')->comment("Program in prodi");
            $table->string('prodi_level')->comment("Bachelor/Master");
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prodis');
    }
};
