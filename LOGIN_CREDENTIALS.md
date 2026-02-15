# Dokumentasi Login & Role Website Parthaistic

## 📋 Daftar Role

Website ini memiliki **3 role** yang berbeda:

1. **Admin** - Administrator dengan akses penuh
2. **Manager** - Manager/CEO dengan akses terbatas
3. **Employee** - Karyawan/Staff dengan akses dasar

---

## 🔐 Kredensial Login

### 1. Admin
- **Email:** `admin@team.parthaistic.com`
- **Password:** `password`
- **Role:** `admin`
- **Jabatan:** Administrator
- **Akses:**
  - ✅ Dashboard
  - ✅ Reports
  - ✅ Profile
  - ✅ Employees (Kelola Karyawan)
  - ✅ Test Trello Sync

### 2. Manager
- **Email:** `rizky.yudo@team.parthaistic.com`
- **Password:** `password`
- **Role:** `manager`
- **Jabatan:** CEO
- **Akses:**
  - ✅ Dashboard
  - ✅ Reports
  - ✅ Profile
  - ❌ Employees (Tidak bisa akses)
  - ❌ Test Trello Sync (Tidak bisa akses)

### 3. Employee (Staff)
Terdapat 3 akun employee yang tersedia:

#### Employee 1 - Video Editor
- **Email:** `editor1@team.parthaistic.com`
- **Password:** `password`
- **Role:** `employee`
- **Jabatan:** Video Editor

#### Employee 2 - Creative Writer
- **Email:** `writer1@team.parthaistic.com`
- **Password:** `password`
- **Role:** `employee`
- **Jabatan:** Creative Writer

#### Employee 3 - Video Editor
- **Email:** `editor2@team.parthaistic.com`
- **Password:** `password`
- **Role:** `employee`
- **Jabatan:** Video Editor

**Akses untuk Employee:**
  - ✅ Dashboard
  - ✅ Reports
  - ✅ Profile
  - ❌ Employees (Tidak bisa akses)
  - ❌ Test Trello Sync (Tidak bisa akses)

---

## 🚀 Cara Login

1. Buka halaman login: `/login`
2. Masukkan email dan password sesuai role yang ingin digunakan
3. Klik tombol "Sign In"
4. Setelah login berhasil, Anda akan diarahkan ke Dashboard

---

## ⚠️ Catatan Penting

1. **Status Akun:** Hanya user dengan `status_akun = 'active'` yang dapat login. User dengan status inactive akan ditolak saat login.

2. **Password Default:** Semua akun menggunakan password default `password`. **Sangat disarankan untuk mengubah password setelah login pertama kali.**

3. **Akses Berdasarkan Role:**
   - **Admin** memiliki akses penuh termasuk halaman Employees
   - **Manager** dan **Employee** tidak dapat mengakses halaman Employees
   - Semua role dapat mengakses Dashboard, Reports, dan Profile

4. **Membuat User Baru:** Hanya Admin yang dapat membuat user baru melalui halaman Employees (`/employees`)

---

## 🔧 Setup Database

Jika database masih kosong, jalankan seeder untuk membuat user default:

```bash
php artisan db:seed
```

Atau jika ingin reset database dan menjalankan seeder:

```bash
php artisan migrate:fresh --seed
```

---

## 📝 Informasi Tambahan

- **URL Login:** `http://localhost/login` (atau sesuai konfigurasi server Anda)
- **URL Dashboard:** `http://localhost/dashboard`
- **Framework:** Laravel dengan Livewire
- **Database:** SQLite (default) atau MySQL (sesuai konfigurasi)

---

## 🐛 Masalah yang Sudah Diperbaiki

1. ✅ **Login Security:** Sekarang login mengecek `status_akun`. User dengan status inactive tidak bisa login.
2. ✅ **TrelloService Error:** Fixed foreign key constraint violation ketika tidak ada user di database.

---

**Dibuat:** {{ date('Y-m-d') }}
**Versi:** 1.0


