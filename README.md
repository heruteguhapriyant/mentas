# 🎭 Mentas.id

**Platform Arsip, Wacana, dan Ekosistem Seni-Budaya Indonesia**

Mentas.id adalah platform digital yang menyediakan ruang untuk tulisan, buletin sastra, katalog riset, agenda kegiatan, dan marketplace untuk komunitas seni-budaya Indonesia.

![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?style=flat-square&logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

---

## 📋 Fitur

- **📝 Blog & Wacana** - Artikel berita, esai, dan opini seputar seni-budaya
- **📚 Buletin Sastra** - Koleksi puisi dan prosa dari kontributor
- **📖 Katalog** - Database riset dan dokumentasi kesenian
- **🛒 Jual Beli** - Marketplace untuk buku dan merchandise
- **🎪 Event** - Agenda kegiatan seperti diskusi, pameran, dan pertunjukan
- **👥 Komunitas** - Direktori komunitas dan mitra seni-budaya
- **🔐 Multi-role Auth** - Sistem login untuk Admin, Kontributor

---

## 🏗️ Arsitektur

Proyek ini menggunakan arsitektur **MVC (Model-View-Controller)** dengan PHP native.

```
mentas-main/
├── public/                  # Web root (document root)
│   ├── index.php           # Bootstrap / entry point
│   ├── router.php          # Router untuk PHP built-in server
│   ├── .htaccess           # Apache rewrite rules
│   ├── assets/             # CSS, JS, images
│   └── uploads/            # User uploaded files
│
├── app/
│   ├── config/
│   │   ├── config.php      # Konfigurasi aplikasi (BASE_URL)
│   │   └── database.php    # Kredensial database
│   │
│   ├── core/               # Framework core
│   │   ├── Controller.php  # Base controller
│   │   ├── Database.php    # PDO Singleton
│   │   └── Router.php      # URL routing
│   │
│   ├── controllers/        # Controllers (logic layer)
│   │   ├── HomeController.php
│   │   ├── ContentController.php
│   │   ├── AdminController.php
│   │   ├── AuthController.php
│   │   └── ...
│   │
│   ├── models/             # Models (data layer)
│   │   ├── Post.php
│   │   ├── Category.php
│   │   ├── User.php
│   │   ├── Zine.php
│   │   └── ...
│   │
│   ├── views/              # Views (presentation layer)
│   │   ├── layouts/        # Master layout (header, footer)
│   │   ├── home/
│   │   ├── content/
│   │   ├── admin/
│   │   └── ...
│   │
│   └── helpers/            # Helper functions
│       ├── content_helper.php
│       ├── url_helper.php
│       └── auth_helper.php
│
└── database/               # SQL files
    └── mentas_db.sql       # Database schema & seed
```

---

## 🔄 Alur Data

```
Browser Request
      │
      ▼
┌─────────────────┐
│  router.php     │ ─── Static file? → Serve langsung
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  index.php      │ ─── Bootstrap (load config, models, helpers)
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Router.php     │ ─── Parse URL → Tentukan Controller & Method
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Controller     │ ─── Business logic, panggil Model
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Model          │ ─── Query database via PDO
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  View           │ ─── Render HTML dengan data
└────────┬────────┘
         │
         ▼
Browser Response (HTML)
```

---

## 🚀 Instalasi

### Prasyarat

- PHP 8.1+
- MySQL 5.7+ / MariaDB
- Composer (opsional)

### Langkah Instalasi

1. **Clone repository**
   ```bash
   git clone https://github.com/Daffa964/mentas.git
   cd mentas
   ```

2. **Konfigurasi database**
   
   Edit file `app/config/database.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'mentas_db');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

3. **Import database**
   ```bash
   mysql -u root -p mentas_db < database/mentas_db.sql
   ```

4. **Jalankan server**
   
   **Opsi A: PHP Built-in Server**
   ```bash
   cd public
   php -S localhost:8000 router.php
   ```
   
   **Opsi B: Apache/Nginx**
   - Arahkan document root ke folder `public/`
   - Pastikan `mod_rewrite` aktif (Apache)

5. **Buka browser**
   ```
   http://localhost:8000
   ```

---

## 📖 URL Routes

| URL | Controller | Deskripsi |
|-----|------------|-----------|
| `/` | HomeController | Halaman utama |
| `/blog` | ContentController | Daftar artikel |
| `/blog/{category}` | ContentController | Artikel per kategori |
| `/blog/{slug}` | ContentController | Detail artikel |
| `/zine` | ZineController | Buletin sastra |
| `/katalog` | KatalogController | Katalog riset |
| `/admin` | AdminController | Dashboard admin |
| `/admin/posts` | AdminController | Kelola artikel |
| `/login` | AuthController | Halaman login |

---

## 🔐 Roles & Permissions

| Role | Akses |
|------|-------|
| **Admin** | Full access - kelola semua konten, user, kategori |
| **Contributor** | Kelola artikel/konten sendiri |
| **Public** | Baca konten yang dipublikasikan |

---

## 🛠️ Teknologi

- **Backend:** PHP 8.1+ (Native MVC)
- **Database:** MySQL / MariaDB dengan PDO
- **Frontend:** HTML5, CSS3, JavaScript
- **Icons:** Font Awesome 6
- **Pattern:** Singleton (Database), MVC

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

---

## 👥 Kontributor

- **Mentas.id Team**

---

<p align="center">
  Made with ❤️ for Indonesian Arts & Culture Community
</p>
