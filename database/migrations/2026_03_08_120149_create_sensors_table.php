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
Schema::create('sensor_data', function (Blueprint $table) {
    $table->id();

    $table->float('ph_air')->nullable();
    $table->float('ph_tanah')->nullable();
    $table->float('kelembaban_tanah')->nullable();
    $table->float('suhu_udara')->nullable();
    $table->float('kelembaban_udara')->nullable();
    $table->float('intensitas_cahaya')->nullable();
    $table->boolean('sensor_hujan')->nullable();

    $table->string('kondisi_air')->nullable();

    $table->boolean('status_filtrasi')->default(false);
    $table->boolean('pemanas_nikrom')->default(false);
    $table->boolean('tambah_garam')->default(false);

    $table->float('target_ph_tanaman')->nullable();

    $table->boolean('status_aliran')->default(false);
    $table->integer('servo_valve')->nullable();

    $table->float('target_ph')->nullable();
    $table->float('ph_stlh_air')->nullable();

    $table->boolean('penyiraman_ulang')->default(false);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_data');
    }
};