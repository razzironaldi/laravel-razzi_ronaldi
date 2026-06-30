# API Documentation

Base URL:
http://127.0.0.1:8000

## Endpoint

POST /api/register
Fungsi: registrasi user

POST /api/login
Fungsi: login user dan mendapatkan token

POST /api/logout
Fungsi: logout user

GET /api/products
Fungsi: melihat daftar product
Query: per_page, search, category, is_active

POST /api/products
Fungsi: membuat product baru
Role: admin

GET /api/products/{id}
Fungsi: melihat detail product

PUT /api/products/{id}
Fungsi: update product
Role: admin

DELETE /api/products/{id}
Fungsi: hapus product
Role: admin

## Contoh Request Product

{
  "name": "Laptop Lenovo",
  "sku": "PRD-1001",
  "category": "Elektronik",
  "price": 7500000,
  "stock": 10,
  "is_active": true,
  "description": "Laptop untuk kerja."
}

## Status Code

200: request berhasil
201: data berhasil dibuat
204: data berhasil dihapus
401: belum login
403: tidak punya akses
422: validasi gagal