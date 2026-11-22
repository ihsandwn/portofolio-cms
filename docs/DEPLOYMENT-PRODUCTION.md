# Panduan Deploy Production — Kode Terbaru

Target: **Laravel di Hostinger** (web hosting) + **4 modul AI di Vercel** + **MySQL** untuk Laravel.
Dokumen ini mencakup semua yang berubah sampai kode terbaru, termasuk alur handoff
AI Lab, env baru (`AI_LAB_*`, `MVP_APP_SECRET`, `TRUSTED_PROXIES`), dan batasan
2 sesi/bulan.

## 1. Peta arsitektur production

```text
Pengunjung
  ├─ https://domain-anda.com ......... Laravel (Hostinger, PHP 8.3+, MySQL)
  │    └─ POST /ai-lab/auth → 302 ke *.vercel.app/auth/callback?code=...
  ├─ 4 aplikasi Next.js (4 project Vercel terpisah)
  │    ├─ sentiment → verify ke Laravel → sesi 10 menit
  │    ├─ hr-screening → verify ke Laravel → sesi 10 menit
  │    ├─ image-caption → verify ke Laravel → sesi 10 menit
  │    └─ pdf-rag-chatbot → verify ke Laravel + simpan ke Postgres+pgvector
  ├─ MySQL (Hostinger) ..... data CMS Laravel SAJA (session, cache, access_requests)
  └─ Postgres + pgvector (Neon, gratis) ..... HANYA untuk modul pdf-rag-chatbot

## 5. Database: siapa memakai apa

- **MySQL Hostinger → Laravel.** Jalankan `php artisan migrate --force` sekali;
  semua migrasi kompatibel MySQL. Tidak perlu ekstensi khusus.
- **Neon Postgres → modul PDF saja.** Langkah wajib satu kali (Neon SQL Editor
  atau `psql`), sesuai `ai-mvps/pdf-rag-chatbot/db/schema.sql`:
  `CREATE EXTENSION IF NOT EXISTS vector;` + tabel `documents` /
  `document_chunks`. Lihat juga `ai-mvps/pdf-rag-chatbot/VERCEL-NEON-SETUP.md`.
  Jangan arahkan `DATABASE_URL` modul PDF ke MySQL — tipe `vector` tidak ada di sana.
- Retensi: grant AI Lab kedaluwarsa 10 menit dan otomatis tidak berlaku;
  dokumen PDF kedaluwarsa mengikuti `DOCUMENT_TTL_MS` dan dibersihkan
  oportunistik saat upload. Tidak ada cron yang wajib jalan (tidak ada scheduler
  maupun queue worker yang dibutuhkan app ini).

## 6. Verifikasi production (checklist)

1. `curl -sI https://domain-anda.com` → 200, ada header keamanan (§2.4).
2. `curl -s https://<pdf>.vercel.app/api/health` → `{"status":"ok","storage":"postgres"}`.
   `503` = `DATABASE_URL` salah atau schema belum diterapkan.
3. Alur penuh: `/ai-lab` → minta akses → tab baru ke Vercel → upload/analisis jalan.
   Batas wajar: 2 sesi per email per modul per bulan; sesi ke-3 dalam bulan yang
   sama ditolak dengan pesan bilingual.
4. `GET /api/health` tiap modul lain → 200 (kecuali PDF yang butuh DB).

## 7. Masalah umum dan artinya

| Gejala | Penyebab paling mungkin | Perbaikan |
| --- | --- | --- |
| Tab baru `/ai-lab/auth` 503 | `AI_LAB_*` belum diset / secret < 32 char / URL portfolio tidak cocok dengan URL app Vercel | Lengkapi §2.2, samakan secret (§4), betulkan URL portfolio di admin |
| Aplikasi Vercel 401 terus | `MVP_APP_SECRET` ≠ `AI_LAB_*_SECRET` pasangan modulnya | Samakan persis, lalu Redeploy project Vercel |
| Upload PDF 500 | `DATABASE_URL` salah / schema belum ada / tipe `vector` hilang | §5; cek log Vercel → baris `[UPLOAD <id>] ...` |
| Redirect ke `/ai-lab` terus | Cookie sesi tidak terbentuk (kode exchange gagal) | Cek `LARAVEL_API_URL` menunjuk domain Laravel yang benar + bisa dijangkau dari Vercel |
| Halaman lambat setelah deploy | Cache config lama | `php artisan optimize:clear && php artisan optimize` |

