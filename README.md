# WooCouponHistory

**WooCouponHistory** adalah plugin khusus WooCommerce yang memungkinkan pemilik toko membatasi penggunaan kupon hanya untuk pelanggan yang memiliki riwayat pembelian produk tertentu.

Plugin ini dirancang untuk meningkatkan loyalitas pelanggan dengan memberikan penghargaan berupa diskon eksklusif kepada pembeli setia produk spesifik.

## 🚀 Fitur Utama

* **Tab Kustom Dedicated**: Memisahkan pengaturan riwayat pembelian ke dalam tab "Coupon History" agar tidak bercampur dengan pembatasan standar WooCommerce.
* **Pencarian Produk Berbasis AJAX**: Memungkinkan pemilihan produk syarat dengan cepat, bahkan pada toko dengan ribuan produk.
* **Validasi Riwayat Pembelian**: Sistem secara otomatis mengecek database pesanan pelanggan sebelum mengizinkan penggunaan kupon.
* **Arsitektur Modular**: Kode dipisahkan menjadi kelas `Admin` dan `Validator` untuk performa dan skalabilitas yang lebih baik.
* **Kompatibilitas PHP 8.2+**: Memanfaatkan fitur terbaru PHP untuk keamanan dan kecepatan maksimal.

## 📋 Persyaratan Sistem

* **WordPress**: 6.8 atau lebih baru.
* **WooCommerce**: Aktif di situs Anda.
* **PHP**: Versi 8.2 atau lebih tinggi.

## 📥 Cara Mengunduh

Anda dapat mengunduh versi terbaru plugin ini melalui repositori resmi di GitHub:

* **GitHub Repository**: [https://github.com/wichaksono/WooCouponHistory](https://github.com/wichaksono/WooCouponHistory)
* **Halaman Releases (Versi Stabil)**: [https://github.com/wichaksono/WooCouponHistory/releases](https://github.com/wichaksono/WooCouponHistory/releases)

**Instruksi:**

1. Buka halaman **Releases**.
2. Unduh file `.zip` dari versi terbaru yang tersedia.

## 🛠️ Instalasi

### Cara 1: Lewat Dashboard WordPress (Rekomendasi)

1. Pastikan Anda sudah mengunduh file `.zip` dari halaman Releases GitHub.
2. Masuk ke area Admin WordPress.
3. Buka menu **Plugins > Add New Plugin**.
4. Klik tombol **Upload Plugin** di bagian atas.
5. Pilih file `.zip` tersebut, lalu klik **Install Now**.
6. Setelah selesai, klik **Activate Plugin**.

### Cara 2: Lewat FTP/File Manager

1. Buka direktori `/wp-content/plugins/`.
2. Unggah dan Extract file `woocouponhistory.zip` ke dalam folder tersebut.
3. Masuk ke Dashboard WordPress, buka **Plugins > Installed Plugins**.
4. Cari **WooCouponHistory** dan klik **Activate**.

## 📖 Cara Penggunaan

1. Pergi ke menu **Marketing > Coupons**.
2. Buat kupon baru atau edit kupon yang sudah ada.
3. Di bagian **Coupon Data**, cari tab **Coupon History** di barisan menu sebelah kiri.
4. Pada field **Required Past Purchase**, cari dan pilih satu atau beberapa produk yang wajib dibeli pelanggan sebelumnya.
5. Klik **Publish** atau **Update**.

> **Catatan:** Pelanggan **wajib login** agar sistem dapat memverifikasi riwayat pesanan mereka. Jika pelanggan tidak login atau belum pernah membeli produk terpilih, pesan error kustom akan muncul di halaman keranjang/checkout.

## 👨‍💻 Kontribusi & Struktur Kode

Plugin ini mengikuti standar **PSR-12** dan menggunakan pembagian tanggung jawab kelas:

* `NeonWebId\WooCouponHistory\Admin`: Menangani logika UI di dashboard.
* `NeonWebId\WooCouponHistory\Validator`: Menangani logika pengecekan di sisi frontend.
* `NeonWebId\WooCouponHistory\Main`: Bootstrapper utama plugin.

## 📄 Lisensi

Plugin ini bersifat Open Source di bawah lisensi **GPL-3.0-or-later**.

Developed by [NEON WEB ID](https://neon.web.id) © 2026
