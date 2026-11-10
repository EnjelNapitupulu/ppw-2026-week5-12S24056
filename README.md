# SIPUS-Del – Week 9: Web Services & REST API

**Mata Kuliah:** Pengembangan dan Pemrograman Web  
**Proyek:** Sistem Informasi Perpustakaan Digital (SIPUS-Del)  
**NIM:** 12S24056  
**Branch:** `week9-rest-api`

---

## 1. Deskripsi Proyek

SIPUS-Del merupakan aplikasi sistem informasi perpustakaan yang dikembangkan menggunakan Laravel. Pada praktikum Week 9, proyek ini dikembangkan dengan menerapkan konsep Web Services dan REST API untuk mendukung komunikasi data antarsistem serta pengelolaan layanan perpustakaan secara terstruktur.

Pengembangan berfokus pada implementasi REST API, autentikasi menggunakan Laravel Sanctum, pengelolaan data buku dan peminjaman, serta integrasi dengan layanan eksternal.

## 2. Fitur yang Dikembangkan

### A. REST API Provider
- Menyediakan layanan API untuk mengakses data buku.
- Menggunakan Laravel API Resources untuk mengatur format respons JSON.
- Mendukung pengelolaan data melalui endpoint API.
- Menerapkan pagination untuk pengambilan data.
- Menggunakan HATEOAS untuk menyediakan tautan navigasi pada respons API.

### B. Authentication dengan Laravel Sanctum
- Menggunakan Laravel Sanctum untuk autentikasi berbasis Bearer Token.
- Menyediakan mekanisme login untuk memperoleh token akses.
- Menggunakan token untuk mengakses endpoint yang dilindungi.
- Mendukung pencabutan atau penghapusan token.

### C. API Peminjaman dan Pengembalian
- Menyediakan endpoint untuk proses peminjaman buku.
- Menyediakan endpoint untuk proses pengembalian buku.
- Menggunakan database transaction untuk menjaga konsistensi data.
- Menggunakan `lockForUpdate` untuk menghindari konflik perubahan stok secara bersamaan.
- Memvalidasi ketersediaan stok sebelum peminjaman dilakukan.

### D. SOAP Web Service
- Mengimplementasikan layanan SOAP menggunakan `LibrarySoapController`.
- Menyediakan kontrak layanan melalui file WSDL.
- Mendukung proses pemeriksaan status clearance perpustakaan.
- Menangani respons SOAP dan SOAP Fault.

### E. REST API Consumer
- Mengintegrasikan aplikasi dengan layanan API eksternal.
- Menggunakan Laravel HTTP Client untuk mengirim HTTP request.
- Mengambil metadata buku dari layanan eksternal.
- Memproses respons API untuk digunakan dalam aplikasi.

## 3. Teknologi yang Digunakan

| Teknologi | Kegunaan |
|---|---|
| PHP | Bahasa pemrograman |
| Laravel | Framework backend |
| MySQL/Database | Penyimpanan data |
| Laravel Sanctum | Autentikasi API |
| Laravel HTTP Client | Konsumsi API eksternal |
| REST API | Komunikasi data berbasis HTTP |
| SOAP | Implementasi Web Service |
| JSON | Format pertukaran data |
| Postman | Pengujian API |
| OpenAPI | Dokumentasi spesifikasi API |
| Git & GitHub | Version control |

## 4. Persyaratan Sistem

Pastikan perangkat telah memiliki:
- PHP dengan versi yang kompatibel dengan proyek Laravel.
- Composer.
- Laravel.
- MySQL atau database yang digunakan dalam konfigurasi proyek.
- Git.
- Postman.
- Web server lokal, seperti Laragon.

## 5. Instalasi dan Konfigurasi

### 5.1 Clone Repository

```bash
git clone https://github.com/EnjelNapitupulu/ppw-2026-week5-12S24056.git
```

Masuk ke direktori proyek:

```bash
cd ppw-2026-week5-12S24056
```

Pindah ke branch Week 9:

```bash
git checkout week9-rest-api
```

### 5.2 Instalasi Dependency

```bash
composer install
```

### 5.3 Konfigurasi Environment

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Sesuaikan konfigurasi database pada file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan nilainya dengan konfigurasi database lokal.

### 5.4 Migrasi Database

```bash
php artisan migrate
```

### 5.5 Menjalankan Aplikasi

```bash
php artisan serve
```

Aplikasi dapat diakses melalui:

```text
http://127.0.0.1:8000
```

## 6. Dokumentasi API

Dokumentasi API disiapkan menggunakan spesifikasi OpenAPI dan koleksi Postman.

File dokumentasi yang digunakan:

```text
docs/
├── openapi-sip-v1.yaml
└── SIPUS-Del-API-v1.postman_collection.json
```

Koleksi Postman dapat digunakan untuk menguji endpoint API, termasuk autentikasi, pengambilan data buku, peminjaman, dan pengembalian.

## 7. Pengujian

Pengujian dilakukan untuk memastikan setiap layanan berjalan sesuai fungsinya.

Skenario pengujian meliputi:

1. Menguji layanan SOAP dan respons SOAP Fault.
2. Melakukan login dan memperoleh Bearer Token.
3. Mengakses endpoint API menggunakan token.
4. Mengambil data katalog buku dengan pagination.
5. Memeriksa tautan HATEOAS pada respons API.
6. Menguji penambahan data dengan respons HTTP 201 Created.
7. Menguji perbedaan metode PUT dan PATCH.
8. Menguji proses peminjaman dan pengembalian buku.
9. Memastikan peminjaman ditolak ketika stok buku kosong.
10. Menguji integrasi API eksternal untuk memperoleh metadata buku.

## 8. Struktur Dokumentasi

```text
SIPUS-Del/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Resources/
│   └── Traits/
├── routes/
│   └── api.php
├── docs/
│   ├── openapi-sip-v1.yaml
│   └── SIPUS-Del-API-v1.postman_collection.json
├── database/
│   └── migrations/
├── bootstrap/
│   └── app.php
└── README.md
```

Struktur di atas merupakan gambaran bagian-bagian utama proyek. Detail direktori dapat berbeda sesuai implementasi aktual.

## 9. Branch dan Version Control

Pengembangan praktikum Week 9 dilakukan pada branch:

```bash
week9-rest-api
```

Untuk melihat daftar branch:

```bash
git branch
```

Untuk melihat riwayat commit:

```bash
git log --oneline
```

Untuk mengirim perubahan ke GitHub:

```bash
git add .
git commit -m "deskripsi perubahan"
git push
```

## 10. Repository

GitHub Repository:

https://github.com/EnjelNapitupulu/ppw-2026-week5-12S24056

Branch Week 9:

https://github.com/EnjelNapitupulu/ppw-2026-week5-12S24056/tree/week9-rest-api

---

**Dikembangkan oleh:**  
Enjel Napitupulu  
NIM: 12S24056  
Institut Teknologi Del