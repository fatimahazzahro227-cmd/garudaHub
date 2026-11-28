# Garuda-Hub (Laravel 12)

Garuda-Hub merupakan aplikasi berbasis web yang mempertemukan klien dengan developer melalui sistem lelang proyek teknologi informasi. Klien dapat mempublikasikan proyek beserta anggaran dan tenggat waktu, sedangkan developer dapat mengajukan penawaran berupa harga, estimasi durasi, dan proposal pengerjaan. Selanjutnya, klien dapat menentukan developer yang paling sesuai untuk dipilih sebagai pemenang.

Aplikasi ini merupakan hasil pengembangan dari prototype PHP Native menjadi Laravel 12 dengan menerapkan arsitektur **Model-View-Controller (MVC)** dan **Eloquent ORM** untuk pengelolaan data.

Source code versi PHP Native tetap tersedia pada branch `main`, termasuk `src/index.php`, `src/database.sql`, dan folder `prototype/`.

Demo online untuk tugas Operation Go-Live dapat diakses melalui: **[URL WEBSITE LIVE]**.

> **Catatan:** Proyek ini ditujukan untuk keperluan pembelajaran dan pengujian. Seluruh akun dan informasi bawaan merupakan data simulasi. Jangan menggunakan data pribadi ataupun password yang sebenarnya.

## 1. Persiapan Lingkungan

Sebelum menjalankan aplikasi secara lokal, pastikan perangkat sudah memiliki kebutuhan berikut:

- PHP versi 8.2 atau lebih baru.
- Composer.
- MySQL atau MariaDB.
- Laragon sebagai lingkungan pengembangan lokal. Tanpa Laragon, database `garuda_hub_laravel` dapat dibuat lewat phpMyAdmin atau HeidiSQL.

### Instalasi Aplikasi

Buka terminal pada direktori utama proyek, kemudian jalankan perintah berikut:

```bat
composer install
copy .env.example .env
php artisan key:generate
mysql -u root -e "CREATE DATABASE garuda_hub_laravel"
php artisan migrate:fresh --seed
php artisan serve
```

Setelah server berhasil dijalankan, buka aplikasi melalui browser:

http://localhost:8000

### Akun Pengguna

Aplikasi menyediakan tiga jenis akun berdasarkan hak akses masing-masing pengguna.

| Peran | Email | Password |
|---|---|---|
| Admin | admin@example.com | admin123 |
| Klien | budi@example.com | klien123 |
| Developer | dewi@example.com | dev123 |

Akun tersebut merupakan data bawaan yang dibuat melalui proses database seeding.

### Menjalankan Pengujian

Untuk menjalankan pengujian aplikasi, gunakan perintah:

```bat
php artisan test
```

## 2. Struktur dan Penerapan MVC

Struktur aplikasi dibagi berdasarkan tanggung jawab masing-masing komponen agar kode lebih terorganisasi dan mudah dikembangkan.

| Bagian | Lokasi File | Fungsi |
|---|---|---|
| Model | `app/Models` | Mengelola entitas User, Project, Bid, dan Rating beserta relasi antar tabel menggunakan Eloquent. |
| View | `resources/views` | Menampilkan halaman menggunakan Blade, seperti autentikasi, proyek, penawaran, rating, dashboard, dan administrasi. |
| Controller | `app/Http/Controllers` | Mengatur proses aplikasi melalui AuthController, ProjectController, BidController, DashboardController, AdminController, dan proses rating. |
| Request Validation | `app/Http/Requests` | Memvalidasi data masukan menggunakan RegisterRequest, ProjectRequest, BidRequest, dan validasi rating. |
| Authorization | `app/Policies` dan `app/Http/Middleware/EnsureRole.php` | Mengatur hak akses pengguna berdasarkan peran serta membatasi tindakan tertentu, termasuk pemberian rating. |
| Routing | `routes/web.php` | Mendefinisikan alamat aplikasi dan mengelompokkan rute berdasarkan hak akses. |
| Migration | `database/migrations` | Mendefinisikan struktur tabel dan perubahan skema database. |
| Seeder | `database/seeders/DatabaseSeeder.php` | Menyediakan data awal berupa akun pengguna, proyek, dan penawaran untuk kebutuhan simulasi. |

