# SPP API Documentation

## Base URL
```
http://localhost/Apk_SPP/api
```

## Authentication

### Login - POST /auth/login
Login as siswa or petugas.

**Request Body:**
```json
{
  "username": "10206080",
  "password": "123456",
  "type": "siswa"  // or "petugas"
}
```

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "Login successful",
  "data": {
    "type": "siswa",
    "nisn": "10206080",
    "nis": "12345676",
    "nama": "Rezza",
    "id_kelas": 2,
    "token": "..."
  }
}
```

### Logout - POST /auth/logout
Logout current user.

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "Logout successful",
  "data": null
}
```

### Get Current User - GET /auth/me
Get current authenticated user data.

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "User data retrieved",
  "data": {
    "type": "siswa",
    "id": "10206080"
  }
}
```

---

## Siswa Endpoints

### Get All Siswa - GET /siswa
Get list of all students. (**Admin only**)

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "Siswa data retrieved",
  "data": [
    {
      "nisn": "10206080",
      "nis": "12345676",
      "nama": "Rezza",
      "id_kelas": 2,
      "id_spp": 2,
      "alamat": "Cikadongdong",
      "no_telp": "098765432",
      "nama_kelas": "XI RPL",
      "tahun": 2021,
      "nominal": 500000
    }
  ]
}
```

### Get Siswa Detail - GET /siswa/{nisn}
Get detail of specific student.

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "Siswa detail retrieved",
  "data": {
    "nisn": "10206080",
    "nis": "12345676",
    "nama": "Rezza",
    "id_kelas": 2,
    "id_spp": 2,
    "alamat": "Cikadongdong",
    "no_telp": "098765432",
    "nama_kelas": "XI RPL",
    "tahun": 2021,
    "nominal": 500000,
    "total_bayar": 500000,
    "sisa_bayar": 0,
    "pembayaran": [
      {
        "id_pembayaran": 11,
        "id_petugas": 10201,
        "nisn": "10206080",
        "tgl_bayar": "2026-02-24",
        "bulan_dibayar": "Januari",
        "tahun_dibayar": "2020",
        "id_spp": 2,
        "jumlah_bayar": 500000,
        "nama_petugas": "Gibran",
        "tahun": 2021,
        "nominal": 500000
      }
    ]
  }
}
```

### Create Siswa - POST /siswa
Create new student. (**Admin only**)

**Request Body:**
```json
{
  "nisn": "1234567890",
  "nis": "87654321",
  "nama": "John Doe",
  "id_kelas": 1,
  "id_spp": 2,
  "password": "password123",
  "alamat": "Jl. Merdeka No. 1",
  "no_telp": "081234567890"
}
```

**Response (201 Created):**
```json
{
  "status": "success",
  "message": "Siswa created successfully",
  "data": {
    "nisn": "1234567890"
  }
}
```

### Update Siswa - PUT /siswa/{nisn}
Update student data. (**Admin only**)

**Request Body (partial update):**
```json
{
  "nama": "Jane Doe",
  "id_kelas": 2,
  "password": "newpassword123"
}
```

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "Siswa updated successfully",
  "data": null
}
```

### Delete Siswa - DELETE /siswa/{nisn}
Delete student and related payments. (**Admin only**)

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "Siswa deleted successfully",
  "data": null
}
```

---

## Petugas Endpoints

### Get All Petugas - GET /petugas
Get list of all officers. (**Admin only**)

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "Petugas data retrieved",
  "data": [
    {
      "id_petugas": 10201,
      "username": "sushibran",
      "nama_petugas": "Gibran",
      "level": "admin"
    }
  ]
}
```

