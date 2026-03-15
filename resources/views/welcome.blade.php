<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<title>Smart Sabin Dashboard</title>

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

</head>


<body class="bg-gray-950 text-gray-200">

<!-- NAVBAR -->
<header class="bg-gray-900 border-b border-gray-800 sticky top-0 z-50">

<div class="max-w-7xl mx-auto flex justify-between items-center p-4">

<h1 class="text-xl font-bold text-green-400">
🌱 Smart Sabin
</h1>

<nav class="space-x-6 text-gray-400">

<a href="#" class="hover:text-green-400">Dashboard</a>
<a href="#" class="hover:text-green-400">Sensors</a>
<a href="#" class="hover:text-green-400">Analytics</a>
<a href="#" class="hover:text-green-400">System</a>

</nav>

</div>

</header>


<!-- MAIN -->
<main class="max-w-7xl mx-auto p-6">

<!-- TITLE -->
<div class="mb-10" data-aos="fade-down">

<h2 class="text-3xl font-bold">
Smart Agriculture Monitoring
</h2>

<p class="text-gray-400">
Realtime monitoring sistem pertanian berbasis IoT
</p>

</div>



<!-- SENSOR CARDS -->
<div class="grid md:grid-cols-3 lg:grid-cols-4 gap-6">

<div data-aos="zoom-in"
class="bg-gray-900 border border-gray-800 p-5 rounded-xl shadow">

<p class="text-gray-400">pH Air</p>

<p class="text-4xl font-bold text-green-400">

{{ $history->first()->ph_air ?? '-' }}

</p>

</div>


<div data-aos="zoom-in"
class="bg-gray-900 border border-gray-800 p-5 rounded-xl shadow">

<p class="text-gray-400">pH Tanah</p>

<p class="text-4xl font-bold">

{{ $history->first()->ph_tanah ?? '-' }}

</p>

</div>


<div data-aos="zoom-in"
class="bg-gray-900 border border-gray-800 p-5 rounded-xl shadow">

<p class="text-gray-400">Suhu Udara</p>

<p class="text-4xl font-bold text-orange-400">

{{ $history->first()->suhu_udara ?? '-' }}°C

</p>

</div>


<div data-aos="zoom-in"
class="bg-gray-900 border border-gray-800 p-5 rounded-xl shadow">

<p class="text-gray-400">Kelembaban Tanah</p>

<p class="text-4xl font-bold text-blue-400">

{{ $history->first()->kelembaban_tanah ?? '-' }}%

</p>

</div>

</div>



<!-- GRAFIK -->
<div class="grid lg:grid-cols-3 gap-6 mt-12">


<div class="bg-gray-900 border border-gray-800 p-6 rounded-xl">

<h3 class="text-lg font-semibold mb-4">
📊 Grafik pH Air
</h3>

<canvas id="phChart"></canvas>

</div>


<div class="bg-gray-900 border border-gray-800 p-6 rounded-xl">

<h3 class="text-lg font-semibold mb-4">
🌡 Grafik Suhu
</h3>

<canvas id="tempChart"></canvas>

</div>


<div class="bg-gray-900 border border-gray-800 p-6 rounded-xl">

<h3 class="text-lg font-semibold mb-4">
💧 Kelembaban Tanah
</h3>

<canvas id="soilChart"></canvas>

</div>

</div>



<!-- HISTORY -->
<div class="mt-12">

<h3 class="text-xl font-semibold mb-4">
📋 Riwayat Sensor
</h3>

<div class="overflow-x-auto bg-gray-900 border border-gray-800 rounded-xl">

<table class="w-full text-sm">

<thead class="bg-gray-800">

<tr>

<th class="p-3">Waktu</th>
<th class="p-3">pH Air</th>
<th class="p-3">pH Tanah</th>
<th class="p-3">Suhu</th>
<th class="p-3">Kelembaban Tanah</th>

</tr>

</thead>

<tbody>

@foreach($history as $item)

<tr class="border-t border-gray-800 hover:bg-gray-800">

<td class="p-3">{{ $item->created_at }}</td>
<td class="p-3">{{ $item->ph_air }}</td>
<td class="p-3">{{ $item->ph_tanah }}</td>
<td class="p-3">{{ $item->suhu_udara }}</td>
<td class="p-3">{{ $item->kelembaban_tanah }}</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

</main>
<div class="bg-gray-900 border border-gray-800 rounded-xl shadow-lg p-6">
    @if($latestRecommendation)
        <div class="space-y-4">
            <p class="text-gray-400">Rekomendasi: 
                <span class="text-green-400 font-bold">{{ $latestRecommendation['rekomendasi tanaman'] ?? '-' }}</span>
            </p>
            <p class="text-gray-400">Status Servo: 
                <span class="text-blue-400 font-bold">{{ $latestRecommendation['status servo'] ?? '-' }}</span>
            </p>
            <p class="text-gray-400">Target pH: 
                <span class="text-yellow-400 font-bold">{{ $latestRecommendation['target ph'] ?? '-' }}</span>
            </p>
        </div>
    @else
        <p class="text-gray-500 text-center">Data analisis tidak tersedia.</p>
    @endif
</div>
<!-- FOOTER -->
<footer class="border-t border-gray-800 mt-12">

<div class="max-w-7xl mx-auto p-6 text-center text-gray-500">

© 2026 Smart Sabin Monitoring System

</div>

</footer>



<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>

AOS.init({duration:1000});

const labels = [

@foreach($history as $item)
"{{ $item->created_at->format('H:i') }}",
@endforeach

];

const phData = [

@foreach($history as $item)
{{ $item->ph_air }},
@endforeach

];

const tempData = [

@foreach($history as $item)
{{ $item->suhu_udara }},
@endforeach

];

const soilData = [

@foreach($history as $item)
{{ $item->kelembaban_tanah }},
@endforeach

];


new Chart(document.getElementById('phChart'), {
type:'line',
data:{labels:labels,datasets:[{label:'pH Air',data:phData,borderColor:'#22c55e'}]}
});

new Chart(document.getElementById('tempChart'), {
type:'line',
data:{labels:labels,datasets:[{label:'Suhu',data:tempData,borderColor:'#fb923c'}]}
});

new Chart(document.getElementById('soilChart'), {
type:'line',
data:{labels:labels,datasets:[{label:'Kelembaban',data:soilData,borderColor:'#38bdf8'}]}
});

</script>

</body>
</html>