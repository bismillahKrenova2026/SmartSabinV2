# 🌱 Farmonitor Dashboard V2

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-12.x-ff2d20?style=for-the-badge\&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-777bb4?style=for-the-badge\&logo=php)
![Blade](https://img.shields.io/badge/Blade-Template-orange?style=for-the-badge)
![Railway](https://img.shields.io/badge/Deployed%20on-Railway-0b0d0e?style=for-the-badge\&logo=railway)
![Krenova](https://img.shields.io/badge/Krenova-Klaten%202026-f59e0b?style=for-the-badge)

## 🚀 Farm Monitoring Dashboard berbasis Laravel

**Farmonitor Dashboard V2** adalah web aplikasi pemantauan pertanian jarak jauh berbasis **Laravel** yang dibangun untuk menampilkan, mengolah, dan memantau data tanaman dalam satu dashboard yang modern, terstruktur, dan siap digunakan secara online.

> **Farmonitor** = *Far Monitor* (pemantauan jarak jauh) + *Farm Monitor* (pemantauan lahan pertanian)

</div>

---

## 📌 Tentang Project

Farmonitor Dashboard V2 merupakan pengembangan dari versi sebelumnya yang masih berupa prototype. Versi ini dibuat sebagai **web asli** dan bukan sekadar dummy demo, dengan fitur autentikasi, dashboard terproteksi, rekomendasi tanaman, monitoring aktif, kontrol, serta integrasi layanan eksternal untuk mendukung sistem pemantauan pertanian jarak jauh.

Aplikasi ini cocok digunakan untuk:

* 🌾 Pemantauan pertanian jarak jauh (remote farm monitoring)
* 📊 Visualisasi dan pemantauan kondisi tanaman secara real-time
* 🔐 Sistem dashboard dengan login
* 🤖 Analisis berbasis data / AI
* 🧪 Proyek inovasi dan presentasi teknologi
* 🏆 Dokumentasi karya **Krenova Klaten 2026**

---

## ✨ Highlight Utama

* ✅ **Web asli berbasis Laravel**
* ✅ **Halaman publik dan dashboard terpisah**
* ✅ **Login session dengan autentikasi Laravel**
* ✅ **Dashboard terproteksi**
* ✅ **Rekomendasi tanaman**
* ✅ **Monitoring tanaman aktif**
* ✅ **Tombol ganti / pilih tanaman**
* ✅ **Kontrol dan sinkronisasi sensor**
* ✅ **Integrasi Blynk**
* ✅ **Integrasi Google Spreadsheet**
* ✅ **Analisis AI**
* ✅ **Siap deploy di Railway**

---

## 🧭 Alur Sistem

```txt
Sensor / Data Lapangan
        ↓
ESP32 / Mikrokontroler
        ↓
Blynk / Spreadsheet / API
        ↓
Laravel Web App (Farmonitor)
        ↓
Dashboard, Monitoring, Rekomendasi, AI Analysis
```

Alur ini menunjukkan bahwa Farmonitor tidak hanya menampilkan data, tetapi juga menjadi pusat pengelolaan informasi untuk kebutuhan pemantauan pertanian dari jarak jauh.

---

## 🧩 Fitur yang Tersedia

### 1. Halaman Publik

* Landing page / welcome page
* Tampilan awal untuk pengunjung
* Akses menuju login dan fitur utama

### 2. Autentikasi User

* Login session menggunakan Laravel Auth
* Logout aman
* Dashboard hanya bisa diakses user yang sudah login

### 3. Dashboard Terproteksi

* Ringkasan informasi utama
* Navigasi ke fitur monitoring dan rekomendasi
* Tampilan terstruktur untuk pengguna aktif

### 4. Rekomendasi Tanaman

* Menampilkan rekomendasi tanaman berdasarkan kondisi yang dipilih
* Mendukung pemilihan tanaman
* Tersedia aksi untuk mengganti tanaman

### 5. Monitoring Tanaman Aktif

* Memantau tanaman yang sedang dipilih
* Menyajikan status pemantauan secara terpusat
* Memudahkan user melihat kondisi terbaru dari jarak jauh

### 6. Kontrol Sistem

* Endpoint kontrol sensor / perangkat
* Endpoint sinkronisasi data sensor
* Endpoint analisis AI

### 7. Integrasi Eksternal

* **Blynk** untuk koneksi data IoT
* **Google Spreadsheet** untuk pencatatan data
* **OpenRouter** untuk fitur AI (opsional)

---

## 📄 Halaman / Route Utama

Berdasarkan struktur project, beberapa route utama yang tersedia antara lain:

* `/` → halaman utama
* `/login` → halaman login
* `/dashboard` → dashboard utama
* `/recommendation` / `/rekomendasi` → rekomendasi tanaman
* `/monitoring` / `/pantauan` → halaman monitoring
* `/plant/select` → pilih tanaman
* `/plant/change` → ganti tanaman
* `/sensor/sync` → sinkronisasi data sensor
* `/control` → kontrol sistem
* `/ai-analysis` → analisis AI

---

## 🛠️ Teknologi yang Digunakan

| Teknologi         | Fungsi                   |
| ----------------- | ------------------------ |
| Laravel 12        | Framework utama aplikasi |
| PHP 8.2+          | Bahasa backend           |
| Blade             | Template engine tampilan |
| Vite              | Build tool frontend      |
| Tailwind CSS 4    | Styling modern           |
| Axios             | Request HTTP             |
| Google API Client | Integrasi Google layanan |
| Railway           | Hosting / deployment     |

---

## 📦 Dependensi Penting

Dari struktur project, beberapa komponen penting yang digunakan adalah:

* `laravel/framework`
* `laravel/tinker`
* `google/apiclient`
* `vite`
* `tailwindcss`
* `axios`
* `concurrently`

---

## 📁 Struktur Project

```txt
SmartSabinV2 (Farmonitor)
├── app
│   ├── Http
│   │   └── Controllers
│   ├── Models
│   ├── Providers
│   └── Services
├── bootstrap
├── config
├── database
├── public
├── resources
├── routes
├── storage
├── tests
├── artisan
├── composer.json
├── package.json
├── railway.json
└── README.md
```

### Services yang tersedia

Folder `app/Services` berisi logika inti seperti:

* `AIService.php`
* `BlynkService.php`
* `PlantRecommendationService.php`
* `SpreadsheetService.php`
* `WaterProcessingService.php`

### Controllers yang tersedia

Folder `app/Http/Controllers` berisi controller utama seperti:

* `AuthController.php`
* `DashboardController.php`
* `HomeController.php`
* `MonitoringController.php`
* `PlantController.php`
* `PlantRecommendationController.php`
* `SensorController.php`

---

## ⚙️ Instalasi Lokal

### 1. Clone repository

```bash
git clone https://github.com/bismillahKrenova2026/SmartSabinV2.git
cd SmartSabinV2
git checkout Farmonitor
```

### 2. Install dependency PHP

```bash
composer install
```

### 3. Install dependency frontend

```bash
npm install
```

### 4. Copy file environment

```bash
cp .env.example .env
```

### 5. Generate application key

```bash
php artisan key:generate
```

### 6. Jalankan migration

```bash
php artisan migrate
```

### 7. Jalankan aplikasi

```bash
php artisan serve
```

Jika ingin mode development penuh:

```bash
npm run dev
```

---

## 🔐 Environment Variables Penting

Beberapa variabel penting yang digunakan pada project ini antara lain:

```env
APP_NAME=Farmonitor
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.up.railway.app

DB_CONNECTION=pgsql
DATABASE_URL=
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

BLYNK_TOKEN=
GOOGLE_SHEET_WEB_APP_URL=
OPENROUTER_API_KEY=
OPENROUTER_MODEL=qwen/qwen3.6-plus:free
FARMONITOR_ADMIN_EMAIL=
FARMONITOR_ADMIN_PASSWORD=
```

---

## 🚀 Deployment

Project ini sudah disiapkan untuk deployment di **Railway**.

Fitur yang mendukung deployment:

* `railway.json`
* `Procfile`
* otomatisasi migrasi dan seed
* dukungan PostgreSQL via `DATABASE_URL`
* login admin awal melalui seeder

Contoh alur deploy:

```txt
GitHub → Railway → PostgreSQL → Domain Publik → Farmonitor
```

---

## 🔑 Login Default

Jika seeder akun admin digunakan, login awal mengikuti konfigurasi environment:

* `FARMONITOR_ADMIN_EMAIL`
* `FARMONITOR_ADMIN_PASSWORD`

> Untuk keamanan, pastikan kredensial default diganti saat deploy produksi.

---

## 🎯 Tujuan Pengembangan

Farmonitor Dashboard V2 dibuat untuk:

* membangun sistem pemantauan pertanian jarak jauh yang lebih rapi dan terpusat
* memudahkan monitoring tanaman secara online dari mana saja
* menjadi media integrasi data sensor dan layanan web
* mendukung pengembangan aplikasi IoT berbasis Laravel
* menjadi karya inovasi teknologi untuk ajang **Krenova Klaten 2026**

---

## 🧑‍💻 Tim Pengembang

* **M. FAHRI FIRNANDO**
* **RAHMAD TEGAR YURIANTO**
* **TOTOK ANDRIANTO**
* **ZAKIAN AUFA NURENDRA**

---

## 🏷️ Branding

<div align="center">

### Krenova Klaten 2026

**Far Monitor, Farm Monitor — Pantau Lahan dari Mana Saja**

</div>

---

## 📝 Catatan

* Project ini merupakan versi web aktif, bukan sekadar demo.
* Struktur Laravel digunakan agar project lebih rapi, mudah dikembangkan, dan siap diintegrasikan dengan sistem IoT nyata.
* README ini dapat terus diperbarui mengikuti fitur terbaru yang ditambahkan ke aplikasi.

---

<div align="center">

**Made with ❤️ for Smart Agriculture**

</div>