### Get Petugas Detail - GET /petugas/{id}
Get detail of specific officer. (**Admin only**)

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "Petugas detail retrieved",
  "data": {
    "id_petugas": 10201,
    "username": "sushibran",
    "nama_petugas": "Gibran",
    "level": "admin",
    "jumlah_pembayaran": 3
  }
}
```

### Create Petugas - POST /petugas
Create new officer. (**Admin only**)

**Request Body:**
```json
{
  "username": "newuser",
  "password": "password123",
  "nama_petugas": "New Officer",
  "level": "petugas"
}
```

**Response (201 Created):**
```json
{
  "status": "success",
  "message": "Petugas created successfully",
  "data": {
    "id_petugas": 10205
  }
}
```

### Update Petugas - PUT /petugas/{id}
Update officer data. (**Admin only**)

**Request Body:**
```json
{
  "nama_petugas": "Updated Name",
  "level": "admin"
}
```

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "Petugas updated successfully",
  "data": null
}
```

### Delete Petugas - DELETE /petugas/{id}
Delete officer. (**Admin only**)

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "Petugas deleted successfully",
  "data": null
}
```

---

## Kelas Endpoints

### Get All Kelas - GET /kelas
Get list of all classes.

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "Kelas data retrieved",
  "data": [
    {
      "id_kelas": 1,
      "nama_kelas": "X RPL",
      "kompetensi_keahlian": "Rekayasa Perangkat Lunak",
      "jumlah_siswa": 25
    }
  ]
}
```

### Get Kelas Detail - GET /kelas/{id}
Get detail of specific class.

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "Kelas detail retrieved",
  "data": {
    "id_kelas": 1,
    "nama_kelas": "X RPL",
    "kompetensi_keahlian": "Rekayasa Perangkat Lunak",
    "siswa": [
      {
        "nisn": "10206080",
        "nama": "Rezza",
        "nis": "12345676"
      }
    ]
  }
}
```

### Create Kelas - POST /kelas
Create new class. (**Admin only**)

**Request Body:**
```json
{
  "nama_kelas": "XIII RPL",
  "kompetensi_keahlian": "Rekayasa Perangkat Lunak"
}
```

**Response (201 Created):**
```json
{
  "status": "success",
  "message": "Kelas created successfully",
  "data": {
    "id_kelas": 9
  }
}
```

### Update Kelas - PUT /kelas/{id}
Update class data. (**Admin only**)

**Request Body:**
```json
{
  "nama_kelas": "X RPL Updated"
}
```

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "Kelas updated successfully",
  "data": null
}
```

### Delete Kelas - DELETE /kelas/{id}
Delete class (must have no students). (**Admin only**)

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "Kelas deleted successfully",
  "data": null
}
```

---

## SPP Endpoints

### Get All SPP - GET /spp
Get list of all SPP.

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "SPP data retrieved",
  "data": [
    {
      "id_spp": 1,
      "tahun": 2020,
      "nominal": 300000
    }
  ]
}
```

### Get SPP Detail - GET /spp/{id}
Get detail of specific SPP.

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "SPP detail retrieved",
  "data": {
    "id_spp": 2,
    "tahun": 2021,
    "nominal": 500000,
    "jumlah_siswa": 1,
    "total_terbayar": 500000,
    "jumlah_pembayaran": 1
  }
}
```

### Create SPP - POST /spp
Create new SPP. (**Admin only**)

**Request Body:**
```json
{
  "tahun": 2026,
  "nominal": 750000
}
```

**Response (201 Created):**
```json
{
  "status": "success",
  "message": "SPP created successfully",
  "data": {
    "id_spp": 6
  }
}
```

### Update SPP - PUT /spp/{id}
Update SPP data. (**Admin only**)

**Request Body:**
```json
{
  "nominal": 800000
}
```

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "SPP updated successfully",
  "data": null
}
```

