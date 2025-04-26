<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <style>
        @page {
            margin: 2cm;
            size: A4 landscape;
        }
        
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            color: #333;
            font-size: 11pt;
        }
        
        .container {
            max-width: 100%;
            padding: 0;
        }
        
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #ddd;
        }
        
        .header h1 {
            margin: 0 0 10px 0;
            font-size: 18pt;
            color: #333;
        }

        .info-section {
            margin-bottom: 30px;
        }
        
        .info-box {
            margin-bottom: 20px;
        }
        
        .info-box h3 {
            margin: 0 0 10px 0;
            font-size: 12pt;
            color: #666;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10pt;
        }
        
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        
        th {
            background-color: #f8f8f8;
            font-weight: bold;
            color: #666;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #f8f8f8;
        }

        .product-details {
            margin: 10px 0;
            background: #f9f9f9;
            border: 1px solid #eee;
            padding: 10px;
        }

        .product-table {
            font-size: 9pt;
        }

        .product-table th, 
        .product-table td {
            padding: 4px 8px;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
        }
        
        .signature {
            margin-top: 60px;
        }
        
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>LAPORAN PENJUALAN</h1>
            <p>Periode: {{ $report->tanggal_awal->format('d F Y') }} - {{ $report->tanggal_akhir->format('d F Y') }}</p>
        </div>

        <div class="info-section">
            <div class="info-box">
                <h3>Ringkasan Laporan</h3>
                <table>
                    <tr>
                        <td width="200">Total Transaksi</td>
                        <td>: {{ $report->total_transaksi }} transaksi</td>
                    </tr>
                    <tr>
                        <td>Total Penjualan</td>
                        <td>: Rp {{ number_format($details->sum('total'), 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Total Laba Kotor</td>
                        <td>: Rp {{ number_format($details->sum('laba_kotor'), 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Dibuat Oleh</td>
                        <td>: {{ $report->user->name }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @foreach($details as $index => $penjualan)
            <div class="transaction-section">
                <table>
                    <tr class="total-row">
                        <td width="150">No. Invoice</td>
                        <td width="150">Tanggal</td>
                        <td width="150">Kasir</td>
                        <td width="200" class="text-right">Total Penjualan</td>
                        <td width="200" class="text-right">Laba Kotor</td>
                    </tr>
                    <tr>
                        <td>{{ $penjualan->nomor }}</td>
                        <td>{{ $penjualan->tanggal->format('d/m/Y H:i') }}</td>
                        <td>{{ $penjualan->user->name }}</td>
                        <td class="text-right">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($penjualan->laba_kotor, 0, ',', '.') }}</td>
                    </tr>
                </table>

                <div class="product-details">
                    <table class="product-table">
                        <tr>
                            <th width="40">No</th>
                            <th>Produk</th>
                            <th class="text-right">Harga Beli</th>
                            <th class="text-right">Harga Jual</th>
                            <th class="text-center" width="60">Qty</th>
                            <th class="text-right">Subtotal</th>
                            <th class="text-right">Laba</th>
                        </tr>
                        @foreach($penjualan->detail as $i => $detail)
                            <tr>
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td>{{ $detail->produk->nama }}</td>
                                <td class="text-right">Rp {{ number_format($detail->produk->harga_beli, 0, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $detail->jumlah }}</td>
                                <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format(($detail->harga - $detail->produk->harga_beli) * $detail->jumlah, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        @endforeach

        <div class="footer">
            <p>{{ now()->format('d F Y') }}</p>
            <div class="signature">
                <p>{{ $report->user->name }}</p>
                <p>Petugas</p>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>