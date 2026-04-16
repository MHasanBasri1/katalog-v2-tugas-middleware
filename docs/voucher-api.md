# Voucher API Documentation & Flutter Implementation

Dokumentasi ini menjelaskan cara menggunakan API Voucher dan penerapannya pada aplikasi mobile (Flutter).

## 1. API Endpoints

### Get All Active Vouchers
Mengambil daftar voucher yang saat ini aktif dan belum kadaluarsa.

- **URL**: `/api/v1/vouchers`
- **Method**: `GET`
- **URL**: `/api/v1/vouchers`
- **Method**: `GET`
- **Headers**: 
  - `Accept: application/json`
  - `Authorization: Bearer <token>` (**WAJIB**: Akses hanya untuk member terautentikasi)

**Response (Success):**
```json
{
    "success": true,
    "message": "Success fetch vouchers",
    "data": [
        {
            "id": 1,
            "code": "PROMO2024",
            "name": "Diskon Awal Tahun",
            "description": "Diskon belanja semua produk",
            "type": "fixed",
            "value": 50000.0,
            "min_purchase": 200000.0,
            "max_discount": null,
            "start_date": "2024-01-01T00:00:00.000000Z",
            "end_date": "2024-12-31T23:59:59.000000Z",
            "usage_limit": 100,
            "used_count": 10,
            "is_active": true,
            "is_claimed": false
        }
    ]
}
```

### Get Voucher Detail / Validate
Validasi voucher berdasarkan kodenya.

- **URL**: `/api/v1/vouchers/{code}`
- **Method**: `GET`
- **Headers**: 
  - `Accept: application/json`
  - `Authorization: Bearer <token>` (**WAJIB**)
- **Response (Valid):** Sama seperti format objek tunggal di atas (mengandung `is_claimed`).
- **Response (Error 401):** Jika token tidak ada atau tidak valid.
- **Response (Error 404):** Jika kode voucher tidak ditemukan.

### Claim / Increment Voucher Usage
Gunakan endpoint ini saat pengguna melakukan tindakan "Salin" (Copy) atau "Klaim" voucher. Ini akan meningkatkan `used_count` (yang di admin dianggap sebagai pengurangan stok/kuota).

- **URL**: `/api/v1/vouchers/{code}/claim`
- **Method**: `POST`
- **Headers**: 
  - `Accept: application/json`
  - `Authorization: Bearer <token>` (**WAJIB**)
- **Response (Success):**
```json
{
    "success": true,
    "message": "Voucher berhasil diklaim",
    "data": null
}
```
- **Response (Error 400):** Jika voucher sudah habis atau tidak valid lagi.
- **PENTING**: 
    1. Endpoint ini mengimplementasikan proteksi 1 User 1 Claim. Jika user yang sama memanggil berkali-kali, stok hanya akan berkurang 1 kali.
    2. Aplikasi Flutter harus memanggil endpoint ini saat tombol "Salin" ditekan.
    3. Setelah sukses memanggil endpoint ini, nilai `is_claimed` pada list voucher akan otomatis menjadi `true` pada request berikutnya.


---

## 2. Implementasi di Flutter

### Service Example (Fetching with Token)
```dart
import 'dart:convert';
import 'package:http/http.dart' as http;

class VoucherService {
  final String baseUrl = "https://domain-anda.com/api/v1";

  Future<List<Voucher>> getVouchers(String token) async {
    final response = await http.get(
      Uri.parse('$baseUrl/vouchers'),
      headers: {
        'Accept': 'application/json',
        'Authorization': 'Bearer $token',
      },
    );

    if (response.statusCode == 200) {
      final List data = json.decode(response.body)['data'];
      return data.map((item) => Voucher.fromJson(item)).toList();
    } else if (response.statusCode == 401) {
      throw Exception('Sesi berakhir, silakan login kembali');
    } else {
      throw Exception('Gagal memuat voucher');
    }
  }

  Future<void> claimVoucher(String token, String code) async {
    final response = await http.post(
      Uri.parse('$baseUrl/vouchers/$code/claim'),
      headers: {
        'Accept': 'application/json',
        'Authorization': 'Bearer $token',
      },
    );

    if (response.statusCode != 200) {
      throw Exception('Gagal klaim voucher: ${json.decode(response.body)['message']}');
    }
  }
}
```

### Voucher Model
Gunakan model ini untuk melakukan parsing data dari API. 

```dart
class Voucher {
  final int id;
  final String code;
  final String name;
  final String? description;
  final String type; // 'fixed' atau 'percentage'
  final double value;
  final double minPurchase;
  final double? maxDiscount;
  final DateTime? endDate;
  final int? usageLimit;
  final int usedCount;
  final bool isActive;
  final bool isClaimed;

  Voucher({
    required this.id,
    required this.code,
    required this.name,
    this.description,
    required this.type,
    required this.value,
    required this.minPurchase,
    this.maxDiscount,
    this.endDate,
    this.usageLimit,
    required this.usedCount,
    required this.isActive,
    required this.isClaimed,
  });

  factory Voucher.fromJson(Map<String, dynamic> json) {
    return Voucher(
      id: json['id'],
      code: json['code'],
      name: json['name'],
      description: json['description'],
      type: json['type'],
      value: (json['value'] as num).toDouble(),
      minPurchase: (json['min_purchase'] as num).toDouble(),
      maxDiscount: json['max_discount'] != null 
          ? (json['max_discount'] as num).toDouble() 
          : null,
      endDate: json['end_date'] != null 
          ? DateTime.parse(json['end_date']) 
          : null,
      usageLimit: json['usage_limit'],
      usedCount: json['used_count'] ?? 0,
      isActive: json['is_active'] ?? false,
      isClaimed: json['is_claimed'] ?? false,
    );
  }
}
```

### Catatan Penting untuk Mobile Developer
1. **Wajib Login**: Endpoint Voucher sekarang berada di grup route yang diproteksi. Jika tidak mengirimkan token, API akan mengembalikan status `401`.
2. **Tipe Data Numerik**: API mengirimkan nilai numerik. Di Dart, gunakan `(json['field'] as num).toDouble()` untuk menghindari error type casting.
3. **Handle 401**: Jika mendapatkan error 401, aplikasi Flutter harus menghapus token lokal dan mengarahkan pengguna ke halaman Login.
4. **Format Keuangan**: Gunakan package `intl` untuk format rupiah (contoh: `Rp 50.000`).
