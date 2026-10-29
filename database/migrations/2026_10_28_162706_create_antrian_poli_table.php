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
        Schema::create('antrian_poli', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_poli')->constrained('poli')->cascadeOnDelete();
            $table->integer('no_antrian_dipanggil')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antrian_poli');
    }
};
