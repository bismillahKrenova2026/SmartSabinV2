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
        'penyiraman_ulang',
        'flow_rate',
        'suhu_air',
    ];

    protected $casts = [
        'ph_air' => 'float',
        'ph_tanah' => 'float',
        'kelembaban_tanah' => 'float',
        'suhu_udara' => 'float',
        'kelembaban_udara' => 'float',
        'intensitas_cahaya' => 'float',
        'sensor_hujan' => 'boolean',
        'status_filtrasi' => 'boolean',
        'pemanas_nikrom' => 'boolean',
        'tambah_garam' => 'boolean',
        'target_ph_tanaman' => 'float',
        'status_aliran' => 'boolean',
        'servo_valve' => 'integer',
        'target_ph' => 'float',
        'ph_stlh_air' => 'float',
        'penyiraman_ulang' => 'boolean',
        'flow_rate' => 'float',
        'suhu_air' => 'float',
    ];
}
