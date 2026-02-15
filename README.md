<p align="center">
    <img src="public/img/logo.png" alt="Parthaistic Logo" width="120">
</p>

<h1 align="center">Parthaistic Workflow & Task Management Dashboard</h1>

<p align="center">
    Dashboard internal untuk mengelola workflow produksi konten Parthaistic – dari ide, eksekusi, hingga rilis.
</p>

<p align="center">
    <img src="https://img.shields.io/badge/framework-Laravel_12-red.svg" alt="Laravel 12">
    <img src="https://img.shields.io/badge/livewire-3.x-blue.svg" alt="Livewire 3">
    <img src="https://img.shields.io/badge/tailwind-4.x-38B2AC.svg" alt="Tailwind CSS 4">
</p>

---

## Tentang Proyek

Proyek ini adalah dashboard workflow & task management untuk tim internal **PT. Parthaistic Kreasi Mendunia**.  
Fokusnya adalah mempermudah pengelolaan proses produksi konten:

- Menyatukan task, status, dan kapasitas tim dalam satu tampilan.
- Menampilkan data dari Trello dan sumber lain melalui Livewire.
- Memberikan landing page publik yang menjelaskan layanan dan keunggulan Parthaistic.

### Fitur Utama

- Landing page khusus Parthaistic (`/`)
  - Penjelasan studio, layanan utama, dan alur kerja.
  - Call-to-action ke halaman login/dashboard.
- Login & manajemen sesi
  - Halaman login dengan UI yang konsisten dengan brand Parthaistic.
  - Session driver database.
- Dashboard internal
  - Ringkasan task dan statistik produksi.
  - Integrasi data Trello melalui `TrelloService`.
  - Section `#problems_and_diagnostics` untuk memantau isu sistem dan alur kerja.
- Role-based access control
  - Middleware `EnsureUserRole` untuk membatasi akses dashboard berdasarkan peran pengguna.

### Teknologi

- Laravel 12
- Livewire 3
- Tailwind CSS 4
- PHP 8.2+
- MySQL / MariaDB

---

## Instalasi

### 1. Requirements

- PHP 8.2 atau lebih baru
- Composer
- Node.js 18+ dan NPM
- MySQL / MariaDB
- Git

### 2. Clone Repository

```bash
git clone https://github.com/Muzaki29/website-parthaistic.git
cd website-parthaistic
```

### 3. Install Dependency PHP

```bash
composer install
```

### 4. Konfigurasi Environment

Salin file `.env`:

```bash
cp .env.example .env
```

Lalu sesuaikan nilai berikut di `.env`:

```env
APP_NAME="Parthaistic Workflow"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=parthaistic
DB_USERNAME=root
DB_PASSWORD= # sesuaikan dengan database lokal
```

Jika menggunakan Laragon default:

- `DB_USERNAME=root`
- `DB_PASSWORD=` (kosong, kecuali diubah sendiri).

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Migrasi Database

```bash
php artisan migrate
# jika suatu saat seeder diaktifkan:
# php artisan db:seed
```

### 7. Install Asset Frontend

```bash
npm install
npm run build

# atau untuk pengembangan:
# npm run dev
```

### 8. Menjalankan Aplikasi

```bash
php artisan serve
```

Lalu akses:

- Landing page: `http://127.0.0.1:8000`
- Login: `http://127.0.0.1:8000/login`
- Dashboard: otomatis setelah login sesuai peran pengguna.

### 9. Menjalankan Test

```bash
php artisan test
```

Semua test bawaan harus **lulus** jika konfigurasi sudah benar.

---

## Struktur Utama

- `app/Livewire/*` – Komponen Livewire untuk dashboard, login, laporan, dll.
- `app/Http/Middleware/EnsureUserRole.php` – Middleware untuk membatasi akses berdasarkan role.
- `app/Services/TrelloService.php` – Integrasi dengan Trello.
- `resources/views/welcome.blade.php` – Landing page publik Parthaistic.
- `resources/views/livewire/*.blade.php` – Tampilan utama dashboard dan halaman-halaman internal.
- `routes/web.php` – Definisi route web dan proteksi akses.

---

## Lisensi

Proyek ini dibangun di atas Laravel yang berlisensi [MIT](https://opensource.org/licenses/MIT).  
Gunakan secara internal di lingkungan PT. Parthaistic Kreasi Mendunia atau sesuai kebutuhan organisasi Anda.
