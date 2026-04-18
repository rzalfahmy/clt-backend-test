# CLT Toolbox – Backend Feature Test

## Gambaran Singkat

Project ini merupakan hasil pengerjaan saya untuk **Backend Feature Test CLT Toolbox**.

Aplikasi ini dibuat menggunakan Laravel, dengan fokus utama untuk mengelola:

* Supplier
* Layup (struktur CLT)
* Layer (lapisan dalam layup)

Relasi data:

```
Supplier → Layups → Layers
```

---

## Fitur yang Dikerjakan

### Fitur Utama

* CRUD Supplier
* CRUD Layup (berdasarkan supplier)
* CRUD Layer (berdasarkan layup)

---

### Import & Export

* Export data supplier lengkap (termasuk semua layup & layer) dalam format JSON
* Import data JSON ke dalam supplier
* Data akan otomatis:

  * membuat data baru, atau
  * mengupdate data yang sudah ada

---

### Penanganan Konflik (Conflict Resolution)

Konflik terjadi jika:

* Layup dengan nama yang sama sudah ada
* Layer dengan `layer_order` yang sama tapi data berbeda

Strategi yang digunakan:

* Overwrite data lama (default)
* Skip jika tidak ingin ditimpa (opsional)

---

### Authentication

* Login & Logout
* Forgot Password (menggunakan Mailtrap)

---

### Tampilan

* Menggunakan custom dark theme
* UI dibuat sederhana, fokus ke fungsi utama

---

## Cara Menjalankan Project

### 1. Install dependency

```bash
composer install
```

---

### 2. Copy file environment

```bash
cp .env.example .env
```

---

### 3. Generate app key

```bash
php artisan key:generate
```

---

### 4. Setup database

Buat database di MySQL (XAMPP/phpMyAdmin):

```
backend_clt
```

Lalu jalankan:

```bash
php artisan migrate
```

---

### 5. Jalankan aplikasi

```bash
php artisan serve
```

Buka di browser:

```
http://127.0.0.1:8000
```

---

## Konfigurasi Email (Forgot Password)

Project ini menggunakan **Mailtrap** untuk testing email.

Isi di `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="test@example.com"
MAIL_FROM_NAME="CLT Toolbox"
```

---

## Catatan

* Asset frontend sudah di-build → tidak perlu menjalankan `npm run dev`
* Folder `vendor` dan `node_modules` tidak disertakan (cukup jalankan `composer install`)
* Database tidak disertakan → gunakan `php artisan migrate`

---

## Demo

Video demo:
https://drive.google.com/drive/folders/1HEZmD6SybNTTZocZDVvopQ9W2DjXA3xV?usp=sharing

---

## Author

Rizal Fahmi
https://rzalfahmy.github.io/ 
