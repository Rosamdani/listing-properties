# Listing Properties

Aplikasi manajemen properti yang dibangun dengan Laravel.

## Teknologi yang Digunakan

- **Backend:** Laravel 12, PHP 8.2+
- **Frontend:** Blade, Bootstrap
- **Database:** MySQL atau PostgreSQL
- **Tools Lainnya:** (Tambahkan jika ada, seperti Redis, Queues, dll.)

## Persyaratan Sistem

- PHP >= 8.3
- Composer
- MySQL atau PostgreSQL
- Node.js & NPM (untuk frontend build)
- (Tambahkan jika ada persyaratan lain seperti Redis, Supervisor, dll.)

## Cara Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/Rosamdani/listing-properties.git
cd listing-properties
```

### 2. Instalasi Dependensi PHP

```bash
composer install
```

### 3. Instalasi Dependensi NPM

```bash
npm install && npm run dev
```

### 4. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Konfigurasi Database

Edit file `.env` dan sesuaikan dengan konfigurasi database:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=username_database
DB_PASSWORD=password_database
```

### 6. Migrasi dan Seeding Database

```bash
php artisan migrate --seed
```

### 7. Buat Symbolic Link untuk Storage

```bash
php artisan storage:link
```

### 8. Menjalankan Server Pengembangan

```bash
php artisan serve
```

Aplikasi akan tersedia di: [http://localhost:8000](http://localhost:8000)

## Struktur Proyek

```
├── app/                # Kode aplikasi utama
│   ├── Http/           # Controllers, Middleware, Requests
│   ├── Models/         # Model Eloquent
│   ├── Providers/      # Service providers
│   └── Services/       # Custom services
├── bootstrap/          # Bootstrap framework Laravel
├── config/             # File konfigurasi aplikasi
├── database/           # Migrasi, Seeder, Factories
├── public/             # Akses publik (assets, storage)
├── resources/          # Views, assets, dan file bahasa
│   ├── js/             # File JavaScript
│   ├── css/            # File CSS
│   └── views/          # Template Blade
├── routes/             # Route aplikasi
│   ├── api.php         # Route untuk API
│   ├── console.php     # Perintah konsol
│   └── web.php         # Route web
├── storage/            # Penyimpanan aplikasi (log, cache, dll.)
├── tests/              # Pengujian otomatis
├── vendor/             # Dependensi Composer
└── .env                # Variabel lingkungan
```

## Pengujian

Jalankan pengujian dengan perintah berikut:

```bash
php artisan test
```

## Kontribusi

Jika ingin berkontribusi, silakan buat pull request atau laporkan masalah di [repository ini](https://github.com/Rosamdani/listing-properties/issues).

## Ucapan Terima Kasih

- [Laravel](https://laravel.com)
- [Bootstrap](https://getbootstrap.com)
- (Tambahkan jika ada teknologi lain yang digunakan)

## Lisensi

Proyek ini dirilis di bawah lisensi [MIT](LICENSE).

