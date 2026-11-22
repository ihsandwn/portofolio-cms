
# 🚀 CMS Portfolio (CMS Portofolio)

A modern, high-performance Content Management System & Personal Portfolio built with **Laravel 12**, **Livewire 4**, and **TailwindCSS**.  
Featuring a light "Technical Blueprint" public site, an AI Lab with four Next.js demo apps behind a server-to-server session exchange, and a fully containerized deployment strategy.

![License](https://img.shields.io/badge/license-MIT-blue.svg)
![Laravel](https://img.shields.io/badge/laravel-12.x-red.svg)
![Livewire](https://img.shields.io/badge/livewire-4.x-pink.svg)
![PHP](https://img.shields.io/badge/php-8.3+-777BB4.svg)

---

## ✨ Key Features

### 🎨 Premium UI/UX
-   **Public site**: Light "Technical Blueprint" theme (Inter + Space Grotesk, sharp corners, blueprint dot grid), bilingual EN/ID, keyboard-navigable with visible focus and reduced-motion support.
-   **Portfolio**: Table-first selected-work index; case-study pages separate summary, context/role, approach/evidence, and verified links. Measured outcomes are shown only when documented.
-   **AI Lab**: Catalogue of four Next.js demo apps (sentiment-analyzer, hr-screening, image-caption, pdf-rag-chatbot). Access is granted via a CSRF-protected POST handoff that mints a 60-second single-use code; apps exchange it server-to-server for an opaque session. See [docs/ai-lab-auth.md](docs/ai-lab-auth.md).

### 📚 Content System
-   **Bilingual Support**: Full English & Indonesian localization for Blog Posts.
-   **Rich Content Builder**: Drag-and-drop support for Text, Galleries, Videos, and PDFs.
-   **Smart Storage**: Native CPanel compatibility mode (`/storage` route proxy) to bypass hosting restrictions.

### 🛠 Tech Stack
-   **Backend**: Laravel 12, Spatie Permission.
-   **Frontend**: Livewire 4, Volt, TailwindCSS, Alpine.js (v3).
-   **Database**: PostgreSQL / SQLite / MySQL.
-   **Storage**: Local Filesystem with PHP-based serving fallback.

---

## 🛠 Local Development Guide

### Option 1: Docker (Recommended)

1.  **Configure `.env`**
    ```env
    DB_CONNECTION=pgsql
    DB_HOST=host.docker.internal
    DB_PORT=5432
    REDIS_HOST=redis
    ```

2.  **Start Containers**
    ```bash
    docker-compose up -d
    ```

3.  **Access App**
    Open `http://localhost:8080`

### Option 2: Standard (No Docker)

1.  **Install Dependencies**
    ```bash
    composer install && npm install
    ```

2.  **Run dev stack**
    ```bash
    composer dev
    ```
    Uses **`npm run watch`** so assets are built to `public/build` (same origin as Laravel). If you need Vite HMR on port 5173 instead, run **`composer dev:vite`** (may conflict with strict CSP — see `docs/CSP-TROUBLESHOOTING.md`).

    Or manually: `npm run watch` + `php artisan serve`, or `npm run dev` + `php artisan serve` for full Vite.

---

## 📦 Deployment

For detailed instructions on deploying to Staging or Production environments, please refer to the [Deployment Guide](DEPLOYMENT.md). For the current production topology (Laravel on Hostinger + 4 AI modules on Vercel + MySQL, latest code), see [docs/DEPLOYMENT-PRODUCTION.md](docs/DEPLOYMENT-PRODUCTION.md).

> [!NOTE]
> See **[DEPLOYMENT.md](DEPLOYMENT.md)** for Docker Production strategies, Nginx config, and Environment setup.

---

## 📂 Project Structure

```
├── app/
│   ├── Livewire/             # Full-page components & Actions
│   └── View/Components/      # Blade Components
├── docker/                   # Docker Utils (Nginx, Entrypoint)
├── resources/
│   ├── css/                  # Tailwind Entry
│   └── views/                # Blade Templates
├── docker-compose.yml        # Local Dev Config
└── docker-compose.prod.yml   # Production Overrides
```

---

**Built with ❤️ by Ichsan Dwi Nugraha**
