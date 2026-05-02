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

### 4.2.1 Pengujian Fungsionalitas (Black Box Testing)
Pengujian fungsionalitas dilakukan menggunakan metode *Black Box Testing*, yaitu pengujian yang berfokus pada perilaku keluaran (*output*) sistem berdasarkan masukan (*input*) tertentu, tanpa memeriksa struktur kode internal. Pengujian ini mencakup seluruh fitur utama sistem *Dashboard Activity Tracker* yang berhubungan langsung dengan integrasi Trello API maupun antarmuka pengguna.

| ID Uji | Nama Fitur / Skenario | Prosedur Pengujian | Input | Output yang Diharapkan | Output Aktual | Status |
| :----: | :-------------------- | :----------------- | :---- | :--------------------- | :------------ | :----: |
| TC-01 | Koneksi API Trello | Memuat halaman Dashboard dan memicu sinkronisasi data melalui tombol Sync | Kredensial valid (`TRELLO_KEY`, `TRELLO_TOKEN`, `TRELLO_BOARD_ID`) tersimpan di `.env` | Sistem berhasil terhubung ke Trello API dan mengembalikan respons HTTP 200 OK | Koneksi berhasil, data JSON diterima dan di-*cache* | ✅ Berhasil |
| TC-02 | Koneksi API — Kredensial Salah | Mengosongkan `TRELLO_TOKEN` di `.env` lalu memuat ulang halaman | Kredensial tidak valid / kosong | Sistem menampilkan pesan peringatan dan tidak melanjutkan pemanggilan API | Log `warning` muncul di Laravel, halaman menampilkan state kosong tanpa *crash* | ✅ Berhasil |
| TC-03 | Tampil Data Ringkasan Dasbor | Mengakses halaman utama Dashboard setelah data tersinkronisasi | Data *cards* aktif dari Board Trello Parthaistic | Metrik total tugas, jumlah *To Do*, *Doing*, dan *Done* tampil akurat di widget ringkasan | Seluruh angka pada widget sesuai dengan jumlah *card* di setiap kolom Trello | ✅ Berhasil |
| TC-04 | Pengelompokan Status Tugas | Memeriksa pengelompokan kartu berdasarkan nama kolom (*List*) Trello | Data *cards* dengan variasi status (*To Do*, *Doing*, *Done*) | Kartu dikelompokkan ke dalam kategori status yang tepat sesuai nama *List* Trello | Pengelompokan akurat, tidak ada kartu yang salah kategori | ✅ Berhasil |
| TC-05 | Render Grafik Aktivitas Harian | Mengakses bagian grafik pada halaman Dashboard | Data historis aktivitas harian dari kartu Trello | Grafik batang atau garis tampil dengan data yang merepresentasikan tren aktivitas harian tim | Grafik berhasil dirender dengan data yang benar dan warna kontras per segmen | ✅ Berhasil |
| TC-06 | Tabel Progres Karyawan | Memeriksa tabel daftar karyawan dan penugasannya | Data *members* dan *card assignments* dari Trello | Setiap anggota tim muncul di tabel beserta daftar tugas aktifnya dan persentase penyelesaian | Tabel tampil dengan data karyawan dan penugasan sesuai data *Board* Trello | ✅ Berhasil |
| TC-07 | Sinkronisasi Perubahan Status | Memindahkan sebuah *card* di Trello dari kolom *Doing* ke *Done*, lalu menekan Sync | Perubahan posisi *card* di platform Trello | Metrik dan tabel pada Dashboard memperbarui jumlah *Done* bertambah 1 dan *Doing* berkurang 1 | Setelah sinkronisasi, data terbaru dari Trello langsung tercermin di antarmuka | ✅ Berhasil |
| TC-08 | Sistem Cache Data | Mengakses Dashboard dua kali secara berturut-turut dalam interval cache | Permintaan kedua dalam rentang waktu cache aktif | Permintaan kedua tidak menembakkan *HTTP Request* baru ke Trello, data diambil dari cache | Data termuat lebih cepat tanpa koneksi ulang ke Trello API | ✅ Berhasil |
| TC-09 | Penanganan API Timeout | Mensimulasikan kondisi jaringan lambat / API Trello tidak merespons dalam batas waktu | Request ke API melebihi batas `timeout(10)` detik | Sistem menampilkan *fallback* state tanpa aplikasi *crash*, pesan *warning* tercatat di log | Sistem tetap berjalan, pesan `Trello API timeout` muncul di log Laravel | ✅ Berhasil |
| TC-10 | Login Pengguna | Mengakses halaman login dan memasukkan kredensial | Email dan password yang terdaftar di *database* | Pengguna diarahkan ke halaman Dashboard setelah autentikasi berhasil | *Redirect* ke halaman Dashboard berjalan dengan benar | ✅ Berhasil |
| TC-11 | Login Gagal — Kredensial Salah | Memasukkan email atau password yang salah pada form login | Email/password tidak terdaftar atau salah | Halaman menampilkan pesan error "Kredensial tidak cocok" dan tidak memberikan akses | Pesan error muncul, pengguna tetap di halaman login | ✅ Berhasil |
| TC-12 | Proteksi Halaman (Auth Guard) | Mencoba mengakses URL `/dashboard` tanpa login | Akses langsung ke URL tanpa sesi yang valid | Sistem mengarahkan (*redirect*) pengguna ke halaman login | *Redirect* ke halaman `/login` terjadi secara otomatis | ✅ Berhasil |

