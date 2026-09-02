# Akses token dan pembaruan model AI MVP

Tanggal: 2 September 2026
Status: Disetujui untuk perencanaan implementasi

## Tujuan

Menyamakan akses `image-caption`, `pdf-rag-chatbot`, dan `sentiment-analyzer` dengan alur `hr-screening`. User yang mendapat akses sementara dari portal Laravel dapat membuka MVP tanpa login. Endpoint AI tetap menolak request tanpa token yang valid.

Default model ketiga MVP berubah dari `gemini-2.5-flash` menjadi `gemini-3-flash-preview`. Environment variable `GEMINI_MODEL` tetap dapat mengganti default tanpa perubahan kode.

## Scope

Perubahan mencakup:

- middleware tiga MVP;
- callback `/auth/callback` tiga MVP;
- helper validasi token tiap MVP;
- endpoint AI yang harus memvalidasi token;
- konfigurasi default model dan dokumentasi environment;
- regression test untuk kontrak token dan redirect Laravel.

Perubahan tidak mencakup:

- UI portal atau halaman MVP;
- login dan registrasi Laravel;
- desain ulang sistem access request;
- perubahan masa berlaku token 10 menit;
- perubahan `hr-screening`;
- deployment atau rotasi API key;
- refactor umum di luar jalur akses dan konfigurasi model.

## Arsitektur akses

1. User membuka halaman proyek pada `/ai-lab/{slug}`.
2. User mengirim email melalui form access request. Login tidak dibutuhkan.
3. Laravel membuat `AccessRequest` berstatus `approved`, token alfanumerik 64 karakter, dan masa berlaku 10 menit.
4. Link akses membuka `GET /ai-lab/auth/{token}` pada Laravel.
5. `AiLabAuthController` memeriksa token, status, masa berlaku, dan URL portfolio. Token valid diteruskan ke `{mvpUrl}/auth/callback?token={token}&email={email}`.
6. Callback MVP memvalidasi token dengan request server-to-server ke `GET /ai-lab/auth/{token}` memakai `redirect: manual` dan `cache: no-store`.
7. Validasi berhasil hanya jika Laravel mengembalikan redirect 3xx menuju origin MVP peminta, path callback, dan token yang sama.
8. Callback menyimpan token dalam cookie HTTP-only khusus modul selama 600 detik, lalu mengarahkan user ke `/`.
9. Middleware mengizinkan halaman jika cookie memiliki format token yang valid. Request API tanpa cookie valid menerima HTTP 401.
10. Endpoint AI memvalidasi token ke Laravel sebelum memanggil Gemini. Cookie saja tidak menjadi bukti otorisasi.

## Kontrak helper token

Ketiga MVP memakai perilaku helper yang sama:

- schema token: `/^[A-Za-z0-9]{64}$/`;
- base URL diambil dari `LARAVEL_API_URL`, dengan fallback `NEXT_PUBLIC_LARAVEL_API_URL`;
- hanya URL `http:` atau `https:` yang diterima;
- base URL dinormalisasi ke `origin`;
- request validasi memakai timeout 5 detik;
- error jaringan, timeout, redirect tidak sesuai, status non-3xx, atau token berbeda menghasilkan `false`;
- detail provider dan internal server tidak dikirim ke client.

Implementasi tetap lokal di masing-masing aplikasi karena folder `ai-mvps/shared` telah dihapus dan setiap MVP dibangun serta dideploy mandiri. Kontrak disamakan tanpa membuat package shared baru.

## Perilaku middleware

Urutan kebijakan tiap middleware:

1. Izinkan `/auth/callback`, `/api/health`, asset Next.js, dan file statis.
2. Baca cookie modul.
3. Izinkan request hanya jika token cocok dengan schema 64 karakter.
4. Untuk request `/api/*` tanpa token valid, kembalikan JSON HTTP 401.
5. Untuk halaman tanpa token valid, redirect ke `${NEXT_PUBLIC_LARAVEL_API_URL}/ai-lab`.
6. Jika URL Laravel tidak tersedia, kembalikan HTTP 503 daripada memakai localhost pada production secara diam-diam.

Middleware hanya melakukan pemeriksaan format murah. Validasi aktif ke Laravel tetap berada pada callback dan endpoint AI agar middleware edge tidak melakukan request jaringan untuk setiap asset atau navigasi.

## Endpoint API

- `sentiment-analyzer`: mempertahankan validasi aktif yang sudah ada dan memakai helper seragam.
- `image-caption`: menambah validasi aktif di route caption. Middleware tidak lagi memakai kontrak `/api/validate-token` yang tidak tersedia.
- `pdf-rag-chatbot`: route upload dan chat memvalidasi token aktif melalui helper seragam. Endpoint `/api/auth/verify` yang tidak lagi diperlukan dihapus bila tidak memiliki consumer.
- Rate-limit key tetap menggabungkan IP dan token jika implementasi modul sudah mendukungnya.
- Health endpoint tetap publik.

## Model Gemini

Default model untuk tiga MVP:

```text
gemini-3-flash-preview
```

Semua client memakai pola:

```text
process.env.GEMINI_MODEL || 'gemini-3-flash-preview'
```

`GEMINI_API_KEY` tetap server-only. Tidak ada API key dalam source, URL, cookie, atau variable `NEXT_PUBLIC_*`.

Model dipilih karena user menyetujui versi preview terbaru yang mendukung text, image, dan PDF serta tersedia pada free tier menurut dokumentasi Google:

- https://ai.google.dev/gemini-api/docs/models/gemini-3-flash-preview
- https://ai.google.dev/gemini-api/docs/pricing

Risiko: model preview dapat berubah atau dihentikan. Override `GEMINI_MODEL` menjadi jalur rollback tanpa deploy kode baru setelah environment diperbarui.

## Error handling

- Token hilang atau invalid pada callback: HTTP 401 dengan pesan generik.
- Token hilang atau invalid pada API: HTTP 401.
- Laravel auth URL tidak dikonfigurasi untuk halaman: HTTP 503.
- Laravel tidak tersedia saat validasi API: HTTP 401 agar akses gagal tertutup.
- Gemini gagal atau memberi payload invalid: pertahankan respons generik yang sudah dimiliki masing-masing modul.
- Jangan log token, email, isi PDF, gambar, atau API key.

## Testing dan verifikasi

Regression test harus mencakup:

- token tepat 64 karakter alfanumerik diterima;
- token pendek atau memiliki karakter lain ditolak;
- redirect 3xx dengan path callback dan token sama diterima;
- redirect ke path lain atau token berbeda ditolak;
- status non-3xx dan kegagalan fetch ditolak;
- route AI menolak cookie yang hilang atau invalid;
- default model yang dipakai adalah `gemini-3-flash-preview` bila `GEMINI_MODEL` kosong.

Verifikasi final per aplikasi:

- test;
- lint;
- typecheck bila script tersedia, atau `tsc --noEmit` langsung;
- production build;
- pemeriksaan diff dan whitespace.

Test tidak memanggil Laravel atau Gemini nyata. Fetch dan client provider dimock pada boundary.

## Batas kualitas

- Tidak membuat abstraction shared baru hanya untuk tiga file kecil yang hidup di aplikasi terpisah.
- Tidak mempertahankan dua kontrak validasi token.
- Tidak menambah boolean mode atau fallback localhost tersebar.
- Tidak mempercayai keberadaan cookie pada endpoint AI.
- Tidak mengubah `Dockerfile`, root `package-lock.json`, atau perubahan user lain yang sudah ada.
