# SPP API - Quick Start Guide

## Overview
REST API untuk Sistem Pembayaran Pembangunan (SPP) dengan fitur lengkap manajemen siswa, pembayaran, dan laporan.

## Folder Structure
```
api/
├── index.php                 # Main router
├── config.php               # Configuration & CORS
├── utils.php                # Utility functions
├── helpers.php              # Helper functions
├── auth.php                 # Authentication endpoints
├── siswa.php                # Student CRUD endpoints
├── petugas.php              # Officer CRUD endpoints
├── kelas.php                # Class CRUD endpoints
├── spp.php                  # SPP CRUD endpoints
├── pembayaran.php           # Payment CRUD endpoints
├── dashboard.php            # Dashboard & statistics
├── report.php               # Report generation
├── export.php               # Export/Import functionality
├── test-examples.php        # Example test cases
├── Postman_Collection.json  # Postman collection
└── .htaccess                # URL rewriting rules
```

## Installation

1. Copy the `api` folder to your project root directory
2. Update `config.php` with your database credentials if needed
3. Ensure `.htaccess` is enabled on your server
4. Test API by accessing `http://localhost/Apk_SPP/api`

## Quick Start

### 1. Authentication
All endpoints (except root) require authentication.

**Login:**
```bash
POST /api/auth/login
Content-Type: application/json

{
  "username": "10206080",
  "password": "123456",
  "type": "siswa"  // or "petugas"
}
```

### 2. Get User Data
After login, get current user info:
```bash
GET /api/auth/me
```

### 3. Access Resources
Use the returned token in subsequent requests:
```bash
GET /api/siswa
Authorization: Bearer YOUR_TOKEN
```

## Core Endpoints

### Authentication
- `POST /api/auth/login` - User login
- `POST /api/auth/logout` - User logout
- `GET /api/auth/me` - Get current user

### Master Data
- `GET/POST /api/siswa` - Student management
- `GET/POST /api/petugas` - Officer management
- `GET/POST /api/kelas` - Class management
- `GET/POST /api/spp` - SPP management

### Transactions
- `GET/POST /api/pembayaran` - Payment management

### Additional Features
- `GET /api/dashboard` - Statistics & dashboard
- `GET /api/report?type=...` - Various reports
- `GET/POST /api/export` - Data export/import

## Database Requirements

Your database should have these tables:
- `siswa` - Student data
- `petugas` - Officers
- `kelas` - Classes
- `spp` - SPP data
- `pembayaran` - Payment records

See `db_spp.sql` for complete schema.

## Security

- ✅ SQL injection prevention (mysqli_real_escape_string)
- ✅ CORS headers configured
- ✅ Role-based access control
- ✅ Password hashing (MD5 - consider upgrading to bcrypt)
- ✅ Session-based authentication

## Error Handling

All errors follow a standard format:
```json
{
  "status": "error",
  "message": "Error description",
  "data": null
}
```

HTTP Status Codes:
- `200` - Success
- `201` - Created
- `400` - Bad request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not found
- `500` - Server error

## Testing

### Using cURL
```bash
curl -X GET http://localhost/Apk_SPP/api/siswa \
  -H "Content-Type: application/json"
```

### Using Postman
1. Import `Postman_Collection.json`
2. Set base URL to `http://localhost/Apk_SPP/api`
3. Run requests

### Using PHP
```php
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => 'http://localhost/Apk_SPP/api/siswa',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json']
]);
$response = json_decode(curl_exec($ch), true);
print_r($response);
```

Run `test-examples.php` for complete examples.

## Common Issues

### 404 Not Found
- Check URL spelling
- Ensure `.htaccess` is enabled
- Verify mod_rewrite is active

### 401 Unauthorized
- Login first to get token
- Include token in Authorization header
- Check if session is still valid

### 403 Forbidden
- Verify user role has permission
- Admin-only endpoints need admin role
- Students can only access their own data

### 500 Internal Server Error
- Check database connection
- Verify table names match schema
- Check PHP error logs

## Performance Notes

- Queries are optimized for common operations
- Consider adding pagination for large datasets
- Use filtering parameters for better performance
- Cache reports and statistics when possible

## Future Enhancements

- [ ] Pagination & filtering
- [ ] JWT token authentication
- [ ] Webhook support
- [ ] Email notifications
- [ ] API documentation UI (Swagger)
- [ ] Request logging
- [ ] Rate limiting

## Support

For issues or questions, check:
1. [API_DOCUMENTATION.md](API_DOCUMENTATION.md) - Complete API documentation
2. [test-examples.php](test-examples.php) - Working examples
3. Database schema in `db_spp.sql`

## Version
API v1.0.0

## License
All rights reserved.

---

**Last Updated:** March 4, 2026
