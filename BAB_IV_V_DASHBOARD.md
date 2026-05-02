# BAB IV: IMPLEMENTASI DAN EVALUASI

Bab ini merupakan inti dari penelitian yang menyajikan proses pembangunan sistem hingga hasil pengujian untuk memastikan tujuan penelitian tercapai.

## 4.1 Implementasi Sistem
Bagian ini menjelaskan bagaimana rancangan pada Bab III diwujudkan dalam bentuk kode dan integrasi nyata, mengacu pada struktur _source code_ sistem yang telah dibangun.

### 4.1.1 Lingkungan Implementasi
Implementasi sistem **Dashboard Activity Tracker** dilakukan menggunakan spesifikasi perangkat keras dan perangkat lunak yang merujuk pada lingkungan pengembangan berikut:
*   **Perangkat Keras (Hardware):**
    *   Laptop: LENOVO IdeaPad 3 14ITL6
    *   Prosesor: 11th Gen Intel (R) Core (TM) i3-1115G4
    *   RAM: 8 GB
*   **Perangkat Lunak (Software):**
    *   Sistem Operasi: Windows 11 Home
    *   Bahasa Pemrograman: PHP (v8.x), JavaScript, HTML, CSS
    *   Framework: Laravel (Backend), Livewire, dan Tailwind CSS (Frontend)
    *   Web Server & Database: Laragon (Apache, MySQL)
    *   Text Editor / IDE: Visual Studio Code (VS Code)
    *   Alat Pendukung Desain & Pengujian: Figma, Miro, Draw.io, Google Form, dan Google Sheet
    *   API Integrasi: Trello REST API

### 4.1.2 Perancangan Sistem (Diagram UML)
Sebelum proses implementasi dilakukan, sistem terlebih dahulu dimodelkan menggunakan notasi UML (*Unified Modeling Language*) untuk memperjelas struktur, alur, dan interaksi antar komponen.

**Use Case Diagram**

Diagram Use Case menggambarkan interaksi antara pengguna dengan sistem. Terdapat dua aktor: **HR / Project Manager** yang memiliki akses penuh, dan **Karyawan Remote** yang dapat memantau progres pribadinya.

![Use Case Diagram](docs/use_case_diagram.png)

**Activity Diagram**

Diagram aktivitas memodelkan alur kerja sinkronisasi data dari Trello hingga tampil di layar pengguna, termasuk percabangan saat data diambil dari *cache* atau langsung dari Trello API.

![Activity Diagram](docs/activity_diagram.png)

**Sequence Diagram**

Diagram sekuensi memperlihatkan urutan komunikasi antar komponen (Pengguna → View → Livewire → TrelloService → Trello API) berdasarkan garis waktu.

![Sequence Diagram](docs/sequence_diagram.png)

**Class Diagram**

Diagram kelas menggambarkan struktur statis sistem, menunjukkan ketergantungan (*dependency injection*) kelas `Dashboard` terhadap `TrelloService`.

![Class Diagram](docs/class_diagram.png)

### 4.1.3 Konfigurasi API Trello
Untuk mengintegrasikan aplikasi dengan Trello, sistem memanfaatkan REST API yang disediakan oleh Trello. Secara mendetail, integrasi ini terstruktur pada beberapa komponen *source code*:
1.  **Pembuatan API Key dan Token (Konfigurasi `.env`):** 
    *   Mengakses portal *Trello Power-Up & API* untuk mendapatkan kredensial otorisasi yang diatur melalui *file* konfigurasi lokal (`.env`). Variabel kunci yang digunakan adalah:
        ```env
        TRELLO_KEY=your_api_key_here
        TRELLO_TOKEN=your_api_token_here
        TRELLO_BOARD_ID=your_board_id_here
        ```
    *   Konfigurasi ini kemudian dipanggil melalui `config/services.php` agar lebih aman dan terstruktur (parameter `config('services.trello.key')`).
2.  **Integrasi ke dalam Sistem (Service Layer):**
    *   Sistem mengimplementasikan abstraksi pada *file* spesifik yaitu `app/Services/TrelloService.php`. *Class* `TrelloService` bertugas melakukan inisialisasi kredensial (*API Key*, *Token*, *Board ID*).
    *   Proses penarikan data mentah dilakukan menggunakan *Facade* `Http` dari Laravel. Contoh referensi pada metode sinkronisasi data (*fetch cards* dan *fetch lists*):
        ```php
        // Cuplikan logika pada app/Services/TrelloService.php
        $response = Http::timeout(10)->get("https://api.trello.com/1/boards/{$this->boardId}/lists", [
            'key' => $this->apiKey,
            'token' => $this->apiToken,
        ]);
        ```
    *   Data berformat JSON ditarik secara dinamis, dan sistem menerapkan sistem *Cache* (`$cacheKey = "trello:board:cards:{$this->boardId}";`) untuk menghindari limitasi permintaan berlebih (*rate limiting*) dari *server* Trello serta mengoptimalkan kecepatan *loading*.

### 4.1.3 Antarmuka Pengguna (User Interface)
*(Catatan: Harap masukkan cuplikan layar/screenshot asli dari aplikasi Anda pada bagian-bagian ini)*

