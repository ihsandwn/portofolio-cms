# cPanel Deployment Checklist

For self-hosted cPanel deployment with the updated CMS Portfolio.

---

## 1. Server Requirements

- **PHP**: 8.2 or higher (Select via cPanel → MultiPHP Manager)
- **Extensions**: `ctype`, `curl`, `dom`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo`, `tokenizer`, `xml`
- **Document root**: Must point to the `public/` folder of your Laravel app

---

## 2. .env Configuration (Production)

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Use file sessions/cache on shared hosting (simpler, no extra tables)
SESSION_DRIVER=file
CACHE_STORE=file

# If using MySQL (common on cPanel)
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=your_database
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

---

## 3. Deploy Steps

1. **Upload files** (exclude `node_modules`, `.git`, `tests` to save space)
2. **Run in terminal (SSH)** or cPanel Terminal:
   ```bash
   cd /home/username/your-app
   composer install --optimize-autoloader --no-dev
   npm ci && npm run build
   php artisan key:generate   # if first deploy
   php artisan migrate --force
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Storage symlink** (optional):  
   If your host allows `symlink()`:
   ```bash
   php artisan storage:link
   ```
   If not, the fallback route in `web.php` serves files from `storage/app/public` automatically (no symlink needed).

4. **Create writable directories** (if missing):
   ```bash
   mkdir -p storage/app/public storage/app/purifier storage/framework/{sessions,views,cache} storage/logs bootstrap/cache
   chmod -R 775 storage bootstrap/cache
   ```

---

## 4. New Updates Specific to This App

| Item | Notes |
|------|-------|
| **Purifier cache** | HTML Purifier writes to `storage/app/purifier`. Ensure `storage/app` is writable (775). |
| **Security headers** | Middleware runs on every request; no extra setup. |
| **Storage route** | Fallback serves `/storage/*` when symlink is disabled; path traversal is protected. |

---

## 5. Permissions (Typical for cPanel)

```bash
# Storage and cache writable by web server
chmod -R 775 storage bootstrap/cache

# Optional: set correct owner if using SSH
chown -R username:username storage bootstrap/cache
```

---

## 6. Document Root

- cPanel → Domains → Addon Domain / Subdomain → Document Root  
- Set to: `public_html/your-app/public` (or equivalent path to `public`)

---

## 7. Optional: Simpler Cache/Session for Shared Hosting

If you prefer fewer moving parts:

- `SESSION_DRIVER=file` — no sessions table required  
- `CACHE_STORE=file` — no cache table required  

Both work out of the box with default Laravel setup.

---

## 8. Verify After Deploy

- Visit `https://yourdomain.com` — homepage loads  
- Login to admin — `/login` then redirect to `/admin/dashboard`  
- Upload an image in admin — verify it displays at `/storage/...` (symlink or fallback route)  
- Check `storage/logs/laravel.log` for errors if something fails  