## 3. Struktur Database

Database Garuda-Hub memiliki empat entitas utama, yaitu `users`, `projects`, `bids`, dan `ratings`.

### Entity Relationship Diagram (ERD)

```mermaid
erDiagram
    users ||--o{ projects : "client_id"
    users ||--o{ bids : "developer_id"
    projects ||--o{ bids : "project_id"

    projects ||--o| ratings : "project_id"
    users ||--o{ ratings : "client_id"
    users ||--o{ ratings : "developer_id"

    users {
        bigint id PK
        varchar nama
        varchar email UK
        varchar password
        enum role
    }

    projects {
        bigint id PK
        bigint client_id FK
        varchar judul
        text deskripsi
        varchar kategori
        bigint budget_min
        bigint budget_max
        date batas_lelang
        enum status
    }

    bids {
        bigint id PK
        bigint project_id FK
        bigint developer_id FK
        bigint harga
        int durasi
        text proposal
        boolean is_winner
    }

    ratings {
        bigint id PK
        bigint project_id FK UK
        bigint client_id FK
        bigint developer_id FK
        int skor
        text ulasan
    }
```

### Hubungan Antar Tabel

- Satu pengguna dengan role klien dapat membuat beberapa proyek.
- Satu pengguna dengan role developer dapat mengajukan penawaran pada beberapa proyek.
- Satu proyek dapat menerima beberapa penawaran dari developer yang berbeda.
- Setiap penawaran terhubung dengan satu proyek dan satu developer.
- Satu proyek hanya dapat memiliki satu rating.
- Setiap rating terhubung dengan satu proyek, satu klien, dan satu developer.

### Peningkatan Struktur Database

Dibandingkan dengan prototype PHP Native sebelumnya (`prototype/mocks/database.sql` pada branch `main`), versi Laravel menerapkan beberapa peningkatan untuk menjaga integritas data:

1. **Unique Email:** Kolom `users.email` menggunakan UNIQUE constraint agar setiap pengguna memiliki alamat email yang berbeda.
2. **Enum Role dan Status:** Kolom `role` pada tabel users dan `status` pada tabel projects menggunakan ENUM sehingga nilai yang dimasukkan hanya berasal dari pilihan yang telah ditentukan.
3. **Foreign Key:** Hubungan antar tabel menggunakan foreign key untuk menjaga keterkaitan data:
  - `projects.client_id` mengacu pada `users.id`.
  - `bids.project_id` mengacu pada `projects.id`.
  - `bids.developer_id` mengacu pada `users.id`.
  - `ratings.project_id` mengacu pada `projects.id`.
  - `ratings.client_id` dan `ratings.developer_id` mengacu pada `users.id`.
4. **Cascade Delete:** Penghapusan data induk akan menghapus data turunannya melalui aturan `ON DELETE CASCADE`.
5. **Unique Penawaran:** Kombinasi `project_id` dan `developer_id` dibatasi menggunakan UNIQUE constraint agar developer tidak dapat mengirimkan penawaran lebih dari satu kali pada proyek yang sama.
6. **Check Constraint:** Memastikan `budget_max` tidak lebih kecil dari `budget_min`, serta harga dan durasi penawaran harus bernilai positif.
7. **Database Index:** Indeks diterapkan pada kolom `status`, `kategori`, `role`, dan foreign key untuk membantu meningkatkan efisiensi pencarian data.
8. **Unique Rating Proyek:** Kolom `ratings.project_id` dibatasi menggunakan UNIQUE constraint agar satu proyek hanya dapat memiliki satu rating.
9. **Validasi Skor Rating:** Skor rating dibatasi pada rentang 1–5.

