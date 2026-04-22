# Panduan singkat: cara menyiapkan akses Trello untuk website

Hai! Dokumen ini untuk **pemula**. Tujuannya supaya tim website bisa **membaca papan (board) Trello** lewat API, tanpa perlu password akun Trello kamu.

---

## Apa yang tim developer butuhkan dari kamu?

Cukup **3 hal** ini:

| No | Apa namanya? | Kira-kira seperti apa? |
|----|----------------|-------------------------|
| 1 | **API Key** | Seperti “kunci aplikasi” dari Trello (bukan password). |
| 2 | **Token** | Seperti “izin khusus” supaya program boleh baca board milik akun yang sudah kamu setujui. |
| 3 | **Board ID** atau **link board** | Board mana yang mau ditampilkan di website. Kalau kamu kirim **link board** saja, biasanya developer bisa bantu cari ID-nya. |

---

## Sebelum mulai

1. **Login** ke Trello pakai akun yang **memang bisa buka board** yang mau dipakai (bisa lihat list dan kartu seperti biasa di browser).
2. Pastikan board itu **bukan board orang lain** yang kamu tidak punya aksesnya.

---

## Langkah 1: Ambil API Key

1. Buka dokumentasi resmi Trello tentang API (cari di Google: **Trello REST API key** atau buka halaman **API key** di situs Trello/developer).
2. Ikuti petunjuk untuk melihat atau membuat **API Key**.
3. **Salin** teks API Key-nya dan simpan sementara di tempat aman (misalnya catatan pribadi, jangan di grup WA umum).

---

## Langkah 2: Buat Token (penting)

Token itu **bukan password**, tapi **izin** supaya aplikasi boleh mengakses data board sesuai yang kamu setujui saat Trello bertanya.

1. Di halaman yang sama dengan API Key, biasanya ada tombol atau link untuk **membuat token** / **authorize**.
2. Klik dan **baca** apa yang diminta Trello, lalu **setujui** jika kamu setuju.
3. Setelah selesai, Trello akan menampilkan **token** (teks panjang). **Salin** dan simpan di tempat aman.

**Penting:** Token ini rahasia. Jangan diposting di media sosial atau dikirim ke grup besar. Kirim ke developer lewat **chat pribadi** atau cara lain yang aman.

---

## Langkah 3: Board mana yang dipakai?

Cara paling mudah untuk pemula:

1. Buka board yang kamu mau di browser (Chrome, Edge, dll).
2. **Salin URL** dari bilah alamat. Contoh bentuknya seperti:  
   `https://trello.com/b/xxxxxxxxx/nama-papan`
3. Kirim URL itu ke developer.

Kalau developer minta **Board ID** khusus (bukan URL), bilang saja kamu kirim **link board** — biasanya mereka yang akan mengambil ID-nya.

---

## Checklist sebelum kamu kirim ke developer

- [ ] Sudah punya **API Key**
- [ ] Sudah punya **Token** (setelah klik setujui di Trello)
- [ ] Sudah tahu **board mana** (minimal: kirim **link board**)
- [ ] Akun yang dipakai **bisa membuka board itu** di Trello seperti biasa

---

## Cara mengirim ke developer dengan aman

- **Jangan** kirim API Key + Token di grup kelas atau grup besar.
- Lebih baik: **chat pribadi** ke orang yang memang mengerjakan website, atau pakai cara share rahasia (misalnya fitur “share secret” di aplikasi password manager, jika punya).

Kalau token atau key pernah ketahuan orang yang tidak seharusnya, minta developer **membuat token baru** di Trello (token lama bisa dicabut/dibuat ulang sesuai panduan Trello).

---

## Setelah developer menerima (informasi tambahan)

Developer akan memasukkan nilai ke file pengaturan server (biasanya `.env`), contoh nama variabel yang dipakai di project ini:

- `TRELLO_KEY` atau `TRELLO_API_KEY` → untuk API Key  
- `TRELLO_TOKEN` atau `TRELLO_API_TOKEN` → untuk Token  
- `TRELLO_BOARD_ID` → untuk ID board  

Kamu **tidak wajib** menghafal ini; yang penting kamu mengirim **key, token, dan board (link atau ID)** ke developer.

---

## Ada pertanyaan?

Kalau ada langkah yang membingungkan, screenshot **tanpa** menampilkan token/key, atau tanyakan ke developer tim website — mereka bisa menjelaskan bagian mana yang harus diklik di layar Trello.

---

*File ini dibuat untuk dibagikan ke pemilik akun Trello (misalnya Ipiw) agar integrasi website berjalan dengan jelas dan aman.*
