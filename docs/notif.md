# Implementasi Notifikasi di Flutter

Dokumen ini berisi contoh penerapan API Notifikasi pada aplikasi Flutter.

## API Endpoints

| Method | Endpoint | Deskripsi | Auth |
|--------|----------|-----------|------|
| GET | `/api/v1/notifications` | List notifikasi & jumlah unread | Ya |
| POST | `/api/v1/notifications/{id}/read` | Mark as read per ID | Ya |
| POST | `/api/v1/notifications/read-all` | Mark as read semua | Ya |
| DELETE| `/api/v1/notifications/{id}` | Hapus notifikasi | Ya |

---

## 1. Model Notifikasi (Dart)

```dart
class NotificationModel {
  final String id;
  final String title;
  final String message;
  final String? type;
  final DateTime? readAt;
  final DateTime createdAt;

  NotificationModel({
    required this.id,
    required this.title,
    required this.message,
    this.type,
    this.readAt,
    required this.createdAt,
  });

  bool get isRead => readAt != null;

  factory NotificationModel.fromJson(Map<String, dynamic> json) {
    // Data biasanya dibungkus dalam field 'data' oleh Laravel
    Map<String, dynamic> data = json['data'] ?? {};
    
    return NotificationModel(
      id: json['id'],
      title: data['title'] ?? 'Notifikasi',
      message: data['message'] ?? '',
      type: json['type'],
      readAt: json['read_at'] != null ? DateTime.parse(json['read_at']) : null,
      createdAt: DateTime.parse(json['created_at']),
    );
  }
}
```

## 2. Notification Service (Dio)

Pastikan Bearer Token sudah disertakan dalam header.

```dart
class NotificationService {
  final Dio _dio = Dio(BaseOptions(baseUrl: 'YOUR_BASE_URL/api/v1'));

  // Ambil Daftar Notifikasi
  Future<Map<String, dynamic>> getNotifications(String token) async {
    try {
      final response = await _dio.get(
        '/notifications',
        options: Options(headers: {'Authorization': 'Bearer $token'}),
      );

      if (response.data['success']) {
        List data = response.data['data']['notifications'];
        int unreadCount = response.data['data']['unread_count'];
        
        return {
          'notifications': data.map((e) => NotificationModel.fromJson(e)).toList(),
          'unreadCount': unreadCount,
        };
      }
      return {'notifications': [], 'unreadCount': 0};
    } catch (e) {
      rethrow;
    }
  }

  // Tandai Sudah Dibaca
  Future<bool> markAsRead(String token, String id) async {
    try {
      final response = await _dio.post(
        '/notifications/$id/read',
        options: Options(headers: {'Authorization': 'Bearer $token'}),
      );
      return response.data['success'];
    } catch (e) {
      return false;
    }
  }
}
```

## 3. Contoh UI Sederhana

```dart
class NotificationScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text("Notifikasi")),
      body: FutureBuilder(
        future: notificationService.getNotifications(userToken),
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return Center(child: CircularProgressIndicator());
          }
           if (!snapshot.hasData) return Center(child: Text("Tidak ada notifikasi"));

          final list = snapshot.data['notifications'] as List<NotificationModel>;

          return ListView.builder(
            itemCount: list.length,
            itemBuilder: (context, index) {
              final item = list[index];
              return ListTile(
                leading: Icon(
                  Icons.notifications, 
                  color: item.isRead ? Colors.grey : Colors.blue
                ),
                title: Text(item.title, style: TextStyle(
                  fontWeight: item.isRead ? FontWeight.normal : FontWeight.bold
                )),
                subtitle: Text(item.message),
                onTap: () {
                   // Panggil API markAsRead di sini
                },
              );
            },
          );
        },
      ),
    );
  }
}
```

## Tips: Mengapa Notifikasi Tidak Muncul?
1. **Token Expired/Missing**: Pastikan header `Authorization: Bearer <token>` terkirim.
2. **Middleware**: Pastikan route di Laravel dibungkus `auth:sanctum` atau middleware token yang sesuai. (Di proyek ini menggunakan `api.token`).
3. **Data Mapping**: Laravel Database Notifications menyimpan data kustom di kolom `data` (JSON). Pastikan model Dart membaca dari `json['data']`.
4. **CORS**: Jika data tidak muncul, cek log konsol apakah ada error CORS (jika testing via web) atau error 401/403.
