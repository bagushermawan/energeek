# Tes Junior Fullstack Web Developer

## Deskripsi Sistem

Projek ini adalah aplikasi web yang dikembangkan sebagai bagian dari tes untuk posisi Junior Fullstack Web Developer. Aplikasi ini memungkinkan pengguna untuk mengelola daftar tugas (tasks/to do), termasuk membuat, mengedit, dan menghapus tugas. 

## Fitur Utama

- **CRUD Task**: Buat, baca, perbarui, dan hapus task/to do.
- **Autentikasi User**: Pengguna dapat melakukan login dan logout (bisa anonymous login).
- **Manajemen User**: CRUD User beserta Roles & Permissionnya.
- **Kategori Task**: Kategorikan task/to do berdasarkan kategori yang telah didefinisikan.
- **Validasi Formulir**: Memastikan data yang dimasukkan sesuai dengan format yang diinginkan.

## Instalasi

Ikuti langkah-langkah berikut untuk menginstal dan menjalankan aplikasi ini secara lokal:

1. **Clone Repository**

    ```bash
    git clone https://github.com/bagushermawan/energeek.git
    cd repository-name
    ```

2. **Install Dependensi**

    Untuk backend:

    ```bash
    composer install
    ```

    Untuk frontend:

    ```bash
    npm install
    ```

3. **Konfigurasi .env**

    Salin/rename file `.env.example` ke file `.env`:

    ```bash
    cp .env.example .env
    ```

    Sesuaikan konfigurasi database dan pengaturan lainnya di file `.env`:

    ```env
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_user
    DB_PASSWORD=your_database_password
    ```

4. **Generate Kunci Aplikasi**

    ```bash
    php artisan key:generate
    ```

5. **Migrasi Database (beserta seeder nya)**

    ```bash
    php artisan migrate --seed
    ```

6. **Menjalankan Aplikasi**

    Untuk menjalankan server Laravel:

    ```bash
    php artisan serve
    ```

    Untuk menjalankan frontend development server:

    ```bash
    npm run dev
    ```

## Penggunaan

- Akses aplikasi melalui browser di `http://localhost:8000` (sesuaikan port nya).
- Akses panel admin di `http://localhost:8000/dashboard` atau `http://localhost:8000/console` untuk manajemen kategori dan pengguna.
- Untuk route bisa langsung di cek di file/folder `routes/...`


Terima kasih telah  proyek ini!
