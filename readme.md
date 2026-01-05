# UndangIn - Platform Undangan Digital

**UndangIn**, Platform berbasis web yang memungkinkan pengguna membuat undangan digital secara instan, murah, dan tanpa coding. Dibangun menggunakan **PHP Native** untuk performa yang ringan dan struktur kode yang bersih.

🔗 **Live Demo:** [https://undangin.xo.je](https://undangin.xo.je)

## 🛠️ Teknologi yang Digunakan

- **Backend:** PHP (Native)
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, JavaScript (Vanilla)
- **Styling:** Custom CSS (Tanpa Framework berat seperti Bootstrap/Tailwind)
- **Deploy:** InfinityFree Hosting & Cloudflare.

## 🚀 Cara Instalasi (Localhost)

Ikuti langkah ini untuk menjalankan proyek di komputer Anda (menggunakan XAMPP):

1.  **Masuk ke Folder Server**
    Buka terminal/command prompt dan arahkan ke folder `htdocs` XAMPP Anda:

    ```bash
    cd C:\xampp\htdocs
    ```

2.  **Clone Repository**
    Download source code ke dalam folder tersebut:

    ```bash
    git clone [https://github.com/diardomarendikrista/undangin-php.git](https://github.com/diardomarendikrista/undangin-php.git)
    cd undangin-php
    ```

3.  **Setup Database**

    - Buka phpMyAdmin (`http://localhost/phpmyadmin`).
    - Buat database baru bernama `db_undangin`.
    - Import file `database.sql` yang ada di dalam folder repository ini.

4.  **Konfigurasi Koneksi**

    - Masuk ke folder `config/`.
    - Rename file `connection.example.php` menjadi `connection.php`.
    - Sesuaikan kredensial database (Default XAMPP biasanya password kosong):
      ```php
      $hostname = "localhost";
      $username = "root";
      $password = "";
      $database = "db_undangin";
      ```

5.  **Jalankan**
    - Pastikan Apache dan MySQL di XAMPP sudah di-start.
    - Buka browser dan akses: `http://localhost/undangin-php`

## 📂 Struktur Folder

```text
undangin/
├── assets/          # File CSS, JS, dan Images statis
├── auth/            # Halaman Login & Register
├── config/          # Koneksi Database
├── dashboard/       # Halaman Admin (CRUD Event)
├── uploads/         # Folder penyimpanan foto user (di-ignore git)
├── index.php        # Landing Page
├── invitation.php   # Halaman Publik Undangan
├── database.sql     # Skema Database
└── README.md
```

## 🔒 Keamanan

Aplikasi ini menerapkan standar keamanan dasar:

- SQL Injection Prevention: Menggunakan mysqli_real_escape_string.
- XSS Protection: Sanitasi output dengan strip_tags (khusus iframe map) dan htmlspecialchars.
- Session Management: Proteksi halaman dashboard dari akses tanpa login.
- Secure File Upload: Validasi ekstensi dan ukuran file gambar.
- Silahkan ditambahkan lagi jika ada yang kurang / terlewat

## 👨‍💻 Author

**Diardo Marendi Krista**

- Program Studi Informatika
- Universitas Ciputra Surabaya
- 2025

---

_Dibuat untuk memenuhi Tugas Mata Kuliah Pemrograman Web._