## 8. Rollback

- Laravel: kembalikan file + `php artisan migrate:rollback --step=N` hanya bila
  migrasi terakhir yang bermasalah; data `access_requests` yang kedaluwarsa aman
  diabaikan (semua query memfilter `expires_at`).
- Vercel: Deployments → pilih deployment sehat sebelumnya → Promote to Production.
  Perubahan env tidak ikut ter-rollback — catat nilai env sebelum mengubah.
```

Poin penting: **modul PDF tidak bisa memakai MySQL.** Ia butuh tipe `vector(3072)`,
jadi wajib Postgres + ekstensi pgvector (Neon paling mudah). Laravel sendiri
100% kompatibel MySQL — sudah diverifikasi: tidak ada SQL khusus-Postgres di
`database/migrations`, dan tidak ada job queue/mail yang butuh worker.

## 2. Laravel di Hostinger (shared hosting)

### 2.1 Persiapan hosting

1. Pilih **PHP 8.3** (atau lebih baru) di panel Hostinger → PHP Configuration.
   `composer.json` mewajibkan PHP `~8.3.36`.
2. Buat **1 database MySQL** + user (catat host, nama DB, user, password).
3. Upload kode ke hosting, lalu arahkan domain ke folder **`public/`**
   (di shared hosting biasanya: isi `public_html` = isi folder `public/ Laravel`,
   atau gunakan subdomain/addon domain yang document root-nya ke `public/`).
4. Pastikan file `.env` **tidak** bisa diakses publik (document root = `public/`
   sudah menanganinya).

### 2.2 File `.env` production

Salin dari `.env.example`, lalu atur minimum ini:

| Variable | Nilai production | Catatan |
| --- | --- | --- |
| `APP_ENV` | `production` | — |
| `APP_DEBUG` | `false` | Wajib false di production |
| `APP_URL` | `https://domain-anda.com` | Tanpa trailing slash; redirect handoff memakai ini |
| `APP_KEY` | hasil `php artisan key:generate` | Generate baru, jangan pakai key lokal |
| `DB_CONNECTION` | `mysql` | — |
| `DB_HOST/PORT/DATABASE/USERNAME/PASSWORD` | kredensial MySQL Hostinger | — |
| `SESSION_DRIVER` | `database` | — |
| `CACHE_STORE` | `database` | Dipakai juga sebagai default `AI_LAB_CACHE_STORE` bila tidak diset |
| `QUEUE_CONNECTION` | `sync` | Aman untuk shared hosting (tidak ada worker; app ini tidak antre job) |
| `AI_LAB_CACHE_STORE` | `database` | Wajib bisa diakses proses web **dan** API (satu mesin = aman) |
| `AI_LAB_SENTIMENT_URL` | URL Vercel modul sentiment | Contoh `https://ai-sentiment-anda.vercel.app` |
| `AI_LAB_SENTIMENT_SECRET` | secret baru ≥ 32 karakter | Lihat §4 |
| `AI_LAB_HR_URL` / `AI_LAB_HR_SECRET` | URL + secret modul hr | Sama, secret beda per modul |
| `AI_LAB_IMAGE_URL` / `AI_LAB_IMAGE_SECRET` | URL + secret modul image | Sama |
| `AI_LAB_PDF_URL` / `AI_LAB_PDF_SECRET` | URL + secret modul pdf | Sama |
| `TRUSTED_PROXIES` | kosongkan | Hanya isi jika Hostinger/CDN Anda pakai proxy terbalik yang dikenal |