### Delete SPP - DELETE /spp/{id}
Delete SPP (must have no assigned students). (**Admin only**)

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "SPP deleted successfully",
  "data": null
}
```

---

## Pembayaran Endpoints

### Get All Pembayaran - GET /pembayaran
Get list of payments.
- **Siswa**: Can only see their own payments
- **Petugas**: Can see all payments or filter by nisn

**Query Parameters:**
- `nisn` (optional, for petugas only): Filter by student NISN

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "Pembayaran data retrieved",
  "data": [
    {
      "id_pembayaran": 11,
      "id_petugas": 10201,
      "nisn": "10206080",
      "tgl_bayar": "2026-02-24",
      "bulan_dibayar": "Januari",
      "tahun_dibayar": "2020",
      "id_spp": 2,
      "jumlah_bayar": 500000,
      "nama": "Rezza",
      "nama_kelas": "XI RPL",
      "tahun": 2021,
      "nominal": 500000,
      "nama_petugas": "Gibran"
    }
  ]
}
```

### Get Pembayaran Detail - GET /pembayaran/{id}
Get detail of specific payment.

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "Pembayaran detail retrieved",
  "data": {
    "id_pembayaran": 11,
    "id_petugas": 10201,
    "nisn": "10206080",
    "tgl_bayar": "2026-02-24",
    "bulan_dibayar": "Januari",
    "tahun_dibayar": "2020",
    "id_spp": 2,
    "jumlah_bayar": 500000,
    "nama": "Rezza",
    "nama_kelas": "XI RPL",
    "tahun": 2021,
    "nominal": 500000,
    "nama_petugas": "Gibran"
  }
}
```

### Create Pembayaran - POST /pembayaran
Create new payment. (**Petugas only**)

**Request Body:**
```json
{
  "nisn": "10206080",
  "id_spp": 2,
  "jumlah_bayar": 250000,
  "bulan_dibayar": "Januari",
  "tahun_dibayar": "2020",
  "tgl_bayar": "2026-02-24"
}
```

**Response (201 Created):**
```json
{
  "status": "success",
  "message": "Pembayaran created successfully",
  "data": {
    "id_pembayaran": 12
  }
}
```

### Update Pembayaran - PUT /pembayaran/{id}
Update payment data. (**Admin only**)

**Request Body:**
```json
{
  "jumlah_bayar": 300000,
  "bulan_dibayar": "Februari"
}
```

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "Pembayaran updated successfully",
  "data": null
}
```

### Delete Pembayaran - DELETE /pembayaran/{id}
Delete payment. (**Admin only**)

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "Pembayaran deleted successfully",
  "data": null
}
```

---

## Error Responses

### 400 Bad Request
```json
{
  "status": "error",
  "message": "Field 'nama' is required",
  "data": null
}
```

### 401 Unauthorized
```json
{
  "status": "error",
  "message": "Unauthorized",
  "data": null
}
```

### 403 Forbidden
```json
{
  "status": "error",
  "message": "Forbidden - Admin access required",
  "data": null
}
```

### 404 Not Found
```json
{
  "status": "error",
  "message": "Siswa not found",
  "data": null
}
```

### 500 Internal Server Error
```json
{
  "status": "error",
  "message": "Database connection failed",
  "data": null
}
```

---

## Testing Guide

### Using cURL

**Login as Siswa:**
```bash
curl -X POST http://localhost/Apk_SPP/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "username": "10206080",
    "password": "123456",
    "type": "siswa"
  }'
```

**Get All Kelas:**
```bash
curl -X GET http://localhost/Apk_SPP/api/kelas \
  -H "Content-Type: application/json"
```

**Create Payment:**
```bash
curl -X POST http://localhost/Apk_SPP/api/pembayaran \
  -H "Content-Type: application/json" \
  -d '{
    "nisn": "10206080",
    "id_spp": 2,
    "jumlah_bayar": 250000,
    "bulan_dibayar": "Januari"
  }'
