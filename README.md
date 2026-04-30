<p align="center">
    <img src="public/img/logo.png" alt="Parthaistic Logo" width="120">
</p>

<h1 align="center">Parthaistic Workflow &amp; Task Management</h1>

<p align="center"><strong>Dashboard internal + landing publik untuk produksi konten Parthaistic</strong></p>

<p align="center">
    <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel 12">
    <img src="https://img.shields.io/badge/Livewire-3.x-FB70A9?style=flat-square" alt="Livewire 3">
    <img src="https://img.shields.io/badge/Tailwind_CSS-4.x-38B2AC?style=flat-square&logo=tailwindcss&logoColor=white" alt="Tailwind CSS 4">
    <img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP 8.2+">
</p>

> Satu aplikasi untuk **landing page**, **login berbasis role**, **dashboard**, **workflow board** (alur 9 tahap), **laporan & ekspor**, **notifikasi**, **lead inbox (admin)**, dan **sinkron data Trello** — UI konsisten mode terang/gelap dengan komponen utilitas Tailwind (`ui-card`, `ui-btn-*`, `ui-modal-*`, animasi reveal).

---

## Daftar isi

- [Tentang sistem](#tentang-sistem)
- [Arsitektur ringkas](#arsitektur-ringkas)
- [Fitur lengkap](#fitur-lengkap)
- [Stack teknologi](#stack-teknologi)
- [Struktur direktori](#struktur-direktori)
- [Skema data (ringkas)](#skema-data-ringkas)
- [Prasyarat instalasi](#prasyarat-instalasi)
- [Panduan instalasi](#panduan-instalasi)
- [Integrasi Trello](#integrasi-trello)
- [Akses per role](#akses-per-role)
- [Rute aplikasi utama](#rute-aplikasi-utama)
- [Dokumen tambahan](#dokumen-tambahan)
- [Lisensi](#lisensi)

---

## Tentang sistem

**Parthaistic Workflow** adalah aplikasi web untuk tim internal **PT. Parthaistic Kreasi Mendunia** guna mengelola **task**, **status produksi**, **notifikasi**, dan **insight operasional** dalam satu tempat. Bagian publik menjelaskan layanan studio; bagian terautentikasi memuat dashboard Livewire dengan pola UI yang seragam (kartu, input, modal, loading, empty state).

Inti nilai produk:

| Aspek | Penjelasan singkat |
| ----- | ------------------- |
| **Alur kerja** | Board 9 tahap (Drop idea → … → Finished) selaras dengan model `Task` dan laporan. |
| **Peran** | Admin, Manager, Employee — middleware `EnsureUserRole` membatasi route. |
| **Integrasi** | Trello API (opsional) untuk sinkron/monitor; kredensial lewat environment. |
| **UX** | Tailwind v4 + token komponen; dark/light; drag-and-drop board; modal CRUD seragam. |

---

## Arsitektur ringkas

```
┌─────────────────────────────────────────────────────────────┐
│                    BROWSER (Blade + Alpine)                  │
│   Landing (/)  ·  Login  ·  Livewire Dashboard & halaman   │
└──────────────────────────────┬──────────────────────────────┘
                               │
┌──────────────────────────────▼──────────────────────────────┐
│                 LARAVEL 12 · Livewire 3 · HTTP Client      │
│  Livewire: Dashboard, WorkflowBoard, Reports, TaskDetail…    │
│  Services: TrelloService, UserNotificationService…           │
│  Policies: TaskPolicy (otorisasi task)                       │
└──────────────┬──────────────────────────────┬──────────────┘
               │                              │
    ┌──────────▼──────────┐         ┌─────────▼─────────┐
    │   MySQL / SQLite     │         │   Trello REST API  │
    │ users, tasks, leads…│         │ (key/token/board)  │
    └─────────────────────┘         └────────────────────┘
```

---

## Fitur lengkap

### Landing & lead

- Landing page publik (`/`) — layout `welcome_v2` + section layanan/portfolio/proses/CTA.
- Form lead dengan throttling (`POST /leads`).

### Autentikasi & profil

- Login & register (komponen Livewire).
- Sesi disimpan di **database** (`SESSION_DRIVER=database`).
- Halaman profil pengguna.

### Dashboard & operasional

- Ringkasan task, statistik, dan area diagnostik/insight.
- Tombol/uji sinkron Trello (admin, **hanya lingkungan lokal** pada route tes).

### Workflow board

- Kolom 9 status produksi; drag-and-drop; tambah kartu cepat (Enter / Esc / “add another”).
- Modal edit/hapus task; toast feedback; loading state saat aksi Livewire.

### Laporan

- Filter, tabel, ekspor (Maatwebsite/Excel).
- CRUD task lewat modal (create / edit / delete) tanpa pindah halaman.

### Task detail

- Detail task, lampiran file, modal edit & konfirmasi hapus file.

### Notifikasi

- Daftar notifikasi pengguna; endpoint buka notifikasi.

### Admin

- Manajemen karyawan (`/employees`) — hanya **admin**.
- Inbox lead (`/admin/leads`) — update status siklus lead.

---

## Stack teknologi

| Kategori | Teknologi | Catatan |
| -------- | ----------- | ------- |
| **Backend** | Laravel 12.x | Routing, auth, HTTP client, cache DB |
| **UI interaktif** | Livewire 3.x | Halaman dashboard utama |
| **Frontend** | Tailwind CSS 4.x, Vite, Alpine.js (di Blade) | Asset build & utilitas `ui-*` |
| **Database** | MySQL / MariaDB (disarankan produksi) · SQLite (default `.env.example`) | Sesuaikan `DB_*` |
| **Excel** | maatwebsite/excel ^3.1 | Ekspor laporan |
| **PHP** | 8.2+ | Wajib |

---

## Struktur direktori

```
website-parthaistic/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/          # LeadController, TrelloController, …
│   │   └── Middleware/
│   │       └── EnsureUserRole.php
│   ├── Livewire/                 # Dashboard, WorkflowBoard, Reports, …
│   ├── Models/                   # User, Task, Lead, …
│   ├── Policies/
│   │   └── TaskPolicy.php
│   └── Services/
│       ├── TrelloService.php
│       ├── UserNotificationService.php
│       └── ActivityLogger.php
│
├── database/
│   ├── migrations/
│   └── seeders/
│
├── resources/
│   ├── css/
│   │   └── app.css               # Token Tailwind + kelas ui-*
│   └── views/
│       ├── layouts/              # landing, dashboard, navigation
│       ├── livewire/             # Blade komponen Livewire
│       └── sections/             # Hero, services, portfolio, …
│
├── routes/
│   └── web.php
│
├── docs/
│   ├── PANDUAN_CLONING_DAN_RUNNING_IPIW.md   # Laragon / tim Ipiw
│   └── PANDUAN_TRELLO_UNTUK_IPIW.md          # Setup Trello
│
├── public/
├── tests/
├── .env.example
├── composer.json                 # script: setup, dev, test
├── package.json
└── vite.config.js
```

---

## Skema data (ringkas)

Entitas utama (nama tabel dapat bervariasi sesuai migrasi):

| Area | Keterangan |
| ---- | ----------- |
| **users** | Autentikasi, role (`admin` / `manager` / `employee`), status akun, jabatan. |
| **tasks** | Judul, deskripsi, status workflow (string fleksibel), prioritas, due date, penugasan. |
| **task_files** | Lampiran pada task detail. |
| **leads** | Lead dari landing; siklus & pembaruan admin. |
| **user_notifications** | Notifikasi per user. |
| **activity_logs** | Jejak aktivitas. |
| **statistics** / **sync_logs** | Ringkasan & log sinkronisasi data. |
| **sessions, cache, jobs** | Driver database untuk sesi, cache, antrian (sesuai `.env`). |

---

## Prasyarat instalasi

| Kebutuhan | Versi minimum | Catatan |
| --------- | -------------- | ------- |
| PHP | **8.2+** | Extension umum Laravel: BCMath, Ctype, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, dll. |
| Composer | **2.x** | |
| Node.js / NPM | **18+** | Untuk Vite |
| Database | MySQL/MariaDB atau SQLite | Produksi: MySQL disarankan |
| Git | Terbaru | |

**Windows + Laragon:** panduan langkah demi langkah ada di [`docs/PANDUAN_CLONING_DAN_RUNNING_IPIW.md`](docs/PANDUAN_CLONING_DAN_RUNNING_IPIW.md).

---

## Panduan instalasi

### 1 — Clone repository

```bash
git clone https://github.com/Muzaki29/website-parthaistic.git
cd website-parthaistic
```

### 2 — Install dependencies

```bash
composer install
npm install
```

### 3 — Environment

```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan minimal:

```env
APP_NAME="Parthaistic Workflow"
APP_URL=http://127.0.0.1:8000

# Contoh MySQL (Laragon)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=parthaistic
DB_USERNAME=root
DB_PASSWORD=
```

### 4 — Migrasi & seeder (opsional)

```bash
php artisan migrate
php artisan db:seed
```

Akun contoh setelah seed: lihat [`LOGIN_CREDENTIALS.md`](LOGIN_CREDENTIALS.md).

### 5 — Build asset

```bash
npm run dev
# atau produksi:
npm run build
```

### 6 — Jalankan aplikasi

```bash
php artisan serve
```

- Landing: `http://127.0.0.1:8000`
- Login: `http://127.0.0.1:8000/login`

### Quick setup (satu perintah)

```bash
composer run setup
```

Menjalankan urutan: `composer install` → salin `.env` jika belum ada → `key:generate` → `migrate --force` → `npm install` → `npm run build`.

### Test

```bash
php artisan test
```

---

## Integrasi Trello

Isi di `.env` (lihat juga [`docs/PANDUAN_TRELLO_UNTUK_IPIW.md`](docs/PANDUAN_TRELLO_UNTUK_IPIW.md)):

```env
TRELLO_KEY=
TRELLO_TOKEN=
TRELLO_BOARD_ID=
```

Endpoint contoh:

- JSON: `GET /trello/cards`
- Blade contoh: `GET /trello/cards/view`

---

## Akses per role

| Fitur | Admin | Manager | Employee |
| ----- | :---: | :-----: | :------: |
| Dashboard, Reports, Profile, Notifications | Ya | Ya | Ya |
| Workflow board, Task detail | Ya | Ya | Ya |
| Employees | Ya | Tidak | Tidak |
| Admin leads | Ya | Tidak | Tidak |
| Tes sinkron Trello (lokal) | Ya | Tidak | Tidak |

Detail login demo: [`LOGIN_CREDENTIALS.md`](LOGIN_CREDENTIALS.md).

---

## Rute aplikasi utama

| Method | URL | Keterangan | Auth / Role |
| ------ | --- | ---------- | ----------- |
| GET | `/` | Landing publik | — |
| POST | `/leads` | Kirim lead | throttle |
| GET | `/trello/cards` | JSON kartu Trello | — |
| GET | `/trello/cards/view` | Tampilan Blade Trello | — |
| GET | `/login` | Login Livewire | — |
| GET | `/register` | Register | — |
| POST | `/logout` | Logout | session |
| GET | `/dashboard` | Dashboard | admin, manager, employee |
| GET | `/workflow-board` | Board workflow | admin, manager, employee |
| GET | `/reports` | Laporan | admin, manager, employee |
| GET | `/tasks/{id}` | Detail task | admin, manager, employee |
| GET | `/profile` | Profil | admin, manager, employee |
| GET | `/notifications` | Notifikasi | admin, manager, employee |
| GET | `/notifications/{id}/open` | Buka notifikasi | admin, manager, employee |
| GET | `/employees` | Kelola user | **admin** |
| GET | `/admin/leads` | Inbox lead | **admin** |
| PATCH | `/admin/leads/{lead}` | Update lead | **admin** |
| GET | `/test-trello-sync` | Tes sinkron (hanya `local`) | **admin** |

---

## Dokumen tambahan

| File | Isi |
| ---- | --- |
| [`docs/PANDUAN_CLONING_DAN_RUNNING_IPIW.md`](docs/PANDUAN_CLONING_DAN_RUNNING_IPIW.md) | Clone & run di localhost Laragon |
| [`docs/PANDUAN_TRELLO_UNTUK_IPIW.md`](docs/PANDUAN_TRELLO_UNTUK_IPIW.md) | Konfigurasi Trello |
| [`LOGIN_CREDENTIALS.md`](LOGIN_CREDENTIALS.md) | Role & akun demo |

---

## Lisensi

Proyek ini dibangun di atas [Laravel](https://laravel.com) (lisensi MIT).  
Gunakan sesuai kebijakan internal organisasi Anda.

---

_Dibuat untuk alur produksi yang jelas — dari ide hingga selesai._
