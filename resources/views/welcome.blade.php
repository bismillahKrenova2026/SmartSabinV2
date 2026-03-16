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
<h1 class="text-xl font-bold text-green-400">🌱 Smart Sabin</h1>

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
<h2 class="text-3xl font-bold">Smart Agriculture Monitoring</h2>
<p class="text-gray-400">Realtime monitoring sistem pertanian berbasis IoT</p>
</div>

<!-- SENSOR CARDS -->
<div class="grid md:grid-cols-3 lg:grid-cols-4 gap-6">

<div data-aos="zoom-in" class="bg-gray-900 border border-gray-800 p-5 rounded-xl shadow">
<p class="text-gray-400">pH Air</p>
<p class="text-4xl font-bold text-green-400" id="phAir">{{ $latest->ph_air ?? '-' }}</p>
</div>

<div data-aos="zoom-in" class="bg-gray-900 border border-gray-800 p-5 rounded-xl shadow">
<p class="text-gray-400">pH Tanah</p>
<p class="text-4xl font-bold" id="phTanah">{{ $latest->ph_tanah ?? '-' }}</p>
</div>

<div data-aos="zoom-in" class="bg-gray-900 border border-gray-800 p-5 rounded-xl shadow">
<p class="text-gray-400">Suhu Udara</p>
<p class="text-4xl font-bold text-orange-400" id="suhuUdara">{{ $latest->suhu_udara ?? '-' }}°C</p>
</div>

<div data-aos="zoom-in" class="bg-gray-900 border border-gray-800 p-5 rounded-xl shadow">
<p class="text-gray-400">Kelembaban Tanah</p>
<p class="text-4xl font-bold text-blue-400" id="kelembabanTanah">{{ $latest->kelembaban_tanah ?? '-' }}%</p>
</div>

</div>

<!-- GRAFIK -->
<div class="grid lg:grid-cols-3 gap-6 mt-12">

<div class="bg-gray-900 border border-gray-800 p-6 rounded-xl">
<h3 class="text-lg font-semibold mb-4">📊 Grafik pH Air</h3>
<canvas id="phChart"></canvas>
</div>

<div class="bg-gray-900 border border-gray-800 p-6 rounded-xl">
<h3 class="text-lg font-semibold mb-4">🌡 Grafik Suhu</h3>
<canvas id="tempChart"></canvas>
</div>

<div class="bg-gray-900 border border-gray-800 p-6 rounded-xl">
<h3 class="text-lg font-semibold mb-4">💧 Kelembaban Tanah</h3>
<canvas id="soilChart"></canvas>
</div>

</div>

<!-- HISTORY -->
<div class="mt-12">

<h3 class="text-xl font-semibold mb-4">📋 Riwayat Sensor Terbaru</h3>

<div class="overflow-x-auto bg-gray-900 border border-gray-800 rounded-xl shadow-lg">

<table id="sensorTable" class="w-full text-sm text-gray-300">

<thead class="bg-gray-800 text-gray-400 uppercase text-xs">
<tr>
<th class="p-4 text-left">Waktu</th>
<th class="p-4">pH Air</th>
<th class="p-4">pH Tanah</th>
<th class="p-4">Suhu</th>
<th class="p-4">Kelembaban</th>
</tr>
</thead>

<tbody class="divide-y divide-gray-800">

@if($latest)

<tr>

<td class="p-4 text-gray-400">
{{ $latest->created_at->diffForHumans() }}
<span class="block text-[10px] text-gray-600">
{{ $latest->created_at->format('H:i:s') }}
</span>
</td>

<td class="p-4 text-center font-mono">
{{ number_format($latest->ph_air,1) }}
</td>

<td class="p-4 text-center font-mono">
{{ number_format($latest->ph_tanah,1) }}
</td>

<td class="p-4 text-center text-orange-400">
{{ $latest->suhu_udara }}°C
</td>

<td class="p-4 text-center text-blue-400">
{{ $latest->kelembaban_tanah }}%
</td>

</tr>

@else

<tr>
<td colspan="5" class="p-6 text-center text-gray-500">
Belum ada data sensor.
</td>
</tr>

@endif

</tbody>

</table>
</div>
</div>

<!-- REKOMENDASI -->
<div class="mt-12 bg-gray-900 border border-gray-800 rounded-xl shadow-lg p-6">

