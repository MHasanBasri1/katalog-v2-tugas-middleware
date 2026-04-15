# Implementasi Section Keunggulan & Kategori

Dokumen ini menjelaskan cara menangani section "Keunggulan" (Advantages) dan "Kategori" yang digabungkan dalam satu tampilan di Flutter.

## Struktur API Terkini

Endpoint `GET /api/v1/home` sekarang mengembalikan field `advantages` bersama dengan `categories`.

**Response Preview:**
```json
{
  "success": true,
  "data": {
    "banners": [...],
    "advantages": [
      {
        "id": 1,
        "title": "Jaminan Ori",
        "subtitle": "100% Original",
        "icon": "fas fa-certificate",
        "color": "#2563eb",
        "badge": "100%"
      },
      ...
    ],
    "categories": [
      {
        "id": 1,
        "name": "Elektronik",
        "slug": "elektronik",
        "icon": "fa-tv"
      },
      ...
    ]
  }
}
```

---

## 1. Model Dart (Keunggulan)

```dart
class AdvantageModel {
  final int id;
  final String title;
  final String subtitle;
  final String icon;
  final String color;
  final String? badge;

  AdvantageModel({
    required this.id,
    required this.title,
    required this.subtitle,
    required this.icon,
    required this.color,
    this.badge,
  });

  factory AdvantageModel.fromJson(Map<String, dynamic> json) {
    return AdvantageModel(
      id: json['id'],
      title: json['title'],
      subtitle: json['subtitle'],
      icon: json['icon'],
      color: json['color'],
      badge: json['badge'],
    );
  }
}
```

## 2. Cara Menggabungkan di Flutter UI

Karena di desain web kedua bagian ini berada dalam satu kontainer putih (`bg-white rounded-xl`), kita bisa menerapkannya dengan `Column` di dalam sebuah `Container` atau `Card`.

### Struktur Widget:
```dart
Column(
  children: [
    // 1. Bagian Keunggulan (Marquee/Horizontal List)
    HomeAdvantagesSection(advantages: advantages),
    
    Divider(height: 1), // Garis pemisah tipis
    
    // 2. Bagian Kategori (Grid/Horizontal List)
    HomeCategoriesSection(categories: categories),
  ],
)
```

### Tips Penggunaan Icon FontAwesome:
API mengirimkan string class FontAwesome (contoh: `fas fa-certificate`). Di Flutter, Anda bisa menggunakan package `font_awesome_flutter` dan membuat helper sederhana untuk mapping string ke IconData:

```dart
IconData getIconData(String iconClass) {
  // Mapping sederhana atau gunakan library pencarian icon
  if (iconClass.contains('fa-certificate')) return FontAwesomeIcons.certificate;
  if (iconClass.contains('fa-undo-alt')) return FontAwesomeIcons.rotateLeft;
  if (iconClass.contains('fa-truck-fast')) return FontAwesomeIcons.truckFast;
  if (iconClass.contains('fa-headset')) return FontAwesomeIcons.headset;
  return Icons.category; // fallback
}
```

## 3. Contoh Implementasi Section "Jadi Satu"

```dart
Widget buildUnifiedSection(List<AdvantageModel> ads, List<CategoryModel> cats) {
  return Container(
    margin: EdgeInsets.all(16),
    decoration: BoxDecoration(
      color: Colors.white,
      borderRadius: BorderRadius.circular(16),
      border: Border.all(color: Colors.grey.shade200),
    ),
    child: Column(
      children: [
        // Horizontal Scroll untuk Keunggulan (Mobile Look)
        SingleChildScrollView(
          scrollDirection: Axis.horizontal,
          padding: EdgeInsets.symmetric(vertical: 12, horizontal: 16),
          child: Row(
            children: ads.map((ad) => Padding(
              padding: EdgeInsets.only(right: 20),
              child: Row(
                children: [
                  Icon(getIconData(ad.icon), color: Color(int.parse(ad.color.replaceAll('#', '0xff'))), size: 18),
                  SizedBox(width: 8),
                  Text(ad.title, style: TextStyle(fontWeight: FontWeight.bold, fontSize: 12)),
                ],
              ),
            )).toList(),
          ),
        ),
        
        Divider(height: 1, indent: 16, endIndent: 16),
        
        // Grid untuk Kategori
        GridView.builder(
          shrinkWrap: true,
          physics: NeverScrollableScrollPhysics(),
          padding: EdgeInsets.all(16),
          gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
            crossAxisCount: 4,
            mainAxisSpacing: 16,
            crossAxisSpacing: 16,
          ),
          itemCount: cats.length,
          itemBuilder: (context, index) {
             final cat = cats[index];
             return Column(
               children: [
                 Container(
                   padding: EdgeInsets.all(12),
                   decoration: BoxDecoration(color: Colors.blue.withOpacity(0.1), borderRadius: BorderRadius.circular(12)),
                   child: Icon(getIconData(cat.icon), color: Colors.blue),
                 ),
                 SizedBox(height: 4),
                 Text(cat.name, style: TextStyle(fontSize: 10), textAlign: TextAlign.center),
               ],
             );
          },
        ),
      ],
    ),
  );
}
```
