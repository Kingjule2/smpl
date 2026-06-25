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
        Schema::table('kolams', function (Blueprint $table) {
            $table->enum('jenis_kolam', ['bibit', 'pembesaran', 'finishing'])->default('bibit')->after('metode_budidaya');
            $table->enum('status_kolam', ['kosong', 'terisi', 'siap_pindah'])->default('kosong')->after('jenis_kolam');
            $table->date('tgl_masuk')->nullable()->after('status_kolam');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kolams', function (Blueprint $table) {
            $table->dropColumn(['jenis_kolam', 'status_kolam', 'tgl_masuk']);
        });
    }
};
