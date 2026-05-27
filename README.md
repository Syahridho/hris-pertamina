# HRIS PT. Pertamina Maintenance & Construction (PMC)

Aplikasi **Human Resource Information System (HRIS)** berbasis web dan **Progressive Web App (PWA)** yang dirancang khusus untuk manajemen kehadiran dan perizinan tenaga kerja di lingkungan **PT. Pertamina Maintenance & Construction (PMC)**.

---

## 🌟 Fitur Utama

### 1. Progressive Web App (PWA)
* Dapat diinstal langsung di layar utama smartphone (*Add to Home Screen*).
* Cepat, responsif, dan optimal untuk penggunaan lapangan.

### 2. Portal Tenaga Kerja (Manpower)
* **Slide-to-Clock (GPS Absensi)**: Mekanisme geser (*Swipe*) aman untuk melakukan *Clock In* dan *Clock Out* menggunakan deteksi lokasi GPS real-time.
* **Pengajuan Izin Kerja**: Formulir digital untuk mengajukan cuti, izin, atau surat sakit lengkap dengan unggah berkas lampiran.
* **Riwayat Kehadiran**: Rekapitulasi absensi mandiri karyawan.

### 3. Portal Supervisor
* **Dashboard Statistik Real-time**: Memantau jumlah tenaga kerja yang hadir tepat waktu, terlambat, atau absen di lokasi proyek hari ini.
* **Persetujuan Izin**: Tinjauan surat pengajuan izin dari tenaga kerja dengan **modal konfirmasi ganda** (mencegah salah tekan tombol) serta penginputan alasan penolakan secara transparan.
* **Riwayat Absensi Lokasi**: Memantau daftar riwayat kehadiran karyawan yang berada di bawah pengawasannya.

### 4. Panel Admin (Filament V3)
* **Lokalisasi Penuh**: Seluruh modul admin menggunakan Bahasa Indonesia untuk kemudahan operasional.
* **Peta Interaktif (Leaflet.js)**: Pengaturan lokasi penempatan kerja (*Placement*) dilengkapi dengan pencarian alamat (*Nominatim API*), penanda lokasi yang bisa digeser (*draggable pin*), dan visualisasi radius aman absensi (geofence).
* Manajemen lengkap untuk data Tenaga Kerja, Supervisor, Proyek, Jadwal Kerja, dan Pengajuan Izin.

---

## 🛠️ Teknologi & Stack

* **Framework**: [Laravel 10](https://laravel.com)
* **Admin Panel**: [Filament v3](https://filamentphp.com)
* **Frontend**: Blade Templates, Tailwind CSS, Alpine.js, & Vite
* **Peta**: Leaflet.js & OpenStreetMap (Nominatim API)
* **Database**: MySQL / MariaDB

---

## 🚀 Panduan Instalasi Lokal

### Prerequisites
* PHP >= 8.1
* Composer
* Node.js & NPM
* MySQL Database

### Langkah-langkah
1. **Clone Repository**
   ```bash
   git clone https://github.com/Syahridho/hris-pertamina.git
   cd hris-pertamina
   ```

2. **Instalasi Dependensi**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   * Salin berkas `.env.example` menjadi `.env`:
     ```bash
     cp .env.example .env
     ```
   * Sesuaikan kredensial database (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) pada berkas `.env`.

4. **Generate App Key & Storage Link**
   ```bash
   php artisan key:generate
   php artisan storage:link
   ```

5. **Migrasi & Seed Database**
   ```bash
   php artisan migrate --seed
   ```

6. **Kompilasi Aset Frontend**
   ```bash
   npm run build
   ```

7. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui browser di `http://localhost:8000`. Panel admin dapat diakses di `http://localhost:8000/admin`.
