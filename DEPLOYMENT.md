# 🚀 Deployment Guide (NexusCMS)

This document outlines the strategy for deploying the NexusCMS application to Staging and Production environments using Docker.

## 🐳 Docker Architecture

We use a **Multi-Stage Dockerfile** to ensure optimization:
1.  **Base Stage**: PHP 8.3, Extensions (pdo_pgsql, redis, etc.).
2.  **Dev Stage**: Includes Composer for development.
3.  **Prod Stage**: 
    -   Compiles Assets (`npm run build`).
    -   Installs specific production dependencies (`composer install --no-dev`).
    -   Optimizes autoloader.
    -   Removes source code volume mounts (Code is baked into the image).

## 🚀 Production / Staging Deployment

### 1. Prerequisites
-   Docker & Docker Compose installed on the server.
-   Access to a PostgreSQL Database (Managed RDS or Host-based).
-   Access to a Redis instance (or use the included container).

### 2. Configuration
Create a `.env` file on your server (based on `.env.example`).
**Critical Production Settings:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=pgsql
DB_HOST=172.17.0.1  # IP of Host or External DB (Do not use localhost inside container)
DB_PORT=5432
DB_DATABASE=nexus_prod
DB_USERNAME=secure_user
DB_PASSWORD=secure_password

REDIS_HOST=redis
```

### 3. Deploying

We use a `docker-compose.prod.yml` override file to apply production settings (restart policies, port 80, no volumes).

**Command:**
```bash
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build
```

### 4. Post-Deployment Steps

The `entrypoint.sh` script automatically runs `config:cache` and `view:cache`.
Run database migrations manually or via exec to be safe:

```bash
docker-compose exec app php artisan migrate --force
```

## 🌐 Nginx Configuration

The Nginx configuration is located at `docker/nginx/app.conf`. 
-   It is mounted into the `web` container.
-   It handles PHP-FPM communication via the internal `app` service on port 9000.
-   It serves static assets directly.

## 🔄 CI/CD Recommendations

If using GitHub Actions or GitLab CI:
1.  **Build Image**: `docker build --target prod -t my-registry/nexus-cms:latest .`
2.  **Push**: Push to your container registry.
3.  **Deploy**: SSH into your server and run the `docker-compose` command above, pulling the latest image.
