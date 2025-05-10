# Web Catering - Sistem Pemesanan dan Manajemen Catering

Web catering ini adalah aplikasi berbasis web yang memungkinkan pengguna untuk melakukan pemesanan catering secara online. Aplikasi ini juga dilengkapi dengan dashboard admin untuk mempermudah pengelolaan menu, kategori, dan status pesanan.

## Fitur Utama

### Untuk Pengguna
- **Halaman Utama**: Menggunakan template gratis yang responsif dan menampilkan berbagai pilihan menu catering.
- **Pemesanan Menu**: Pengguna dapat memilih menu yang tersedia dan melakukan pemesanan secara langsung.
- **Halaman Profil**: Pengguna dapat melihat dan mengedit informasi profil, serta melihat riwayat pesanan mereka.
- **Riwayat Pesanan**: Pengguna dapat melihat status pesanan yang telah mereka buat.

### Untuk Admin
- **Dashboard Admin**: Admin memiliki kontrol penuh atas menu, kategori, dan status pesanan. Admin dapat melakukan CRUD (Create, Read, Update, Delete) untuk menu dan kategori.
- **Kelola Pengguna**: Admin dapat melihat dan mengelola pengguna serta mengatur role mereka.
- **Laporan Pendapatan**: Admin dapat melihat laporan pendapatan berdasarkan periode tertentu.

## Teknologi yang Digunakan

- **Frontend**:
  - HTML
  - CSS (menggunakan **Tailwind CSS** dan **Bootstrap** untuk desain yang responsif)
  - JavaScript (untuk interaktivitas pada halaman)
- **Backend**:
  - PHP (untuk logika server-side)
  - MySQL (untuk menyimpan data pengguna, menu, pesanan, dan laporan)
- **Tools**:
  - VS Code (IDE untuk pengembangan)
  - Git (untuk version control)

## Instalasi

### Prasyarat

Pastikan kamu sudah menginstal:
- **PHP** (versi 7.4 atau lebih tinggi)
- **MySQL**
- **Composer** (jika menggunakan package PHP tertentu)
- **Git** (untuk cloning repository)

### Langkah-langkah Instalasi

1. **Clone Repository**:
   Clone repositori project ini ke komputer lokal kamu:
   ```bash
   git clone https://github.com/Jesaurel/web-catering.git


2. **Masuk ke Direktori Project**:
   Setelah cloning selesai, masuk ke folder project:

   ```bash
   cd nama-project
   ```

3. **Setup Database**:

   * Buat database baru di MySQL, misalnya `catering_db`.
   * Import file SQL untuk membuat struktur tabel yang diperlukan. File SQL bisa ditemukan di `/database/`.

4. **Konfigurasi Environment**:

   * Salin file `.env.example` menjadi `.env` dan sesuaikan konfigurasi database dengan yang ada di lokal kamu:

   ```bash
   cp .env.example .env
   ```

   * Pastikan informasi seperti `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD`, dan `DB_DATABASE` sudah benar.

5. **Jalankan Aplikasi**:

   * Untuk menjalankan aplikasi, gunakan server PHP built-in:

   ```bash
   php -S localhost:8000
   ```

   * Buka browser dan akses aplikasi di `http://localhost:8000`.

## Penggunaan

### Bagi Pengguna:

* **Pendaftaran Pengguna**: Pengguna baru dapat membuat akun untuk memesan menu.
* **Login Pengguna**: Setelah mendaftar, pengguna dapat login dan mengakses fitur pemesanan serta riwayat pesanan mereka.

### Bagi Admin:

* **Dashboard Admin**: Admin dapat login ke dashboard untuk mengelola menu, kategori, dan status pesanan.
* **Laporan Pendapatan**: Admin dapat melihat laporan pendapatan berdasarkan kategori atau periode tertentu.

## Kredit

* **Template Halaman Utama**: Template gratis yang digunakan diambil dari [CaterServ]([link-template](https://themewagon.com/themes/caterserv/)), yang telah disesuaikan untuk kebutuhan aplikasi ini.
* **Library & Framework**: Tailwind CSS digunakan untuk styling dan membuat desain responsif yang elegan. Bootstrap juga digunakan untuk komponen UI yang lebih mudah diintegrasikan.

## Lisensi

Project ini **tidak menggunakan lisensi open-source**. Semua hak cipta atas kode dan aset tetap dimiliki oleh pengembang. Jika Anda ingin menggunakan kode atau bagian dari project ini untuk keperluan lain, harap mendapatkan izin terlebih dahulu.

## Kontak

Jika ada pertanyaan atau saran, kamu bisa menghubungi saya di:

* **Email**: [jessicaaurel283@egmail.com](mailto:jessicaaurel283@gmail.com)
* **GitHub**: [https://github.com/Jesaurel](https://github.com/Jesaurel)

