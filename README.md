# Sistem Pembayaran Pembangunan (SPP) - API Implementation

> REST API untuk aplikasi manajemen pembayaran SPP dengan role-based access control dan fitur reporting yang lengkap.

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![PHP](https://img.shields.io/badge/PHP-%3E%3D7.2-purple.svg)
![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange.svg)
![Status](https://img.shields.io/badge/status-Active-brightgreen.svg)

## 📋 Daftar Isi
- [Overview](#overview)
- [Fitur](#fitur)
- [Instalasi](#instalasi)
- [Struktur Project](#struktur-project)
- [API Endpoints](#api-endpoints)
- [Penggunaan](#penggunaan)
- [Dokumentasi](#dokumentasi)
- [Troubleshooting](#troubleshooting)

## Overview

SPP adalah sistem manajemen pembayaran pembangunan berbasis web yang memungkinkan:
- **Siswa** untuk melihat status pembayaran SPP mereka (halaman siswa sekarang mengambil data melalui API dan menampilkan status berwarna hijau/merah)
- **Petugas** untuk mencatat dan mengelola pembayaran
- **Admin** untuk mengelola master data dan membuat laporan

Sistem ini kini dilengkapi dengan **REST API** yang komprehensif untuk integrasi dengan aplikasi mobile atau sistem pihak ketiga.

## Fitur

### ✅ Core Features
- [x] Manajemen Siswa (Create, Read, Update, Delete)
- [x] Manajemen Petugas (Create, Read, Update, Delete)
- [x] Manajemen Kelas
- [x] Manajemen SPP (Rumus Pembayaran)
- [x] Pencatatan Pembayaran
- [x] Autentikasi berbasis Session

### ✅ API Features
- [x] REST API dengan JSON responses
- [x] CORS support untuk cross-domain requests
- [x] Role-based access control (RBAC)
- [x] Dashboard dengan statistik
- [x] Report generation (Siswa belum lunas, sudah lunas, per bulan)
- [x] Export/Import CSV
- [x] Error handling yang proper
- [x] Input validation & sanitization

### 📋 Advanced Features
- [x] Pembayaran tracking per siswa
- [x] Laporan pembayaran per bulan
- [x] Top siswa & top pembayaran
- [x] Sisa pembayaran calculation
- [x] Frontend siswa menggunakan API dan responsif

- [x] Database error handling

## Instalasi

### Requirements
- PHP 7.2 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Apache dengan mod_rewrite enabled
- Web server (XAMPP, WAMP, LAMP, dll)

### Steps

1. **Clone atau copy project ke htdocs**
   ```bash
   cd C:\xampp\htdocs
   # Project sudah ada di: Apk_SPP
   ```

2. **Import database**
   ```bash
   # Akses phpMyAdmin atau gunakan command line
   mysql -u root < db_spp.sql
   ```

3. **Update database credentials (jika berbeda)**
   ```php
   // koneksi.php
   $koneksi = mysqli_connect('localhost', 'root', '', 'db_spp');
   ```

4. **Enable mod_rewrite di Apache**
   - Edit `httpd.conf`
   - Uncomment: `LoadModule rewrite_module modules/mod_rewrite.so`
   - Restart Apache

5. **Akses aplikasi**
   - Web: http://localhost/Apk_SPP
   - API: http://localhost/Apk_SPP/api

## Struktur Project

```
Apk_SPP/
├── api/                          # REST API Implementation
│   ├── index.php                # Main router
│   ├── config.php               # Database & CORS config
│   ├── utils.php                # Utility functions
│   ├── helpers.php              # Helper functions
│   ├── auth.php                 # Authentication
│   ├── siswa.php                # Student endpoints
│   ├── petugas.php              # Officer endpoints
│   ├── kelas.php                # Class endpoints
│   ├── spp.php                  # SPP endpoints
│   ├── pembayaran.php           # Payment endpoints
│   ├── dashboard.php            # Dashboard/Statistics
│   ├── report.php               # Reports
│   ├── export.php               # Export/Import
│   ├── test-examples.php        # API test examples
│   ├── Postman_Collection.json  # Postman collection
│   ├── .env.example             # Environment example
│   └── .htaccess                # URL rewriting
├── siswa/
│   ├── index.php                # Dashboard siswa (updated with API)
│   ├── logout.php
│   └── cetak_laporan.php
├── petugas/
│   ├── index.php
│   ├── functions_query.php
│   ├── functions_cetak_laporan.php
│   ├── [...edit, tambah, hapus, td files...]
│   └── logout.php
├── style/
│   ├── css/                     # CSS files
│   ├── js/                      # JavaScript files
│   └── gambar/                  # Images
├── aksi_login.php               # Student login handler (updated)
├── aksi_login_petugas.php       # Officer login handler (updated)
├── index.php                    # Student login page
├── index_petugas.php            # Officer login page
├── koneksi.php                  # Database connection (updated)
├── db_spp.sql                   # Database schema
├── .htaccess                    # URL rewriting (new)
├── API_DOCUMENTATION.md         # Complete API docs
├── API_QUICK_START.md           # Quick start guide
└── README.md                    # This file
```

## API Endpoints

### Overview
```
Base URL: http://localhost/Apk_SPP/api
```

### Authentication
```
POST   /auth/login              # Login (siswa/petugas)
POST   /auth/logout             # Logout
GET    /auth/me                 # Get current user
```

### Master Data CRUD
```
GET    /siswa                   # Get all siswa
GET    /siswa/{nisn}            # Get siswa detail
POST   /siswa                   # Create siswa
PUT    /siswa/{nisn}            # Update siswa
DELETE /siswa/{nisn}            # Delete siswa

GET    /petugas                 # Get all petugas
GET    /petugas/{id}            # Get petugas detail
POST   /petugas                 # Create petugas
PUT    /petugas/{id}            # Update petugas
DELETE /petugas/{id}            # Delete petugas

GET    /kelas                   # Get all kelas
GET    /kelas/{id}              # Get kelas detail
POST   /kelas                   # Create kelas
PUT    /kelas/{id}              # Update kelas
DELETE /kelas/{id}              # Delete kelas

GET    /spp                     # Get all spp
GET    /spp/{id}                # Get spp detail
POST   /spp                     # Create spp
PUT    /spp/{id}                # Update spp
DELETE /spp/{id}                # Delete spp
```

### Transactions
```
GET    /pembayaran              # Get pembayaran list
GET    /pembayaran/{id}         # Get pembayaran detail
POST   /pembayaran              # Create pembayaran (petugas only)
PUT    /pembayaran/{id}         # Update pembayaran (admin only)
DELETE /pembayaran/{id}         # Delete pembayaran (admin only)
```

### Analytics & Reporting
```
GET    /dashboard               # Dashboard statistics
GET    /report?type=...         # Generate reports
GET    /export?action=...       # Export data
POST   /export?action=import    # Import data
```

Lihat [API_DOCUMENTATION.md](API_DOCUMENTATION.md) untuk detail lengkap.

## Penggunaan

### Login via API
```bash
curl -X POST http://localhost/Apk_SPP/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "username": "10206080",
    "password": "123456",
    "type": "siswa"
  }'
```

### Get Dashboard
```bash
curl -X GET http://localhost/Apk_SPP/api/dashboard \
  -H "Content-Type: application/json"
```

### Export Siswa to CSV
```bash
curl -X GET http://localhost/Apk_SPP/api/export?action=export-siswa \
  -H "Content-Type: application/json" \
  -o siswa.csv
```

### Create Pembayaran
```bash
curl -X POST http://localhost/Apk_SPP/api/pembayaran \
  -H "Content-Type: application/json" \
  -d '{
    "nisn": "10206080",
    "id_spp": 2,
    "jumlah_bayar": 250000,
    "bulan_dibayar": "Maret"
  }'
```

## Dokumentasi

### File Dokumentasi
- **[API_DOCUMENTATION.md](API_DOCUMENTATION.md)** - Dokumentasi API lengkap dengan semua endpoint, contoh request/response
- **[API_QUICK_START.md](API_QUICK_START.md)** - Panduan cepat untuk mulai menggunakan API
- **[api/test-examples.php](api/test-examples.php)** - Contoh-contoh implementasi API dalam PHP

### Postman Collection
Import `api/Postman_Collection.json` ke Postman untuk testing yang lebih mudah.

### Testing API
```bash
# Akses halaman test
php api/test-examples.php
```

## Data Access Control

### Siswa
- ✅ Bisa melihat data pribadi mereka
- ✅ Bisa melihat riwayat pembayaran mereka
- ❌ Tidak bisa mengakses data siswa lain
- ❌ Tidak bisa membuat/edit/hapus data

### Petugas (Officer)
- ✅ Bisa melihat semua data siswa
- ✅ Bisa membuat pembayaran
- ✅ Bisa melihat laporan
- ✅ Bisa export data
- ❌ Tidak bisa delete data siswa
- ❌ Tidak bisa mengelola petugas lain

### Admin
- ✅ Akses penuh ke semua endpoint
- ✅ Bisa mengelola master data
- ✅ Bisa delete data
- ✅ Bisa import data

## Security Features

### Input Validation
- ✅ SQL injection prevention (mysqli_real_escape_string)
- ✅ CORS headers untuk cross-origin requests
- ✅ HTTPS headers recommendations

### Authentication
- ✅ Session-based authentication
- ✅ Password hashing (MD5)
- ✅ Role-based access control

### Best Practices
- ✅ Error messages tidak mengekspose database info
- ✅ All inputs are sanitized
- ✅ HTTP status codes yang proper

### Rekomendasi Upgrade
- ⚠️ Upgrade MD5 ke bcrypt
- ⚠️ Implementasi JWT tokens
- ⚠️ Tambah SSL/HTTPS
- ⚠️ Rate limiting
- ⚠️ CSRF protection

## Troubleshooting

### 404 API Not Found
**Problem:** Mendapat error 404 saat akses API
**Solution:**
1. Pastikan `.htaccess` sudah di-upload dengan benar
2. Check mod_rewrite sudah enabled
3. Restart Apache

### 401 Unauthorized
**Problem:** API mengembalikan 401 error
**Solution:**
1. Login terlebih dahulu
2. Pastikan session sudah aktif
3. Check Authorization header jika menggunakan token

### 403 Forbidden
**Problem:** API mengembalikan 403 error
**Solution:**
1. Cek role user (admin, petugas, siswa)
2. Verify user punya permission untuk endpoint
3. Siswa hanya bisa akses data mereka sendiri

### 500 Server Error
**Problem:** API mengembalikan 500 error
**Solution:**
1. Check database connection
2. Verify database schema matches `db_spp.sql`
3. Check PHP error logs
4. Ensure tables exist di database

### Database Connection Error
**Problem:** "Connection failed: could not connect to MySQL"
**Solution:**
1. Pastikan MySQL service running
2. Check credentials di `koneksi.php`
3. Verify database `db_spp` sudah di-create
4. Check user permissions

## Version History

### v1.0.0 (March 4, 2026)
- ✨ Initial API implementation
- ✨ Complete CRUD operations
- ✨ Dashboard & reporting
- ✨ Export/Import functionality
- ✨ Authentication system
- ✨ Comprehensive documentation

Lihat [CHANGELOG.md](CHANGELOG.md) untuk detail perubahan.

## Contributing

Untuk kontribusi atau laporan bug:
1. Buat issue dengan deskripsi jelas
2. Sertakan langkah reproduksi
3. Sertakan environment info (PHP version, MySQL version, dll)

## License

All rights reserved. © 2026

## Support

Untuk pertanyaan atau bantuan:
1. Check dokumentasi di [API_DOCUMENTATION.md](API_DOCUMENTATION.md)
2. Lihat contoh di [api/test-examples.php](api/test-examples.php)
3. Test dengan Postman collection di [api/Postman_Collection.json](api/Postman_Collection.json)

---

**Last Updated:** March 4, 2026
**API Version:** 1.0.0
**Status:** ✅ Active & Stable
