# Panduan Ipiw: Cloning dan Running Project di Localhost (Laragon)

Panduan ini dibuat agar Ipiw bisa langsung menjalankan project `website-parthaistic` di komputer lokal menggunakan Laragon (Windows).

---

## 1) Prasyarat

Pastikan software berikut sudah terpasang:

- Laragon (Apache/Nginx + MySQL/MariaDB + PHP)
- Git
- Composer
- Node.js (disarankan v18+)
- NPM

Cek cepat di terminal:

```powershell
php -v
composer -V
node -v
npm -v
git --version
```

---

## 2) Clone Repository

Buka terminal (PowerShell/CMD), lalu jalankan:

```powershell
cd C:\laragon\www
git clone https://github.com/Muzaki29/website-parthaistic.git
cd website-parthaistic
```

---

## 3) Install Dependency Backend dan Frontend

```powershell
composer install
npm install
```

---

## 4) Buat dan Atur File Environment

Salin `.env` dari `.env.example`:

```powershell
copy .env.example .env
```

Lalu edit file `.env` minimal bagian berikut:

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
DB_PASSWORD=
```

Catatan Laragon default:

- `DB_USERNAME=root`
- `DB_PASSWORD=` kosong (kecuali kamu ubah sendiri)

Jika ingin pakai domain Laragon (contoh `http://website-parthaistic.test`), nanti sesuaikan juga `APP_URL`.

---

## 5) Generate App Key

```powershell
php artisan key:generate
```

---

## 6) Siapkan Database

1. Buka Laragon -> Start All.
2. Buat database baru bernama `parthaistic` (via HeidiSQL / phpMyAdmin).
3. Jalankan migrasi:

```powershell
php artisan migrate
```

Jika ingin langsung ada akun default:

```powershell
php artisan db:seed
```

Atau reset total:

```powershell
php artisan migrate:fresh --seed
```

---

## 7) Build Asset Frontend

Untuk development (otomatis recompile):

```powershell
npm run dev
```

Untuk build production:

```powershell
npm run build
```

---

## 8) Jalankan Aplikasi (pilih salah satu)

### Opsi A — Via `php artisan serve` (paling cepat)

```powershell
php artisan serve
```

Akses:

- `http://127.0.0.1:8000`
- Login: `http://127.0.0.1:8000/login`

Setelah login, halaman utama yang tersedia (tergantung role):

| URL | Keterangan singkat |
|-----|-------------------|
| `/dashboard` | Ringkasan tugas, grafik, aktivitas |
| `/team-overview` | Task selesai per hari per anggota + ringkasan bulanan + Hall of Fame |
| `/workflow-board` | Papan workflow multi-tahap |
| `/reports` | Filter & laporan tugas (bisa `?userId=` untuk filter anggota) |
| `/tasks/{id}` | Detail satu task |
| `/profile`, `/notifications` | Profil & notifikasi |
| `/employees`, `/admin/leads` | Hanya **admin** |

### Opsi B — Via Laragon Apache (domain lokal)

Karena project ada di `C:\laragon\www`, Laragon bisa langsung expose sebagai:

- `http://website-parthaistic.test`

Langkah:

1. Jalankan Laragon (Start All).
2. Aktifkan Auto Virtual Hosts (biasanya default Laragon).
3. Restart Apache jika perlu.
4. Akses `http://website-parthaistic.test`.

Jika pakai opsi ini, sebaiknya ubah:

```env
APP_URL=http://website-parthaistic.test
```

---

## 9) Akun Login Default (Jika Seeder Dijalankan)

Contoh akun:

- Admin: `admin@team.parthaistic.com` / `password`
- Manager: `rizky.yudo@team.parthaistic.com` / `password`
- Employee: `editor1@team.parthaistic.com` / `password`

Dokumen detail akun ada di `LOGIN_CREDENTIALS.md`.

---

## 10) Command Harian yang Sering Dipakai

Jalankan server backend:

```powershell
php artisan serve
```

Jalankan watcher frontend:

```powershell
npm run dev
```

Bersihkan cache Laravel:

```powershell
php artisan optimize:clear
```

Test aplikasi:

```powershell
php artisan test
```

---

## 11) Troubleshooting Cepat

**1. Error `SQLSTATE` / gagal konek DB**
- Pastikan MySQL Laragon hidup.
- Cek nama database di `.env`.
- Cek username/password DB.

**2. Error `Vite manifest not found`**
- Jalankan `npm run build` atau `npm run dev`.

**3. Perubahan CSS/JS tidak muncul**
- Stop `npm run dev`, jalankan lagi.
- Jalankan `php artisan optimize:clear`.
- Hard refresh browser (`Ctrl + F5`).

**5. Halaman tiba-tiba putih setelah klik navigasi Livewire (misalnya ganti bulan di Team Overview)**
- Pastikan sudah `npm run build` atau `npm run dev` berjalan.
- Hard refresh (`Ctrl + F5`). Jika masih bermasalah, cek konsol browser (F12) dan `storage/logs/laravel.log`.

**6. Akun tidak bisa masuk setelah di-nonaktifkan admin**
- Perilaku normal: middleware memaksa logout jika `status_akun` bukan `active`. Hubungi admin untuk mengaktifkan kembali.

---

## 12) Checklist Sukses Running

- [ ] `composer install` sukses
- [ ] `npm install` sukses
- [ ] `.env` sudah benar
- [ ] `php artisan key:generate` sukses
- [ ] `php artisan migrate` sukses
- [ ] `npm run dev` atau `npm run build` sudah jalan
- [ ] Website bisa diakses di localhost
- [ ] Login berhasil

---

Jika dibutuhkan, nanti saya bisa lanjutkan versi **super singkat 1 halaman** khusus onboarding tim (tanpa penjelasan teknis panjang), jadi tinggal copy-paste command.