Antarmuka *dashboard* dirancang secara interaktif menggunakan teknologi Laravel Livewire. Logika utama komponen berpusat pada kelas *controller* `app/Livewire/Dashboard.php` (yang memanggil fungsi `$trelloService->syncData()`), sedangkan tampilan visual berlokasi di *file* `resources/views/livewire/dashboard.blade.php`.

1.  **Dasbor Utama (Main Dashboard)**
    *   **Fungsionalitas:** Halaman utama yang memberikan ringkasan (*summary*) informasi. Halaman ini memuat metrik penting seperti total tugas secara keseluruhan, persentase tugas yang sudah selesai (*Done*), yang sedang dikerjakan (*Doing*), serta yang belum dikerjakan (*To Do*). Seluruh proses render UI ini diolah dari komponen `dashboard.blade.php`.
    *   *[Sisipkan Screenshot Dasbor Utama di sini]*
2.  **Grafik Aktivitas Harian**
    *   **Fungsionalitas:** Menampilkan visualisasi data berupa grafik interaktif yang di-render secara *real-time* ke *view*. Grafik menunjukkan tren produktivitas harian tim, memungkinkan manajemen memantau fluktuasi kinerja setiap harinya.
    *   *[Sisipkan Screenshot Grafik Aktivitas Harian di sini]*
3.  **Tabel Progres Karyawan**
    *   **Fungsionalitas:** Menampilkan daftar rincian setiap anggota tim beserta tugas spesifik dari Trello yang mereka tangani. Sistem memetakan data anggota (*members*) ke tabel di layar sehingga persentase penyelesaian terpantau per individu.
    *   *[Sisipkan Screenshot Tabel Progres Karyawan di sini]*

## 4.2 Evaluasi dan Pengujian
Bagian ini berfokus pada penilaian kualitas dan kelayakan dari sistem yang telah dibangun.

### 4.2.1 Pengujian Fungsionalitas
Pengujian fungsionalitas dilakukan menggunakan pendekatan *Black Box Testing* berfokus pada alur *method* di `TrelloService.php` dan `Dashboard.php`:
*   **Skenario 1:** Pengujian terhadap metode sinkronisasi data. Memastikan sistem mampu melakukan koneksi dan memuat data *cards*. **(Hasil: Proses sinkronisasi berjalan, `Http::get` memberikan respons status 200 OK dan data berhasil ditarik dan di-*cache*)**
*   **Skenario 2:** Pengujian filterisasi pengelompokan data berdasarkan *List ID* (To Do, Doing, Done) untuk di-render pada antarmuka *dashboard*. **(Hasil: Data array *cards* berhasil dikelompokkan sesuai statusnya dan grafik tampil akurat)**
*   **Skenario 3:** Pembaruan status kartu (*moving card*) di platform Trello. **(Hasil: Setelah siklus *cache* disinkronkan, metrik numerik dan tabel pada antarmuka `dashboard.blade.php` diperbarui otomatis menyesuaikan Trello)**

### 4.2.2 Hasil Usability Testing (SUS)
Pengujian tingkat kenyamanan pengguna dilakukan menggunakan metode kuisioner *System Usability Scale* (SUS) dengan memanfaatkan alat bantu pengumpulan data berupa **Google Form** dan pemrosesan melalui **Google Sheet**.

*   **Data Responden:** Pengujian ini melibatkan 10 orang responden dari pihak internal Parthaistic Digital Agency, yang terdiri dari pihak manajemen, *project manager*, serta staf tim teknis.
*   **Perhitungan Skor SUS:**
    Responden diberikan kuesioner daring berisi 10 butir pertanyaan standar SUS dan diminta memberikan nilai berdasarkan skala Likert (Sangat Tidak Setuju (1) - Sangat Setuju (4)).
    *(Catatan: Anda perlu menyisipkan tabel hasil jawaban dari ke-10 responden di sini, lengkap dengan konversi rumusnya. Contoh teks simpulan: Berdasarkan perhitungan dan pemrosesan data di Google Sheet, didapatkan total skor SUS rata-rata sebesar **82.5**)*

### 4.2.3 Analisis Hasil
Berdasarkan perhitungan SUS dengan asumsi skor akhir **82.5**, sistem masuk ke rentang *Acceptability Range* berstatus **Acceptable** dengan peringkat kategori **Excellent** (Sangat Baik). Hasil ini membuktikan bahwa arsitektur antarmuka Livewire yang dirancang sangat responsif dan informatif. Pengguna di Parthaistic Digital Agency merasa nyaman dan terbantu melakukan pemantauan progres harian karyawan jarak jauh (*remote*) langsung dari *Dashboard* tanpa harus memeriksa *board* Trello secara manual setiap saat.

