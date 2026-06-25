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
        Schema::create('monitoring_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kolam_id')->constrained('kolams')->cascadeOnDelete();
            $table->enum('metode_budidaya', ['bioflok', 'konvensional']);
            $table->decimal('suhu', 5, 2);       // Suhu air °C
            $table->decimal('ph', 4, 2);          // pH air
            $table->decimal('amonia', 6, 4);      // Kadar amonia ppm
            $table->string('status_panen');        // Siap Panen / Belum Siap
            $table->integer('umur_ikan');           // Umur ikan dalam hari
            $table->string('kondisi_air');          // normal / bahaya
            $table->text('rekomendasi')->nullable(); // Rekomendasi tindakan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_logs');
    }
};
