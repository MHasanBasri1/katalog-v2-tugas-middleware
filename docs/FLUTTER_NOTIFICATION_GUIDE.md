# Panduan Implementasi Notifikasi (Laravel to Flutter)

Dokumen ini menjelaskan cara mengintegrasikan sistem notifikasi database Laravel ke dalam aplikasi Flutter.

## 1. Daftar Endpoint API

Semua endpoint ini membutuhkan header `Authorization: Bearer <token>`.

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| **GET** | `/api/v1/notifications` | Mengambil daftar notifikasi user dan jumlah unread. |
| **POST** | `/api/v1/notifications/{id}/read` | Menandai satu notifikasi sebagai sudah dibaca. |
| **POST** | `/api/v1/notifications/read-all` | Menandai semua notifikasi sebagai sudah dibaca. |
| **DELETE** | `/api/v1/notifications/{id}` | Menghapus satu data notifikasi. |

---

## 2. Model Data (Dart)

Laravel menyimpan konten notifikasi di dalam field `data` (JSON). Pastikan model Anda memetakan field tersebut.

```dart
class NotificationModel {
  final String id;
  final String title;
  final String message;
  final String type; // success, info, warning
  final String? productSlug; // Diisi jika tipenya produk baru
  final String? readAt;
  final String createdAt;

  NotificationModel({
    required this.id,
    required this.title,
    required this.message,
    required this.type,
    this.productSlug,
    this.readAt,
    required this.createdAt,
  });

  bool get isRead => readAt != null;

  factory NotificationModel.fromJson(Map<String, dynamic> json) {
    // Data kustom dari Laravel ada di dalam object 'data'
    final Map<String, dynamic> customData = json['data'] ?? {};

    return NotificationModel(
      id: json['id'],
      title: customData['title'] ?? 'Pesan Baru',
      message: customData['message'] ?? '',
      type: customData['type'] ?? 'info',
      productSlug: customData['product_slug'], // Ambil slug untuk navigasi
      readAt: json['read_at'],
      createdAt: json['created_at'],
    );
  }
}
```

---

## 3. Notification Service (Dio Example)

```dart
class NotificationService {
  final Dio _dio = Dio(BaseOptions(baseUrl: 'https://api.anda.com/api/v1'));

  // Ambil Notifikasi (dengan Pagination)
  Future<Map<String, dynamic>> fetchNotifications(String token) async {
    try {
      final response = await _dio.get(
        '/notifications',
        options: Options(headers: {'Authorization': 'Bearer $token'}),
      );

      if (response.data['success']) {
        final List rawList = response.data['data']['notifications'];
        return {
          'notifications': rawList.map((e) => NotificationModel.fromJson(e)).toList(),
          'unreadCount': response.data['data']['unread_count'],
        };
      }
      return {'notifications': [], 'unreadCount': 0};
    } catch (e) {
      rethrow;
    }
  }

  // Mark as Read
  Future<void> markAsRead(String token, String id) async {
    await _dio.post(
      '/notifications/$id/read',
      options: Options(headers: {'Authorization': 'Bearer $token'}),
    );
  }
}
```

---

## 4. Sistem Trigger Otomatis (Backend)

Sistem telah memiliki **ProductObserver**. Anda tidak perlu memanggil API untuk mengirim notifikasi saat produk baru ditambah.

1. **Admin** menambah produk baru di Dashboard Web.
2. **Backend** otomatis mengirim `BroadcastNewProductJob`.
3. **Database** mencatat notifikasi untuk seluruh Member.
4. **App Flutter** akan melihat notifikasi tersebut saat melakukan refresh/fetch pada endpoint `/notifications`.

## 5. Tips Implementasi UI

- **Badge Notifikasi:** Gunakan field `unread_count` dari API untuk menampilkan angka di atas icon lonceng.
- **Warna Berdasarkan Tipe:**
  - `type == 'success'`: Hijau (biasanya untuk promo baru).
  - `type == 'info'`: Biru.
- **Empty State:** Berikan ilustrasi jika `notifications.isEmpty`.

---
*Dokumen ini dibuat secara otomatis pada 17 April 2026.*
