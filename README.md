# Sistem Informasi Manajemen Inventaris - Bakso Berkah Mandiri

Aplikasi berbasis web untuk pengelolaan stok bahan baku dan menu pada UMKM **Bakso Berkah Mandiri**. Proyek ini dikembangkan sebagai pemenuhan Tugas Besar mata kuliah **Cyber Security**, dengan fokus utama pada penerapan keamanan aplikasi web standar industri.

## Identitas Pengembang
* **Nama:** Nur Ismail
* **NIM:** 20240810050
* **Kelas:** TINFC 2024 04
* **Program Studi:** Teknik Informatika, Universitas Kuningan

---

## Fitur Utama Aplikasi
1. **Autentikasi & Otorisasi Pengguna:** Sistem registrasi dan login dengan pembagian hak akses (*Role-Based Access Control*) antara **Administrator** dan **Kasir**.
2. **Manajemen Inventaris (CRUD):** Pencatatan, pemantauan, pembaruan, dan penghapusan data stok menu serta bahan baku.
3. **Ekspor Laporan:** Fitur unduh rekapitulasi data inventaris beserta kalkulasi total nilai aset ke dalam format `.csv` (kompatibel dengan Microsoft Excel).
4. **Antarmuka Responsif & Ramah Pengguna:** Menggunakan Bootstrap 5 serta fitur *toggle* visibilitas kata sandi (*Vanilla JavaScript*) untuk meminimalkan kesalahan input pengguna.

---

## Teknologi yang Digunakan
* **Backend:** PHP Native (PDO Extension)
* **Database:** MySQL / MariaDB
* **Frontend:** HTML5, Bootstrap 5, Vanilla JavaScript
* **Keamanan & Konfigurasi:** Bcrypt Hashing, Environment Variables (`.env`), SSL/TLS (HTTPS)

---

## Implementasi Keamanan (Cyber Security)
Aplikasi ini dirancang untuk memitigasi berbagai celah keamanan web (*OWASP Top 10*), meliputi:
1. **Proteksi SQL Injection (SQLi):** Seluruh kueri basis data menggunakan *Prepared Statements* dengan parameter terikat (*bound parameters*) melalui PDO.
2. **Proteksi Cross-Site Scripting (XSS):** Sanitasi seluruh output data dari basis data yang ditampilkan ke antarmuka menggunakan fungsi `htmlspecialchars()`.
3. **Keamanan Kata Sandi:** Penyimpanan kata sandi menggunakan algoritma *hashing* satu arah `PASSWORD_BCRYPT` serta validasi panjang karakter minimal 8 karakter.
4. **Kontrol Akses & Anti-IDOR:** Validasi sesi dan peran pengguna (*role checking*) pada sisi server untuk mencegah akses tidak sah ke fitur sensitif (seperti penghapusan data oleh akun Kasir, mengembalikan status HTTP `403 Forbidden`).
5. **Keamanan Sesi & Rate Limiting:** Penerapan flag `HttpOnly` pada *session cookie*, regenerasi ID sesi (`session_regenerate_id(true)`) setelah login untuk mencegah *Session Fixation*, serta pembatasan percobaan login gagal (*Rate Limiting*) maksimal 5 kali per menit untuk menahan serangan *Brute Force*.
6. **Manajemen Kredensial:** Pemisahan konfigurasi sensitif basis data ke dalam file `.env` yang dikecualikan dari pelacakan Git melalui `.gitignore`.

---

## Struktur Direktori
```text
├── .env.example        # Contoh kerangka konfigurasi environment
├── .gitignore          # Daftar file yang diabaikan oleh Git (termasuk .env)
├── config.php          # Konfigurasi koneksi PDO ke basis data MySQL
├── dashboard.php       # Halaman utama sistem berdasarkan hak akses pengguna
├── edit_produk.php     # Formulir dan proses pembaruan data inventaris
├── export_csv.php      # Skrip generasi laporan inventaris ke format CSV
├── hapus_produk.php    # Proses penghapusan data (khusus Administrator)
├── login.php           # Halaman autentikasi pengguna dan rate limiting
├── logout.php          # Proses penghancuran sesi pengguna
├── produk.php          # Halaman manajemen dan penambahan data inventaris
└── register.php        # Halaman pendaftaran akun pegawai baru
