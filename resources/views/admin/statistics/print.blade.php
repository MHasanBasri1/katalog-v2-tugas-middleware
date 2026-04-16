<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Statistik - {{ strtoupper($range) }} - {{ date('d/m/Y') }}</title>
    <style>
        @page { size: A4; margin: 2cm; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #1a1a1a; line-height: 1.5; margin: 0; padding: 0; font-size: 11px; }
        .container { max-width: 100%; margin: 0 auto; }
        
        /* Header */
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #f0f0f0; padding-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; color: #1e40af; text-transform: uppercase; letter-spacing: 1px; }
        .header p { margin: 5px 0 0; color: #666; font-size: 12px; }
        
        /* Summary Box */
        .summary-container { display: flex; justify-content: space-between; gap: 10px; margin-bottom: 30px; }
        .summary-item { flex: 1; padding: 15px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; text-align: center; }
        .summary-label { display: block; font-size: 9px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 5px; }
        .summary-value { display: block; font-size: 18px; font-weight: 800; color: #0f172a; }

        /* Tables */
        h2 { font-size: 14px; color: #1e293b; border-left: 4px solid #3b82f6; padding-left: 10px; margin: 30px 0 15px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; background: #fff; }
        th { background-color: #f1f5f9; color: #475569; font-weight: bold; text-align: left; padding: 10px; border: 1px solid #e2e8f0; font-size: 10px; text-transform: uppercase; }
        td { padding: 8px 10px; border: 1px solid #e2e8f0; vertical-align: top; }
        tr:nth-child(even) { background-color: #f8fafc; }

        .marketplace-list { margin: 0; padding: 0; list-style: none; }
        .marketplace-item { display: inline-block; background: #f1f5f9; padding: 2px 6px; border-radius: 4px; margin-right: 4px; margin-bottom: 4px; font-size: 9px; font-weight: bold; border: 1px solid #e2e8f0; }
        
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .footer { margin-top: 50px; text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #f0f0f0; padding-top: 20px; }

        @media print {
            .no-print { display: none; }
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body onload="window.print(); window.onafterprint = function() { window.close(); }">
    <div class="container">
        <div class="header">
            <h1>Laporan Statistik Katalog</h1>
            <p>Rentang Waktu: <strong>{{ strtoupper($range === 'all' ? 'Semua Waktu' : $range) }}</strong> | Dicetak pada: {{ date('d F Y, H:i') }}</p>
        </div>

        <div class="summary-container">
            <div class="summary-item">
                <span class="summary-label">Total Views</span>
                <span class="summary-value">{{ number_format($summary['total_views']) }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Total Klik Marketplace</span>
                <span class="summary-value">{{ number_format($summary['total_clicks']) }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Produk Terdaftar</span>
                <span class="summary-value">{{ number_format($summary['total_products']) }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Link Marketplace</span>
                <span class="summary-value">{{ number_format($summary['total_links']) }}</span>
            </div>
        </div>

        <h2>Performa Per Platform</h2>
        <table>
            <thead>
                <tr>
                    <th>Marketplace</th>
                    <th class="text-right">Jumlah Link</th>
                    <th class="text-right">Total Klik</th>
                </tr>
            </thead>
            <tbody>
                @foreach($marketplaceStats as $stat)
                    <tr>
                        <td class="font-bold">{{ $stat->marketplace }}</td>
                        <td class="text-right">{{ number_format($stat->link_count) }}</td>
                        <td class="text-right font-bold">{{ number_format($stat->total_clicks) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h2>Rincian Klik Marketplace per Produk</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 40%">Nama Produk</th>
                    <th>Rincian Marketplace</th>
                    <th class="text-right" style="width: 15%">Total Klik</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topClickedProducts as $product)
                    <tr>
                        <td class="font-bold">{{ $product->name }}</td>
                        <td>
                            <div class="marketplace-list">
                                @foreach($product->marketplaceLinks as $link)
                                    <span class="marketplace-item">{{ $link->marketplace }}: {{ number_format($link->link_clicks) }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="text-right font-bold">{{ number_format($product->range_count) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="page-break-before: always;"></div>

        <h2>Rincian Views Produk</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th class="text-right" style="width: 20%">Jumlah Views</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topViewedProducts as $product)
                    <tr>
                        <td class="font-bold">{{ $product->name }}</td>
                        <td class="text-right font-bold">{{ number_format($product->range_count) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Kataloque Admin System - Laporan Statistik Otomatis</p>
        </div>
    </div>
</body>
</html>
