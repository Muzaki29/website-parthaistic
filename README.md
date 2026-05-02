<div align="center">

<img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="320" alt="Laravel Logo" />

<br/><br/>

**Dashboard Activity Tracker & Monitoring Produktivitas Karyawan Remote**

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/Livewire-4E56A6?style=for-the-badge&logo=livewire&logoColor=white)](https://laravel-livewire.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Trello API](https://img.shields.io/badge/Trello_API-0052CC?style=for-the-badge&logo=trello&logoColor=white)](https://developer.atlassian.com/cloud/trello/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)

> Sistem pemantauan progres dan aktivitas kerja terpusat untuk karyawan *remote*. Dibangun menggunakan **Laravel Livewire** dengan integrasi langsung secara otomatis ke **Trello REST API**, mengeliminasi kebutuhan rekapitulasi data manual.

</div>

---

## 📋 Daftar Isi
- [Tentang Sistem](#-tentang-sistem)
- [Arsitektur Sistem](#-arsitektur-sistem)
- [Fitur Lengkap](#-fitur-lengkap)
- [Stack Teknologi](#-stack-teknologi)
- [Struktur Direktori Utama](#-struktur-direktori-utama)
- [Prasyarat Instalasi](#-prasyarat-instalasi)
- [Panduan Instalasi & Konfigurasi API](#-panduan-instalasi--konfigurasi-api)
- [Alur Sinkronisasi Trello](#-alur-sinkronisasi-trello)

---

## 🏢 Tentang Sistem
**Dashboard Activity Tracker** adalah aplikasi berbasis web yang dirancang khusus untuk **Parthaistic Digital Agency**. Sistem ini memecahkan masalah visibilitas dan pelacakan produktivitas dalam skema kerja *remote* (jarak jauh). Menggunakan integrasi **Trello REST API**, aplikasi ini menarik data mentah berupa *board*, *list*, dan *card* secara *real-time*, lalu mentransformasikannya menjadi wawasan visual yang informatif.

---

## 🏗 Arsitektur Sistem
```text
┌──────────────────────────────────────────────────────────────┐
│                       BROWSER / CLIENT                      │
│   ┌──────────────────────────────────────────────────────┐  │
│   │   Dashboard Interface (Livewire + Tailwind CSS)      │  │
│   │   - Grafik Aktivitas                                 │  │
│   │   - Tabel Progres Karyawan                           │  │
│   └────────────────────────┬─────────────────────────────┘  │
└────────────────────────────┼─────────────────────────────────┘
                             │
┌────────────────────────────▼─────────────────────────────────┐
│                      LARAVEL (Backend)                       │
│                                                              │
│  Dashboard Component                TrelloService            │
│  (Livewire Controller)              (API Abstraction Layer)  │
│                                                              │
│  Cache Layer                        Configuration (.env)     │
│  (Redis/File Cache)                 (Trello Keys & Tokens)   │
└────────────────────────────────────┬─────────────────────────┘
                                     │
                        ┌────────────▼────────────┐
                        │    TRELLO CLOUD API     │
                        │ /boards/{id}/cards      │
                        │ /boards/{id}/lists      │
                        └─────────────────────────┘
```

---

## 🌟 Fitur Lengkap

### 📊 Dasbor Utama (Main Dashboard)
- ✅ **Summary Metrics** — Menampilkan kalkulasi total tugas keseluruhan (*To Do*, *Doing*, *Done*).
- ✅ **Real-Time Render** — Antarmuka responsif tanpa *full-page reload* berkat Laravel Livewire.

### 📉 Grafik Aktivitas Harian
- ✅ **Visualisasi Tren** — Grafik interaktif yang memetakan jumlah tugas yang sedang dikerjakan vs selesai.
- ✅ **Color Coded Segments** — Palet warna kontras untuk membedakan urgensi dan status tugas secara instan.

### 👥 Tabel Progres Karyawan
- ✅ **Mapping Anggota** — Menarik data Trello *Members* dan memetakan penugasan mereka.
- ✅ **Persentase Penyelesaian** — Indikator performa individual karyawan berdasarkan *Cards* yang berada pada tahap penyelesaian (*Done*).

### ⚡ Smart API Caching
- ✅ **Anti Rate-Limit** — Sistem *caching* penahan antrian *(throttle)* otomatis. Menjaga reliabilitas aplikasi tanpa membebani limitasi *request* Trello API pada jam operasional sibuk.

---

## 🛠 Stack Teknologi

| Kategori               | Teknologi               | Keterangan                    |
| :--------------------- | :---------------------- | :---------------------------- |
| **Backend**            | Laravel 11.x            | Core PHP framework            |
| **Frontend Reactive**  | Livewire 3.x            | Reaktivitas interaktif PHP    |
| **CSS Framework**      | Tailwind CSS            | *Utility-first styling*       |
| **API Provider**       | Trello REST API         | Sumber data *cards* & *lists* |
| **HTTP Client**        | Laravel Http Facade     | Pengelolaan *requests* ke API |

---

## 📂 Struktur Direktori Utama

```text
web-parthaistic/
│
├── 📁 app/
│   ├── 📁 Livewire/
│   │   └── Dashboard.php             # ⭐ Core: Komponen penggerak antarmuka
│   │
│   ├── 📁 Services/
│   │   └── TrelloService.php         # ⭐ Core: Logika penarikan API Trello & Caching
│   │
│   └── 📁 Models/
│       └── User.php                  # Model autentikasi lokal
│
├── 📁 config/
│   └── services.php                  # Registrasi variabel layanan pihak ketiga (Trello)
│
├── 📁 resources/
│   └── 📁 views/
│       └── 📁 livewire/
│           ├── dashboard.blade.php   # Tampilan antarmuka Dashboard
│           └── ...
│
└── .env                              # Kredensial rahasia (Token & Key)
```

---

## ⚙️ Prasyarat Instalasi

| Kebutuhan       |      Versi Minimum      |
| :-------------- | :---------------------: |
| PHP             |        **8.2+**         |
| Composer        |         **2.x**         |
| Node.js & NPM   |         **18+**         |
| Web Server      | Laragon / XAMPP / Valet |

---

## 🚀 Panduan Instalasi & Konfigurasi API

### 1 — Clone Repository & Install Dependencies
```bash
git clone https://github.com/username/website-parthaistic.git
cd website-parthaistic
composer install
npm install
```

### 2 — Konfigurasi Environment & Key
```bash
cp .env.example .env
php artisan key:generate
```

### 3 — Setup Kredensial Trello
Buka file `.env` di teks editor, tambahkan kredensial dari Trello Power-Up / API Developer Portal:

```ini
TRELLO_KEY=masukkan_api_key_trello_anda
TRELLO_TOKEN=masukkan_server_token_trello_anda
TRELLO_BOARD_ID=masukkan_id_board_target
```
> **Penting**: Pastikan akun Trello yang Anda gunakan untuk meng-generate token telah memiliki izin akses (minimal *Member*) ke Board yang diincar.

### 4 — Build Assets & Jalankan Aplikasi
```bash
npm run build
php artisan serve
```
Akses di browser melalui: `http://localhost:8000`

---

## 🔄 Alur Sinkronisasi Trello

Saat komponen *Dashboard* diakses, urutan logika berikut dieksekusi oleh sistem:

```text
1. TrelloService Dipanggil
   ├── Cek ketersediaan Data di Cache
   │
   ├── [JIKA ADA] 
   │   └── Load data dari memori (Sangat Cepat & Tanpa Request)
   │
   └── [JIKA TIDAK ADA / EXPIRED]
       ├── Http::get() -> /boards/{id}/lists
       ├── Http::get() -> /boards/{id}/cards
       ├── Transformasi JSON & Mapping data Members
       └── Simpan ke Cache selama interval waktu yang ditentukan
             │
             ▼
2. Livewire Component (Dashboard.php)
   └── Menerima array data bersih -> Kirim ke View
             │
             ▼
3. Rendering Antarmuka (dashboard.blade.php)
   ├── Kalkulasi Persentase
   ├── Update Chart Aktivitas
   └── Tampilkan di Layar Browser Pengguna
```

---

<div align="center">

**Dibuat untuk efisiensi pemantauan dan akuntabilitas kerja jarak jauh.**

*Parthaistic Digital Agency — Tracking Made Simple.*

</div>
