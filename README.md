# BooyahIndia (Core PHP MVC Tournament App)

BooyahIndia is a premium esports tournament platform scaffold with a mobile-first UI, wallet-ready transaction architecture, and admin control tools.

## UI Theme

- Primary color: **Orange** (`#ff7a18`)
- Secondary color: **Red** (`#ff3131`)
- Dark competitive gaming style with animations, gradient highlights, glass cards, and responsive layout.

## Core Features Included

- Dual authentication foundation (Google OAuth placeholder + Mobile OTP flow)
- Tournament module:
  - 1v1, 4v4 Clash Squad, Battle Royale support
  - Join control and slot handling
  - T-4 minute room reveal logic for joined users
- Wallet and payments architecture:
  - Razorpay-ready deposit structure
  - Manual withdrawal workflow
  - User UPI update endpoint
- Redeem code market:
  - Wallet-based purchase
  - One-time reveal behavior
- Leaderboard (earnings, deposits, withdrawals)
- Admin module with role-change and audit log scaffolding
- Responsive UI for mobile, tablet, desktop + UI animations

---

## Debian 11 VPS Setup Guide

### 1) Update system

```bash
sudo apt update && sudo apt upgrade -y
```

### 2) Install required packages

```bash
sudo apt install -y nginx mariadb-server php php-fpm php-mysql php-mbstring php-xml php-curl php-zip unzip git composer
```

### 3) Create project directory and upload code

```bash
sudo mkdir -p /var/www/booyahindia
sudo chown -R $USER:$USER /var/www/booyahindia
cd /var/www/booyahindia
# git clone <your-repo-url> .
```

### 4) Configure environment

```bash
cp .env.example .env
```

Edit `.env` with real values:
- DB credentials
- Google OAuth keys
- Razorpay keys
- OTP provider key

### 5) Install PHP dependencies

```bash
composer install --no-dev --optimize-autoloader
```

### 6) Setup MariaDB/MySQL database

```bash
sudo mysql -u root -p
```

Inside SQL prompt:

```sql
CREATE DATABASE booyahindia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'booyah_user'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON booyahindia.* TO 'booyah_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Import schema:

```bash
mysql -u booyah_user -p booyahindia < database/schema.sql
```

### 7) Configure Nginx

Copy provided config as site config:

```bash
sudo cp deploy.nginx.conf /etc/nginx/sites-available/booyahindia
sudo ln -s /etc/nginx/sites-available/booyahindia /etc/nginx/sites-enabled/booyahindia
sudo rm -f /etc/nginx/sites-enabled/default
```

Open config and ensure paths + PHP-FPM version match Debian 11 installation (often `php7.4-fpm` or installed version):

- `root /var/www/booyahindia/public;`
- `fastcgi_pass unix:/run/php/php-fpm.sock;` or versioned socket

Then validate and reload:

```bash
sudo nginx -t
sudo systemctl restart nginx
sudo systemctl restart php*-fpm
```

### 8) Permissions

```bash
sudo chown -R www-data:www-data /var/www/booyahindia
sudo chmod -R 755 /var/www/booyahindia
```

### 9) HTTPS (recommended)

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

---

## Local Development (quick)

```bash
composer install
cp .env.example .env
php -S 0.0.0.0:8000 -t public
```

---

## Production Hardening Checklist

- Replace OTP debug behavior with real SMS provider delivery
- Add CSRF tokens and rate-limiting for forms/APIs
- Verify Razorpay webhook signature before wallet credit
- Encrypt room credentials at rest
- Add RBAC middleware for all admin routes
- Add centralized logging/monitoring and backups