<h3 class="text-xl font-semibold mb-4">🌾 Analisis Tanaman</h3>

@if($latestRecommendation)

<div class="space-y-4">

<p class="text-gray-400">
Rekomendasi Tanaman :
<span id="rekomTanaman" class="text-green-400 font-bold">
{{ $latestRecommendation['rekomendasi tanaman'] ?? '-' }}
</span>
</p>

<p class="text-gray-400">
Status Servo :
<span id="statusServo" class="text-blue-400 font-bold">
{{ $latestRecommendation['status servo'] ?? '-' }}
</span>
</p>

<p class="text-gray-400">
Target pH :
<span id="targetPh" class="text-yellow-400 font-bold">
{{ $latestRecommendation['target ph'] ?? '-' }}
</span>
</p>

</div>

@else

<p class="text-gray-500 text-center">
Data analisis tidak tersedia.
</p>

@endif

</div>

</main>

<!-- FOOTER -->
<footer class="border-t border-gray-800 mt-12">
<div class="max-w-7xl mx-auto p-6 text-center text-gray-500">
© 2026 Smart Sabin Monitoring System
</div>
</footer>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
AOS.init({duration:1000});
</script>

<script>

const phChart = new Chart(document.getElementById('phChart'), {
type:'bar',
data:{labels:['pH Air'],datasets:[{label:'pH Air',data:[{{ $latest->ph_air ?? 0 }}],backgroundColor:'#22c55e'}]},
options:{responsive:true,scales:{y:{beginAtZero:true}}}
});

const tempChart = new Chart(document.getElementById('tempChart'), {
type:'bar',
data:{labels:['Suhu'],datasets:[{label:'Suhu',data:[{{ $latest->suhu_udara ?? 0 }}],backgroundColor:'#fb923c'}]},
options:{responsive:true,scales:{y:{beginAtZero:true}}}
});

const soilChart = new Chart(document.getElementById('soilChart'), {
type:'bar',
data:{labels:['Kelembaban'],datasets:[{label:'Kelembaban',data:[{{ $latest->kelembaban_tanah ?? 0 }}],backgroundColor:'#38bdf8'}]},
options:{responsive:true,scales:{y:{beginAtZero:true}}}
});


async function fetchLatest(){

try{

const res = await fetch('/api/sensor/latest');

if(!res.ok) return;

const data = await res.json();

document.getElementById('phAir').innerText = data.ph_air ?? '-';
document.getElementById('phTanah').innerText = data.ph_tanah ?? '-';
document.getElementById('suhuUdara').innerText = (data.suhu_udara ?? '-')+'°C';
document.getElementById('kelembabanTanah').innerText = (data.kelembaban_tanah ?? '-')+'%';

phChart.data.datasets[0].data = [data.ph_air ?? 0];
tempChart.data.datasets[0].data = [data.suhu_udara ?? 0];
soilChart.data.datasets[0].data = [data.kelembaban_tanah ?? 0];

phChart.update();
tempChart.update();
soilChart.update();

const tbody = document.querySelector('#sensorTable tbody');

tbody.innerHTML = `
<tr>
<td class="p-4 text-gray-400">
Baru
<span class="block text-[10px] text-gray-600">
${new Date().toLocaleTimeString()}
</span>
</td>

<td class="p-4 text-center font-mono">${data.ph_air}</td>

<td class="p-4 text-center font-mono">${data.ph_tanah}</td>

<td class="p-4 text-center text-orange-400">${data.suhu_udara}°C</td>

<td class="p-4 text-center text-blue-400">${data.kelembaban_tanah}%</td>

</tr>`;

}catch(e){console.error(e)}

}



async function fetchRecommendation(){

try{

const res = await fetch('/api/recommendation/latest');

if(!res.ok) return;

const data = await res.json();

document.getElementById('rekomTanaman').innerText = data['rekomendasi tanaman'] ?? '-';

document.getElementById('statusServo').innerText = data['status servo'] ?? '-';

document.getElementById('targetPh').innerText = data['target ph'] ?? '-';

}catch(e){console.error(e)}

}



setInterval(()=>{

fetchLatest();

fetchRecommendation();

},5000);

fetchLatest();

fetchRecommendation();

</script>

</body>
</html>