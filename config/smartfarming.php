<?php

return [
    'site' => [
        'name' => 'Farmonitor',
        'tagline' => 'Pemantauan lahan, air, dan tanaman jarak jauh berbasis IoT',
        'subtitle' => 'Satu platform, satu lahan, satu akun — pantau kondisi pertanian dari mana saja secara real-time.',
    ],

    'auth' => [
        'email' => env('FARMONITOR_ADMIN_EMAIL', 'admin@farmonitor.test'),
        'password' => env('FARMONITOR_ADMIN_PASSWORD', 'password123'),
    ],

    'plants' => [
        'padi' => [
            'key' => 'padi',
            'name' => 'Padi',
            'description' => 'Cocok untuk lahan dengan suplai air stabil, tekstur tanah lembap, dan pH cenderung netral ke sedikit asam.',
            'advantages' => [
                'Stabil di lahan basah.',
                'Cocok untuk sistem irigasi terkontrol.',
                'Menjadi pilihan utama untuk monitoring air.',
            ],
            'ideal_ph_min' => 5.5,
            'ideal_ph_max' => 6.5,
            'ideal_environment' => 'Lahan tergenang terkontrol, suhu hangat, dan kelembaban cukup tinggi.',
            'water_need' => 'tinggi',
            'badge' => 'Lahan basah',
        ],
        'cabai' => [
            'key' => 'cabai',
            'name' => 'Cabai',
            'description' => 'Tanaman bernilai jual tinggi yang butuh air cukup, drainase baik, dan pengawasan pH lebih ketat.',
            'advantages' => [
                'Permintaan pasar tinggi.',
                'Cocok untuk lahan yang terkontrol.',
                'Baik untuk sistem otomatisasi pemantauan.',
            ],
            'ideal_ph_min' => 5.8,
            'ideal_ph_max' => 6.8,
            'ideal_environment' => 'Drainase baik, sinar cukup, dan air tidak terlalu berlebihan.',
            'water_need' => 'sedang',
            'badge' => 'Nilai jual tinggi',
        ],
        'tomat' => [
            'key' => 'tomat',
            'name' => 'Tomat',
            'description' => 'Tumbuh baik pada lingkungan yang seimbang antara kelembaban, cahaya, dan pH tanah yang relatif stabil.',
            'advantages' => [
                'Cepat dipantau hasilnya.',
                'Cocok untuk lahan semi-terkontrol.',
                'Bagus untuk demonstrasi sensor.',
            ],
            'ideal_ph_min' => 5.5,
            'ideal_ph_max' => 7.0,
            'ideal_environment' => 'Cukup cahaya, tanah gembur, dan tidak terlalu tergenang.',
            'water_need' => 'sedang',
            'badge' => 'Adaptif',
        ],
        'sawi' => [
            'key' => 'sawi',
            'name' => 'Sawi',
            'description' => 'Cepat panen dan cocok untuk sistem monitoring yang membutuhkan siklus uji singkat.',
            'advantages' => [
                'Cepat dipanen.',
                'Mudah diamati pertumbuhannya.',
                'Cocok untuk uji coba rekomendasi tanaman.',
            ],
            'ideal_ph_min' => 6.0,
            'ideal_ph_max' => 7.0,
            'ideal_environment' => 'Kelembaban cukup, drainase baik, dan intensitas cahaya sedang.',
            'water_need' => 'sedang',
            'badge' => 'Cepat panen',
        ],
        'selada' => [
            'key' => 'selada',
            'name' => 'Selada',
            'description' => 'Cocok untuk lingkungan yang lebih sejuk, pH netral, dan kelembaban yang terjaga.',
            'advantages' => [
                'Cocok untuk lahan modern.',
                'Visualnya menarik untuk dashboard.',
                'Baik untuk demo sistem rekomendasi.',
            ],
            'ideal_ph_min' => 6.0,
            'ideal_ph_max' => 7.0,
            'ideal_environment' => 'Suhu relatif sejuk, air terjaga, dan cahaya tidak terlalu ekstrem.',
            'water_need' => 'sedang',
            'badge' => 'Sejuk & stabil',
        ],
    ],
];