```

### Using Postman

1. Import the API endpoints
2. Set the Authorization header if using token-based auth
3. Set request method (GET, POST, PUT, DELETE)
4. Add JSON body for POST/PUT requests
5. Send request

## Dashboard Endpoint

### Get Dashboard - GET /dashboard
Get dashboard statistics and summaries.

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "Dashboard statistics retrieved",
  "data": {
    "total_siswa": 1,
    "total_kelas": 3,
    "total_spp_nominal": 1600000,
    "total_pembayaran_count": 4,
    "total_pembayaran_nominal": 980000,
    "total_potensi": 1600000,
    "belum_lunas": 0,
    "sudah_lunas": 1,
    "top_pembayaran": [
      {
        "id_pembayaran": 11,
        "jumlah_bayar": 500000,
        "nama": "Rezza",
        "tgl_bayar": "2026-02-24"
      }
    ],
    "top_siswa": [
      {
        "nisn": "10206080",
        "nama": "Rezza",
        "jumlah_pembayaran": 4,
        "total_bayar": 980000
      }
    ],
    "pembayaran_per_bulan": []
  }
}
```

---

## Report Endpoints

### Siswa Belum Lunas Report - GET /report?type=siswa-belum-lunas
Get report of students who haven't fully paid their SPP.

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "Siswa belum lunas report",
  "data": [
    {
      "nisn": "1234567890",
      "nama": "John Doe",
      "nis": "87654321",
      "nama_kelas": "X RPL",
      "nominal": 500000,
      "total_bayar": 250000,
      "sisa_bayar": 250000
    }
  ]
}
```

### Siswa Sudah Lunas Report - GET /report?type=siswa-sudah-lunas
Get report of students who have fully paid their SPP.

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "Siswa sudah lunas report",
  "data": [
    {
      "nisn": "10206080",
      "nama": "Rezza",
      "nis": "12345676",
      "nama_kelas": "XI RPL",
      "nominal": 500000,
      "total_bayar": 500000,
      "tgl_selesai": "2026-02-24"
    }
  ]
}
```

### Pembayaran Per Siswa Report - GET /report?type=pembayaran-per-siswa&nisn={nisn}
Get detailed payment report for a specific student.

**Query Parameters:**
- `nisn` (required): Student NISN

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "Pembayaran per siswa report",
  "data": {
    "siswa": {
      "nisn": "10206080",
      "nama": "Rezza",
      "nis": "12345676",
      "nama_kelas": "XI RPL",
      "nominal": 500000
    },
    "pembayaran": [
      {
        "id_pembayaran": 11,
        "tgl_bayar": "2026-02-24",
        "bulan_dibayar": "Januari",
        "tahun_dibayar": "2020",
        "jumlah_bayar": 500000,
        "nama_petugas": "Gibran",
        "total_sebelumnya": 0
      }
    ],
    "ringkasan": {
      "jumlah_pembayaran": 1,
      "total_bayar": 500000,
      "sisa_bayar": 0
    }
  }
}
```

### Pembayaran Per Bulan Report - GET /report?type=pembayaran-per-bulan&tahun={tahun}
Get payment summary by month for a specific year.

**Query Parameters:**
- `tahun` (optional, default: current year): Year in YYYY format

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "Pembayaran per bulan report",
  "data": {
    "tahun": "2026",
    "data_bulanan": [
      {
        "bulan_dibayar": "Februari",
        "tahun_dibayar": "2026",
        "jumlah_pembayaran": 1,
        "total_bayar": 500000,
        "jumlah_siswa": 1
      }
    ],
    "ringkasan": {
      "total_pembayaran": 1,
      "total_bayar": 500000,
      "total_siswa": 1
    }
  }
}
```

---

## Export/Import Endpoints

### Export Siswa - GET /export?action=export-siswa
Export all siswa data to CSV file. (**Admin only**)

**Response:**
Downloads a CSV file with siswa data.

### Export Pembayaran - GET /export?action=export-pembayaran
Export all pembayaran records to CSV file. (**Admin only**)

**Response:**
Downloads a CSV file with pembayaran data.

### Export SPP - GET /export?action=export-spp
Export all SPP data to CSV file. (**Admin only**)

**Response:**
Downloads a CSV file with SPP data.

### Import Siswa - POST /export?action=import-siswa
Import siswa from CSV file. (**Admin only**)

