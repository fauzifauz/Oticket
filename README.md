# OTicket - Enterprise IT Service Management & AI Command Center

Selamat datang di dokumentasi resmi **OTicket**, solusi manajemen layanan TI (ITSM) premium yang dirancang untuk perusahaan modern. Sistem ini tidak hanya mencatat keluhan, tetapi juga menganalisisnya menggunakan **Kecerdasan Buatan (AI)** untuk membantu manajemen mengambil keputusan strategis.

---

## 🌟 Tentang Sistem (System Overview)

OTicket dibangun dengan arsitektur **Laravel 12 (PHP 8.2+)** yang handal, dipadukan dengan antarmuka modern berbasis **TailwindCSS** dan analitik cerdas yang ditenagai oleh **Python**.

### Filosofi Desain
Sistem ini mengutamakan **Transparansi**, **Efisiensi**, dan **Estetika**.
1.  **Transparansi**: Karyawan bisa melihat siapa yang mengerjakan tiket mereka dan memberikan rating terbuka.
2.  **Efisiensi**: Agen Support dibantu oleh indikator SLA dan notifikasi realtime agar tidak ada tiket yang terlewat.
3.  **Estetika**: Antarmuka "Glassmorphism" yang futuristik untuk meningkatkan pengalaman pengguna (UX).

### Modul Utama

#### 1. 🏢 Employee Portal (User)
Gerbang utama bagi seluruh karyawan perusahaan.
*   **Ticket Submission**: Form pengaduan masalah dengan kategori spesifik, prioritas, dan lampiran bukti (gambar/dokumen).
*   **Real-time Tracking**: Memantau status tiket (`Open` -> `In Progress` -> `Resolved`) secara langsung.
*   **Feedback & Rating**: Memberikan penilaian bintang (1-5) dan komentar kepuasan setelah masalah selesai. Data ini langsung mempengaruhi KPI tim support.

#### 2. 🛡️ Support Dashboard (Agent)
Workspace khusus untuk tim teknis menangani insiden.
*   **Smart Queue**: Daftar tiket yang diurutkan berdasarkan prioritas dan deadline SLA.
*   **SLA Monitoring**: Indikator hitung mundur visual. Warna berubah (Hijau -> Kuning -> Merah) saat mendekati tenggat waktu.
*   **Live Feedback Ticker**: Teks berjalan (Marquee) yang menampilkan komentar karyawan terbaru secara real-time, memotivasi tim untuk bekerja lebih baik.
*   **Personal Analytics**: Grafik performa individu (Rata-rata rating, waktu respon).

#### 3. 🧠 AI Command Center (Admin)
Pusat kontrol bagi Manajer IT / Administrator.
*   **AI Pattern Recognition**: Skrip Python (`mining_service.py`) berjalan di latar belakang untuk menganalisis ribuan tiket dan mengelompokkannya (Clustering) menjadi "Top IT Issues".
*   **Deep Analytics**: Laporan mendalam tentang distribusi masalah per departemen, beban kerja tim, dan tren kerusakan perangkat keras/lunak.
*   **User Management**: Mengelola akun, reset password, dan hak akses pengguna secara terpusat.

#### 4. 📑 Reporting Engine
Mesin pelaporan otomatis untuk kebutuhan audit.
*   **Format**: Tersedia dalam bentuk PDF (Siap Cetak) dan CSV (Olah Data Excel).
*   **Periode**: Laporan General, Bulanan, dan Tahunan.
*   **Ledger Annex**: Lampiran detail yang mencantumkan setiap tiket yang masuk dalam periode laporan, menjamin transparansi data 100%.

---

## � Spesifikasi Teknis (Tech Stack)

Sistem ini membutuhkan lingkungan server dengan spesifikasi berikut:

| Komponen | Versi Minimum | Deskripsi |
| :--- | :--- | :--- |
| **PHP** | 8.2 | Bahasa pemrograman utama (Backend). |
| **Laravel** | 12.x | Framework PHP untuk struktur aplikasi. |
| **MySQL** | 8.0 / MariaDB | Database Relasional untuk menyimpan data tiket. |
| **Python** | 3.8+ | Bahasa untuk modul AI/Data Mining. |
| **Node.js** | 18+ | Runtime untuk build tool frontend (Vite). |
| **Composer** | 2.0+ | Manajer paket PHP. |

**Python Libraries yang Digunakan:**
*   `pandas`: Manipulasi dan pembersihan data tiket.
*   `scikit-learn`: Algoritma K-Means Clustering untuk AI.
*   `mysql-connector-python`: Koneksi langsung script Python ke Database MySQL.

---

## �️ Panduan Instalasi Lengkap (Step-by-Step)

Ikuti instruksi ini secara berurutan untuk menghindari error.

### Tahap 1: Persiapan Sistem
Pastikan Anda sudah menginstall Git, PHP, Composer, dan MySQL di komputer/server Anda.

1.  **Clone Repository**
    ```bash
    git clone https://github.com/username/OTicket.git
    cd OTicket
    ```