## 4. Pengelolaan Data dengan Eloquent ORM

Proses manipulasi data pada aplikasi menggunakan Eloquent ORM. Pendekatan ini memungkinkan interaksi dengan database melalui model dan relasi yang telah didefinisikan.

Berikut penerapan operasi CRUD pada setiap entitas:

| Entitas | Create | Read | Update | Delete |
|---|---|---|---|---|
| Project | `$user->projects()->create()` \vert{} `Project::with('client')->withCount('bids')->search()` \vert{} `$project->update()` | `$project->delete()` \vert{} \vert{} Bid \vert{} `$bid->project()->associate()->save()` | `$project->bids`, `$user->bids()` | `$bid->update()` \vert{} `$bid->delete()` |
| User | `$user->save()` \vert{} `User::withCount()` \vert{} `forceFill(['role'])` \vert{} `$user->delete()` |
| Rating | Penyimpanan rating melalui relasi proyek | Rating proyek dan rata-rata rating developer | — | — |

### Mekanisme Pemilihan Pemenang

Proses penentuan developer terpilih memanfaatkan transaksi database melalui `DB::transaction()` yang dikombinasikan dengan `lockForUpdate()`.

Baris proyek dikunci selama transaksi, sehingga satu proyek tidak bisa memiliki dua pemenang meskipun pemilihan dilakukan bersamaan.

### Mekanisme Rating Developer

Rating diberikan oleh klien pemilik proyek kepada developer yang dipilih sebagai pemenang setelah proyek ditutup. Setiap proyek hanya dapat menerima satu rating.

Rating developer ditampilkan berdasarkan data rating yang dimiliki developer tersebut. Dengan demikian, rating dan jumlah rating developer tetap dapat terlihat ketika developer yang sama muncul pada proyek lain.

## 5. Database Seeding

Untuk mempermudah demonstrasi dan pengujian, aplikasi menyediakan data awal melalui file `database/seeders/DatabaseSeeder.php`.

Proses pengisian data menggunakan model Eloquent, facade `Hash`, serta `DB::statement()` untuk penyesuaian sequence PostgreSQL.

### Data yang Disediakan

| Jenis Data | Jumlah | Keterangan |
|---|---:|---|
| User | 8 | 1 admin, 3 klien, dan 4 developer |
| Project | 8 | Berbagai proyek dengan kategori berbeda |
| Bid | 15 | Penawaran developer terhadap proyek |

### Kategori Proyek

Data proyek mencakup beberapa kategori bidang teknologi informasi:

- Web
- Mobile
- Desain
- Data
- AI

### Distribusi Akun Pengguna

| Nama | Role |
|---|---|
| Admin Garuda | Admin |
| Budi Santoso | Klien |
| Siti Rahayu | Klien |
| Agus Wijaya | Klien |
| Dewi Lestari | Developer |
| Eko Prasetyo | Developer |
| Fitri Handayani | Developer |
| Gilang Ramadhan | Developer |

### Mekanisme Pengisian Data

Beberapa mekanisme yang diterapkan dalam `DatabaseSeeder` meliputi:

- Password setiap pengguna di-hash menggunakan `Hash::make()` (bcrypt), sehingga tidak tersimpan sebagai teks asli.
- Data pengguna, proyek, dan penawaran dimasukkan menggunakan `forceCreate()`.
- Penawaran yang terpilih sebagai pemenang ditandai melalui atribut `is_winner`.
- ID setiap data ditentukan secara eksplisit untuk menjaga kesesuaian relasi.
- Terdapat penyesuaian sequence ID apabila database menggunakan PostgreSQL melalui perintah `setval()`.

## 6. Lisensi

Garuda-Hub menggunakan **MIT License**. Informasi lebih lanjut mengenai ketentuan penggunaan, modifikasi, dan distribusi proyek dapat dilihat pada file `LICENSE` yang tersedia di direktori utama repository.