# Smart Sabin Rework

Struktur ini sudah dirombak agar lebih profesional:

- halaman public `welcome`
- login session dengan Laravel Auth
- dashboard terproteksi
- rekomendasi tanaman
- monitoring tanaman aktif
- tombol ganti tanaman
- integrasi service untuk Blynk dan Spreadsheet

## Login default

Email: `admin@smartsabin.test`

Password: `password123`

## Catatan setup

1. Jalankan migration agar tabel `sessions` tersedia.
2. Jalankan seeder agar akun login default dibuat.
3. Pastikan `.env` berisi `BLYNK_TOKEN` dan `GOOGLE_SHEET_WEB_APP_URL`.
4. Setelah login, mulai dari dashboard lalu pilih tanaman.