2.  **Install Dependensi Backend (Laravel)**
    ```bash
    composer install --optimize-autoloader --no-dev
    ```
    *Gunakan `--no-dev` jika ini adalah server produksi.*

3.  **Install Dependensi Frontend**
    ```bash
    npm install
    ```

### Tahap 2: Konfigurasi Database & Environment

1.  **Duplikat File Konfigurasi**
    ```bash
    cp .env.example .env
    ```

2.  **Edit File .env**
    Buka file `.env` dengan text editor (Notepad/VIsual Studio Code). Sesuaikan bagian ini:
    ```env
    APP_NAME=OTicket
    APP_URL=http://localhost:8000

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=oticket      <-- NAMA DATABASE ANDA
    DB_USERNAME=root         <-- USERNAME DATABASE
    DB_PASSWORD=             <-- PASSWORD DATABASE
    ```

3.  **Generate Kunci Enkripsi**
    ```bash
    php artisan key:generate
    ```

4.  **Siapkan Database**
    Buat database kosong bernama `oticket` di MySQL Anda, lalu jalankan migrasi:
    ```bash
    php artisan migrate --seed
    ```
    *Perintah ini akan membuat struktur tabel DAN mengisi data awal (Admin, Kategori Tiket, SLA).*

### Tahap 3: Instalasi AI Service (Python)

Fitur "Top IT Issues" di dashboard Admin tidak akan muncul tanpa langkah ini.

1.  **Masuk ke Direktori AI**
    ```bash
    cd app/AI
    ```

2.  **Buat Virtual Environment (Wajib)**
    Agar library Python tidak "mengotori" sistem utama Anda.
    ```bash
    python3 -m venv venv
    ```

3.  **Aktifkan Virtual Environment**
    *   **Untuk Windows (Command Prompt):**
        ```cmd
        venv\Scripts\activate
        ```
    *   **Untuk Linux / Mac / Git Bash:**
        ```bash
        source venv/bin/activate
        ```

4.  **Install Library Pendukung**
    ```bash
    pip install pandas scikit-learn mysql-connector-python
    ```

5.  **Keluar dari Direktori AI**
    ```bash
    deactivate
    cd ../..
    ```

### Tahap 4: Menjalankan Aplikasi

Untuk lingkungan developement lokal, Anda perlu menjalankan **3 terminal** secara bersamaan agar semua fitur berjalan lancar.

*   **Terminal 1 (Web Server)**: Menjalankan aplikasi web.
    ```bash
    php artisan serve
    ```
    Akses di browser: `http://127.0.0.1:8000`

*   **Terminal 2 (Frontend Build)**: Mengkompilasi file CSS/JS secara real-time.
    ```bash
    npm run dev
    ```

*   **Terminal 3 (Job Worker)**: Memproses pengiriman email notifikasi di latar belakang.
    ```bash
    php artisan queue:work
    ```

---

## 📂 Struktur Direktori Penting

Memahami struktur folder akan membantu Anda jika ingin memodifikasi sistem.

*   **`app/Http/Controllers/`**
    *   `Admin/`: Semua logika untuk halaman Admin (Laporan, Kelola User).
    *   `SupportController.php`: Logika dashboard khusus tim Support.
    *   `TicketController.php`: Logika pembuatan tiket oleh karyawan.
*   **`app/AI/`**
    *   `mining_service.py`: "Otak" AI yang menganalisis cluster masalah.
    *   `venv/`: Folder environment Python.
*   **`database/`**
    *   `migrations/`: File pembentuk tabel database.
    *   `seeders/`: Data awal (Dummy Data).
*   **`resources/views/`**
    *   `admin/`: File HTML/Blade untuk halaman Admin.
    *   `support/`: File HTML/Blade untuk dashboard Support.
    *   `tickets/`: File HTML/Blade untuk form tiket karyawan.
*   **`routes/web.php`**: Daftar alamat URL aplikasi.

---

## ❓ Troubleshooting (Pemecahan Masalah)

**Q: Grafik "Top IT Issues" di Dashboard Admin kosong?**
A: Pastikan Anda sudah menjalankan **Tahap 3 (Instalasi AI Service)** dengan benar. Cek juga apakah ada data tiket di database. AI butuh data tiket untuk dianalisis.

**Q: Email notifikasi tidak masuk?**
A: Pastikan **Terminal 3 (Job Worker)** sedang berjalan. Cek konfigurasi `MAIL_...` di file `.env`. Untuk lokal, gunakan driver `log` (`MAIL_MAILER=log`) untuk melihat email di file `storage/logs/laravel.log`.

**Q: Tampilan web berantakan / CSS tidak muncul?**
A: Pastikan **Terminal 2 (npm run dev)** sedang berjalan.

---

**© 2026 PT PRIMA TEKNOLOGI INOVASI - OTicket System**
Dokumentasi disusun untuk memudahkan implementasi dan pemeliharaan sistem.