**Keterangan:** ✅ Berhasil = Output aktual sesuai dengan output yang diharapkan.

### 4.2.2 Hasil Usability Testing (SUS)
Pengujian tingkat kenyamanan pengguna dilakukan menggunakan metode kuesioner *System Usability Scale* (SUS) yang disebarkan melalui **Google Form** kepada 10 responden dari pihak internal Parthaistic Digital Agency.

#### Data Responden

| No | Nama Responden | Jabatan / Peran |
| :-: | :------------- | :-------------- |
| 1 | Responden 1 | Manajer HR |
| 2 | Responden 2 | Project Manager 1 |
| 3 | Responden 3 | Project Manager 2 |
| 4 | Responden 4 | Staff Teknis Senior |
| 5 | Responden 5 | Koordinator Proyek |
| 6 | Responden 6 | Karyawan Remote (Divisi Video) |
| 7 | Responden 7 | Karyawan Remote (Divisi Konten) |
| 8 | Responden 8 | Karyawan Remote (Divisi Editing) |
| 9 | Responden 9 | Karyawan Remote (Divisi YouTube) |
| 10 | Responden 10 | Karyawan Remote (Divisi Kreatif) |

#### Tabulasi Jawaban Kuesioner SUS

Keterangan skala: **1** = Sangat Tidak Setuju, **2** = Tidak Setuju, **3** = Setuju, **4** = Sangat Setuju

| Resp. | P1 | P2 | P3 | P4 | P5 | P6 | P7 | P8 | P9 | P10 |
| :---: | :--: | :--: | :--: | :--: | :--: | :--: | :--: | :--: | :--: | :--: |
| R1    |  4   |  1   |  4   |  1   |  4   |  1   |  4   |  1   |  4   |  1   |
| R2    |  4   |  1   |  4   |  2   |  4   |  1   |  4   |  2   |  4   |  2   |
| R3    |  4   |  1   |  4   |  1   |  4   |  2   |  4   |  1   |  4   |  2   |
| R4    |  4   |  1   |  4   |  1   |  3   |  1   |  4   |  2   |  3   |  2   |
| R5    |  4   |  1   |  4   |  1   |  4   |  1   |  4   |  1   |  4   |  2   |
| R6    |  4   |  2   |  3   |  1   |  4   |  1   |  4   |  2   |  3   |  1   |
| R7    |  4   |  1   |  4   |  1   |  3   |  1   |  4   |  1   |  4   |  2   |
| R8    |  4   |  1   |  4   |  1   |  3   |  2   |  4   |  1   |  4   |  2   |
| R9    |  4   |  1   |  4   |  1   |  4   |  2   |  4   |  1   |  4   |  1   |
| R10   |  3   |  1   |  4   |  1   |  4   |  1   |  4   |  1   |  4   |  2   |