### 2.3 Perintah deploy (via SSH; jika tidak ada SSH, via Terminal di hPanel/File Manager + cron sekali jalan)

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate --force
php artisan migrate --force
php artisan storage:link   # jika gagal (shared hosting tanpa symlink), abaikan:
                           # route /storage/{path} sudah jadi fallback bawaan
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

Setiap deploy berikutnya: ulangi `composer install`, `migrate --force`, lalu
`optimize` (atau `optimize:clear` dulu bila mengubah `.env`).

### 2.4 Setelah deploy Laravel

1. Di **admin → Portfolios**, ubah kolom URL keempat entri AI ke domain Vercel
   masing-masing (bagian B). Slug boleh tetap (handoff punya fallback pencocokan
   URL), tapi URL wajib benar dan unik per modul.
2. Verifikasi: buka `/ai-lab`, minta akses dengan email → tab baru harus mendarat
   di aplikasi Vercel, bukan 503. Lihat §6 bila 503.
3. Opsional (Apache/LiteSpeed): tambah header keamanan di `.htaccess` dalam
   folder `public/` (setara `docker/nginx/app.conf` yang hanya berlaku di Docker):

```apache
<IfModule mod_headers.c>
  Header always set X-Content-Type-Options "nosniff"
  Header always set X-Frame-Options "SAMEORIGIN"
  Header always set Referrer-Policy "strict-origin-when-cross-origin"
  Header always set Permissions-Policy "camera=(), microphone=(), geolocation=()"
  Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
</IfModule>
```

## 3. Empat modul AI di Vercel

Buat **4 project terpisah** (satu per modul), masing-masing:

- **Root Directory**: `ai-mvps/sentiment-analyzer`, `ai-mvps/hr-screening`,
  `ai-mvps/image-caption`, `ai-mvps/pdf-rag-chatbot`.
- Framework preset: Next.js (default). Tidak perlu ubah build command.
- Tambahkan environment variables di bawah, lalu **Redeploy** (env hanya berlaku
  untuk deployment baru).

| Variable | sentiment | hr-screening | image-caption | pdf-rag-chatbot |
| --- | --- | --- | --- | --- |
| `GEMINI_API_KEY` | ✔ | ✔ | ✔ | ✔ |
| `GEMINI_MODEL` | `gemini-2.5-flash` | `gemini-2.5-flash` | `gemini-2.5-flash` | `gemini-2.5-flash` |
| `EMBEDDING_MODEL` | — | — | — | `gemini-embedding-001` |
| `NEXT_PUBLIC_LARAVEL_API_URL` | `https://domain-anda.com` (semua modul sama) | | | |
| `LARAVEL_API_URL` | `https://domain-anda.com` (semua modul sama, server-only) | | | |
| `MVP_APP_SECRET` | = `AI_LAB_SENTIMENT_SECRET` | = `AI_LAB_HR_SECRET` | = `AI_LAB_IMAGE_SECRET` | = `AI_LAB_PDF_SECRET` |
| `DATABASE_URL` | — | — | — | Neon **pooled** URL (`-pooler`, `sslmode=require`) |
| `MAX_FILE_SIZE` | — | — | — | `4000000` (batas body Vercel ±4,5 MB) |
| `DOCUMENT_TTL_MS` | — | — | — | opsional, default 30 menit |

Jangan set variabel apa pun berawalan `MVP_` selain yang di tabel — kode tidak
membacanya (flag `MVP_DEV_BYPASS_AUTH` / `MVP_SESSION_TTL_SECONDS` yang lama
sudah dihapus karena tidak pernah dipakai).

## 4. Secret production (wajib baru, jangan pakai secret lokal)

Generate 4 secret berbeda (satu per modul, ≥ 32 karakter):

```bash
php -r "foreach (['SENTIMENT','HR','IMAGE','PDF'] as \$k) echo \$k.'='.bin2hex(random_bytes(20)).PHP_EOL;"
```

Pasang tiap secret di **dua tempat yang berpasangan** (tidak sama = 401/503):

- `AI_LAB_*_SECRET` di `.env` Hostinger ↔ `MVP_APP_SECRET` di project Vercel modul tersebut.
