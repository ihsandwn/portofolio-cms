
# 🚀 CMS Portfolio (CMS Portofolio)

A modern, high-performance Content Management System & Personal Portfolio built with **Laravel 11**, **Livewire 3**, and **TailwindCSS**.  
Featuring a premium "Dark Blue & Electric Blue" SaaS-style Admin Dashboard, AI-ready architecture, and a fully containerized deployment strategy.

![License](https://img.shields.io/badge/license-MIT-blue.svg)
![Laravel](https://img.shields.io/badge/laravel-11.x-red.svg)
![Livewire](https://img.shields.io/badge/livewire-3.x-pink.svg)
![PHP](https://img.shields.io/badge/php-8.2+-777BB4.svg)

---

## ✨ Key Features

### 🎨 Premium UI/UX
-   **Frontend**: Futurustic "Clean Dark" theme with `Slate-950` backgrounds and neon accents.
-   **Admin Panel**: "CMS Portofolio" Dashboard featuring a detached sidebar, glassmorphism effects, and dynamic data visualization.

### 🛠 Tech Stack
-   **Backend**: Laravel 11, Spatie Permission.
-   **Frontend**: Livewire 3, Volt, TailwindCSS, Alpine.js (v3).
-   **Database**: PostgreSQL (Production) / SQLite or Postgres (Dev).
-   **Cache**: Redis.

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

2.  **Run Dev Servers**
    ```bash
    npm run dev
    php artisan serve
    ```

---

## 📦 Deployment

For detailed instructions on deploying to Staging or Production environments, please refer to the [Deployment Guide](DEPLOYMENT.md).

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