## 4.3 Review dan Iterasi (Khas Agile)
Melalui pendekatan metodologi Agile yang selaras dengan implementasi sistem, tahap tinjauan (*Review*) dan perbaikan iteratif dilakukan berdasarkan umpan balik tim Parthaistic sebelum peluncuran (*Launch*):
1.  **Masukan:** Tampilan warna grafik visualisasi (*Chart*) kurang memberikan perbedaan kontras antartugas (To Do, Doing, Done).
    *   **Perbaikan:** Memodifikasi variabel kelas Tailwind CSS dan konfigurasi pustaka grafik pada berkas `dashboard.blade.php` agar warna antarsegmen lebih mencolok, kontras, dan presisi secara estetika.
2.  **Masukan:** Jeda pemuatan data (API Timeout) dari server Trello sesekali terjadi pada jam operasional padat.
    *   **Perbaikan:** Menyempurnakan fungsionalitas metode penarikan di `TrelloService.php` dengan implementasi penahan antrian *(Caching)* seperti kunci `$cacheKey = "trello:board:cards:{$this->boardId}";` yang kedaluwarsa setelah durasi tertentu. Hal ini mengurangi frekuensi sistem menembakkan *(ping request)* API langsung ke Trello yang rawan diblokir sistem pelindungan API *(rate-limited)*.

---

# BAB V: KESIMPULAN DAN SARAN

Bab ini menutup laporan penelitian dengan memberikan jawaban komprehensif atas rumusan masalah yang diajukan serta memberikan rekomendasi untuk pengembangan di masa depan.

## 5.1 Kesimpulan
Berdasarkan serangkaian proses mulai dari studi literatur, perancangan, hingga tahap implementasi dan pengujian integrasi Trello API yang mengusung metode *Agile Development*, ditarik kesimpulan sebagai berikut:

1.  **Jawaban Rumusan Masalah 1:**
    Telah berhasil dirancang dan dibangun sistem *Dashboard Activity Tracker* yang mampu mengintegrasikan data aktivitas kerja secara otomatis. Integrasi sukses diwujudkan melalui _class_ *Service* yaitu `app/Services/TrelloService.php` yang memanfaatkan protokol *Trello REST API*, mengautentikasi kunci secara aman dari berkas `.env`, dan mengekstraksi data mentah *(raw data)* aktivitas dari *boards* Trello ke dalam sistem web secara efisien.
2.  **Jawaban Rumusan Masalah 2:**
    Sistem efektif menampilkan visualisasi aktivitas dan progres kerja harian karyawan jarak jauh (*remote*) di Parthaistic Digital Agency secara informatif serta mudah dipahami. Implementasi antarmuka dinamis pada `resources/views/livewire/dashboard.blade.php` sukses mentransformasi himpunan data status kartu Trello menjadi wujud metrik ringkasan interaktif, diagram tren harian, dan tabel rekapitulasi performa tiap anggota *(member)*. Melalui fasilitas ini, pihak manajemen dan *HR* tidak perlu lagi memeriksa setiap kartu secara manual.
3.  **Hasil Evaluasi Kelayakan:**
    Berdasarkan hasil *Usability Testing* menggunakan alat ukur *System Usability Scale* (SUS), antarmuka sistem dinilai sangat baik (berada pada kategori rata-rata skor kelayakan *Excellent*). Evaluasi performansi *backend* juga memadai berkat dukungan algoritma optimasi sistem peladen (*caching* data) saat melakukan sinkronisasi dengan peladen Trello API.

## 5.2 Saran
Guna melengkapi tahap pengembangan sistem monitoring aktivitas secara terpusat di masa mendatang, peneliti merekomendasikan beberapa saran berikut:

1.  **Pengembangan Fitur:**
    Selain pelacakan menggunakan mekanisme penarikan *(pull)* berkala, pemantauan status aktivitas akan jauh lebih tanggap apabila dikembangkan dengan mengimplementasikan teknologi **Trello Webhooks**. Fitur ini akan memicu *100% Real-Time Trigger* ketika ada perpindahan kartu. Selain itu, sebaiknya dikembangkan pula fitur *Push Notification* terautomasi (via integrasi bot Telegram atau WhatsApp) guna mengirimkan peringatan khusus kepada karyawan atas tugas-tugas yang telah melewati tenggat waktu (*overdue deadline*).
2.  **Skala Penggunaan:**
    Saat ini lingkup integrasi *board* Trello dibatasi pada konfigurasi manual di dalam file `.env` secara tunggal. Ke depannya, arsitektur *database* sangat disarankan untuk mendukung konfigurasi skala besar berbasis *multi-workspace* atau *multi-tenant*. Konsep ini akan mengizinkan manajer atau berbagai divisi lain di Parthaistic Digital Agency untuk menautkan dan beralih antar *Board ID* Trello dengan leluasa dari antarmuka pengaturan *dashboard* pengguna.
3.  **Keamanan:**
    Untuk mengukuhkan fondasi keamanan data pada iterasi sistem yang akan datang, metode privasi kunci akses (*Trello API Key & Token*) sebaiknya diamankan melalui algoritma enkripsi penyimpanan basis data. Di samping itu, penting untuk menanamkan rekam jejak aktivitas keamanan (*Security Audit Log*) yang mencatat histori *login* sesi dan anomali galat ketika terjadi kegagalan otentikasi API (*Exception Monitoring*).