**Request (multipart/form-data):**
```
file: [CSV file]
```

**CSV Format (header required):**
```
NISN,NIS,Nama,Kelas,Tahun SPP,Nominal,Alamat,No Telp
1234567890,87654321,John Doe,1,2020,300000,Jl. Merdeka,081234567890
```

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "Import completed",
  "data": {
    "imported": 10,
    "errors": []
  }
}
```

---

## Testing Guide

### Using cURL

**Login as Siswa:**
```bash
curl -X POST http://localhost/Apk_SPP/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "username": "10206080",
    "password": "123456",
    "type": "siswa"
  }'
```

**Get Dashboard:**
```bash
curl -X GET http://localhost/Apk_SPP/api/dashboard \
  -H "Content-Type: application/json"
```

**Get Siswa Belum Lunas Report:**
```bash
curl -X GET "http://localhost/Apk_SPP/api/report?type=siswa-belum-lunas" \
  -H "Content-Type: application/json"
```

**Export Siswa CSV:**
```bash
curl -X GET http://localhost/Apk_SPP/api/export?action=export-siswa \
  -H "Content-Type: application/json" \
  -o siswa_export.csv
```

**Import Siswa from CSV:**
```bash
curl -X POST http://localhost/Apk_SPP/api/export?action=import-siswa \
  -F "file=@siswa_data.csv"
```

### Using Postman

1. Import the API collection from the documentation
2. Set up an environment with the base URL
3. Use Pre-request Script to get auth token
4. Make requests using saved examples

### PHP Example

```php
$base_url = 'http://localhost/Apk_SPP/api';

// Login
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => "$base_url/auth/login",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode([
        'username' => '10206080',
        'password' => '123456',
        'type' => 'siswa'
    ])
]);
$response = json_decode(curl_exec($ch), true);
$token = $response['data']['token'];

// Get Dashboard
curl_setopt_array($ch, [
    CURLOPT_URL => "$base_url/dashboard",
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ]
]);
$dashboard = json_decode(curl_exec($ch), true);
print_r($dashboard);
```

---

## Change Log

### Version 1.0.0 (Initial Release)
- Authentication endpoints (login, logout, me)
- CRUD operations for Siswa, Petugas, Kelas, SPP, Pembayaran
- Dashboard with statistics
- Report generation (siswa belum/sudah lunas, pembayaran details)
- Export/Import functionality (CSV)
- Role-based access control (siswa, petugas, admin)
- Session-based authentication

---

## Troubleshooting

### 401 Unauthorized
- Make sure you're logged in or have a valid token
- Check if the token is included in the Authorization header

### 403 Forbidden
- You don't have permission to access this resource
- Check your user role (siswa, petugas, admin)

### 404 Not Found
- The resource doesn't exist
- Check the NISN, ID, or endpoint URL

### 500 Internal Server Error
- Database connection error
- Check if the database is running and credentials are correct

### CSV Import Errors
- Make sure the CSV header matches exactly
- Check for encoding issues (use UTF-8)
- Validate data types (NISN must be 10 digits)

---

## Security Notes

- All passwords are hashed using MD5 (consider upgrading to bcrypt)
- SQL injection prevention via mysqli_real_escape_string
- CORS headers are set for all API requests
- Input validation is performed on all endpoints
- Session-based authentication for web applications
- Consider implementing rate limiting for production

---

## Performance Tips

- Use pagination for large datasets (implement in future versions)
- Cache dashboard statistics (implement Redis for production)
- Index frequently filtered columns in database
- Monitor database query performance
- Use connection pooling for high-traffic scenarios

---

## Future Enhancements

- JWT token-based authentication
- Role-based access control middleware
- Pagination and filtering
- API documentation UI (Swagger/OpenAPI)
- Webhook support for payment notifications
- Email notifications
- Activity logging
- Two-factor authentication
- API rate limiting
- Request/response logging and monitoring

---
