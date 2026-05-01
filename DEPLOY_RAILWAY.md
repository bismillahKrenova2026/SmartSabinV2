# Deploy Farmonitor ke Railway

## Yang sudah disiapkan di project
- `railway.json` untuk menjalankan migrasi dan seed otomatis sebelum aplikasi start.
- `.env.example` yang sudah diisi variabel penting.
- Dukungan `DATABASE_URL` agar koneksi PostgreSQL Railway langsung terbaca.
- Seed admin yang otomatis membuat akun login pertama.

## Langkah deploy

### 1) Upload repo ke GitHub
Pastikan source code ini sudah ada di repository GitHub pada branch `Farmonitor`.

### 2) Buat project baru di Railway
- Buka Railway
- Klik **New Project**
- Pilih **Deploy from GitHub repo**
- Pilih repository proyek ini, branch `Farmonitor`

### 3) Tambahkan database PostgreSQL
- Di canvas Railway, tambahkan service **PostgreSQL**
- Railway akan membuat variabel koneksi database otomatis

### 4) Set variabel environment pada service app
Isi variabel berikut pada service Laravel:

- `APP_NAME=Farmonitor`
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_KEY=...` (generate dari Laravel)
- `APP_URL=https://domain-railway-anda`
- `DB_CONNECTION=pgsql`
- `DATABASE_URL=${{Postgres.DATABASE_URL}}`
- `SESSION_DRIVER=database`
- `CACHE_STORE=database`
- `QUEUE_CONNECTION=database`
- `BLYNK_TOKEN=...`
- `GOOGLE_SHEET_WEB_APP_URL=...`
- `OPENROUTER_API_KEY=...` (jika ingin fitur AI aktif)
- `OPENROUTER_MODEL=qwen/qwen3.6-plus:free`
- `FARMONITOR_ADMIN_EMAIL=...`
- `FARMONITOR_ADMIN_PASSWORD=...`

### 5) Generate APP_KEY
Jalankan sekali secara lokal atau lewat Railway shell:

```bash
php artisan key:generate --show
```

Salin hasilnya ke variabel `APP_KEY`.

### 6) Deploy
Saat deployment berjalan, Railway akan menjalankan:

```bash
php artisan migrate --force && php artisan db:seed --force
```

Jadi tabel database akan dibuat dan akun admin otomatis terisi.

### 7) Generate domain
- Buka service app
- Masuk ke tab **Networking**
- Klik **Generate Domain**

### 8) Login pertama
Gunakan email dan password dari variabel:
- `FARMONITOR_ADMIN_EMAIL`
- `FARMONITOR_ADMIN_PASSWORD`

## Kalau ada yang gagal
- Jika halaman tidak muncul, cek log build/deploy Railway.
- Jika login gagal, pastikan `db:seed` berhasil jalan.
- Jika data sensor kosong, pastikan `BLYNK_TOKEN` dan node Blynk aktif.
- Jika rekomendasi AI kosong, pastikan `OPENROUTER_API_KEY` terisi.