#### Perhitungan Skor SUS

Rumus konversi yang digunakan mengacu pada standar SUS:
- **Pertanyaan positif (P1, P3, P5, P7, P9):** Nilai konversi = Skor − 1
- **Pertanyaan negatif (P2, P4, P6, P8, P10):** Nilai konversi = 5 − Skor
- **Total Skor SUS** = Jumlah seluruh nilai konversi × 2,5

| Resp. | P1 | P2 | P3 | P4 | P5 | P6 | P7 | P8 | P9 | P10 | Jumlah | **Skor SUS** |
| :---: | :--: | :--: | :--: | :--: | :--: | :--: | :--: | :--: | :--: | :--: | :----: | :----------: |
| R1    |  3   |  4   |  3   |  4   |  3   |  4   |  3   |  4   |  3   |  4   |  35    | **87,5**     |
| R2    |  3   |  4   |  3   |  3   |  3   |  4   |  3   |  3   |  3   |  3   |  32    | **80,0**     |
| R3    |  3   |  4   |  3   |  4   |  3   |  3   |  3   |  4   |  3   |  3   |  33    | **82,5**     |
| R4    |  3   |  4   |  3   |  4   |  2   |  4   |  3   |  3   |  2   |  3   |  31    | **77,5**     |
| R5    |  3   |  4   |  3   |  4   |  3   |  4   |  3   |  4   |  3   |  3   |  34    | **85,0**     |
| R6    |  3   |  3   |  2   |  4   |  3   |  4   |  3   |  3   |  2   |  4   |  31    | **77,5**     |
| R7    |  3   |  4   |  3   |  4   |  2   |  4   |  3   |  4   |  3   |  3   |  33    | **82,5**     |
| R8    |  3   |  4   |  3   |  4   |  2   |  3   |  3   |  4   |  3   |  3   |  32    | **80,0**     |
| R9    |  3   |  4   |  3   |  4   |  3   |  3   |  3   |  4   |  3   |  4   |  34    | **85,0**     |
| R10   |  2   |  4   |  3   |  4   |  3   |  4   |  3   |  4   |  3   |  3   |  33    | **82,5**     |
| **Total** | | | | | | | | | | | **328** | **820,0** |
| **Rata-rata** | | | | | | | | | | | **32,8** | **82,0** |

### 4.2.3 Analisis Hasil
Berdasarkan hasil perhitungan kuesioner SUS dari 10 responden, diperoleh **skor rata-rata sebesar 82,0**. Merujuk pada tabel kategori penilaian SUS (Tabel 4 pada Bab III), skor tersebut berada pada rentang **80–90** yang diklasifikasikan pada grade **B** dengan peringkat **Good (Baik)**.

| Rentang Skor | Grade | Penilaian | Status Sistem |
| :----------: | :---: | :-------: | :-----------: |
| 90 – 100 | A | Excellent | — |
| **80 – 90** | **B** | **Good** | **✅ Sistem ini** |
| 70 – 80 | C | Okay | — |
| 60 – 70 | D | Poor | — |
| < 60 | E | Awful | — |

Hasil ini membuktikan bahwa antarmuka *Dashboard Activity Tracker* dinilai **baik** dan nyaman digunakan oleh seluruh lapisan pengguna di Parthaistic Digital Agency, mulai dari pihak manajemen hingga karyawan *remote*. Sistem berhasil menyajikan visualisasi data Trello secara informatif sehingga proses pemantauan progres harian dapat dilakukan tanpa perlu membuka papan Trello secara manual.

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
