<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    protected $table = 'sensor_data';

    protected $fillable = [
        'ph_air',
        'ph_tanah',
        'kelembaban_tanah',
        'suhu_udara',
        'kelembaban_udara',
        'intensitas_cahaya',
        'sensor_hujan',
        'kondisi_air',
        'status_filtrasi',
        'pemanas_nikrom',
        'tambah_garam',
        'target_ph_tanaman',
        'status_aliran',
        'servo_valve',
        'target_ph',
        'ph_stlh_air',
        'penyiraman_ulang'
    ];
}
{
    //
}
