# Panduan Implementasi API Favorit (Laravel to Flutter)

Dokumen ini menjelaskan cara menggunakan API Favorit untuk mengelola daftar produk yang disukai oleh pengguna di aplikasi Flutter.

## 1. Daftar Endpoint API

Semua endpoint ini membutuhkan header `Authorization: Bearer <token>`.

| Method | Endpoint | Deskripsi | Parameter |
|--------|----------|-----------|-----------|
| **GET** | `/api/v1/favorit` | Mengambil daftar produk yang difavoritkan. | - |
| **POST** | `/api/v1/favorit/toggle` | Menambah/menghapus produk dari favorit (Toggle). | `product_id` |
| **POST** | `/api/v1/favorit` | Menambah produk ke favorit (Manual). | `product_id` |
| **DELETE** | `/api/v1/favorit/{id}` | Menghapus produk dari favorit berdasarkan ID produk. | - |

---

## 2. Struktur Data Response

### GET /api/v1/favorit
Mengembalikan objek yang berisi array `products`. Struktur produk sama dengan API Katalog.

```json
{
    "success": true,
    "message": "Daftar produk favorit.",
    "data": {
        "products": [
            {
                "id": 1,
                "name": "Produk Contoh",
                "slug": "produk-contoh",
                "price": 150000,
                "image": "https://domain.com/storage/products/image.jpg",
                "is_favorite": true
            }
        ]
    }
}
```

### POST /api/v1/favorit/toggle
Sangat direkomendasikan untuk digunakan pada icon ❤️ ("Like").

**Request:**
```json
{
    "product_id": 1
}
```

**Response:**
```json
{
    "success": true,
    "message": "Produk ditambahkan ke favorit.",
    "data": {
        "is_favorite": true
    }
}
```

---

## 3. Implementasi di Flutter

### Favorite Service
```dart
class FavoriteService {
  final String baseUrl = "https://api.anda.com/api/v1";

  // Ambil Daftar Favorit
  Future<List<ProductModel>> getFavorites(String token) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/favorit'),
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      if (response.statusCode == 200) {
        final Map<String, dynamic> body = json.decode(response.body);
        final List rawList = body['data']['products'];
        return rawList.map((e) => ProductModel.fromJson(e)).toList();
      }
      return [];
    } catch (e) {
      rethrow;
    }
  }

  // Toggle Favorit (Add/Remove)
  Future<bool> toggleFavorite(String token, int productId) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/favorit/toggle'),
        body: json.encode({'product_id': productId}),
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      if (response.statusCode == 200 || response.statusCode == 201) {
        final Map<String, dynamic> body = json.decode(response.body);
        return body['data']['is_favorite'];
      }
      return false;
    } catch (e) {
      rethrow;
    }
  }
}
```

### Tips Implementasi UI
1. **Optimistic UI**: Saat user klik tombol ❤️, langsung ubah warna icon di UI tanpa menunggu response API. Jika API gagal, baru kembalikan warnanya ke semula (rollback).
2. **Sync State**: Gunakan State Management (Provider/Bloc) agar perubahan status favorit di halaman "Detail" otomatis mengupdate widget di halaman "Daftar Produk".
3. **Empty State**: Tampilkan ilustrasi "Belum ada produk favorit" jika list kosong untuk meningkatkan User Experience (UX).

---
*Dokumen ini diperbarui pada 17 April 2026 mengikuti struktur API terbaru.*
