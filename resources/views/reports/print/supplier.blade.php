<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Supplier - {{ $report->supplier->nama }}</title>
    <style>
        @page {
            margin: 2cm;
            size: A4;
        }
        
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            color: #333;
            font-size: 12pt;
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
            font-size: 24pt;
            color: #333;
        }
        
        .header p {
            margin: 0;
            font-size: 12pt;
            color: #555;
        }
        
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .info-box {
            width: 48%;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            box-sizing: border-box;
        }
        
        .info-box h3 {
            margin-top: 0;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #eee;
            color: #444;
            font-size: 14pt;
        }
        
        .info-box p {
            margin: 8px 0;
        }
        
        .info-box .label {
            font-weight: bold;
            display: inline-block;
            width: 100px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: left;
            color: #333;
        }
        
        .no-column {
            width: 5%;
            text-align: center;
        }
        
        .date-column {
            width: 15%;
        }
        
        .po-column {
            width: 20%;
        }
        
        .total-column {
            width: 20%;
            text-align: right;
        }
        
        .user-column {
            width: 20%;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #f2f2f2;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .footer {
            margin-top: 40px;
            text-align: right;
        }
        
        .signature {
            margin-top: 60px;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        @media print {
            body {
                padding: 0;
                margin: 0;
                background-color: white;
            }
            
            .no-print {
                display: none;
            }
            
            table {
                page-break-inside: auto;
            }
            
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            
            .page-break {
                page-break-after: always;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>LAPORAN SUPPLIER</h1>
            <p>Periode: {{ $report->tanggal_awal->format('d F Y') }} - {{ $report->tanggal_akhir->format('d F Y') }}</p>
        </div>

        <div class="info-section">
            <div class="info-box">
                <h3>Informasi Supplier</h3>
                <p><span class="label">Nama</span>: {{ $report->supplier->nama }}</p>
                <p><span class="label">Kontak</span>: {{ $report->supplier->kontak ?: '-' }}</p>
                <p><span class="label">Alamat</span>: {{ $report->supplier->alamat ?: '-' }}</p>
            </div>
            <div class="info-box">
                <h3>Ringkasan Laporan</h3>
                <p><span class="label">Transaksi</span>: {{ $report->total_transaksi }} transaksi</p>
                <p><span class="label">Total</span>: Rp {{ number_format($report->total_nominal, 0, ',', '.') }}</p>
                <p><span class="label">Petugas</span>: {{ $report->user->name }}</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="no-column">No.</th>
                    <th class="po-column">Nomor PO</th>
                    <th class="date-column">Tanggal</th>
                    <th class="total-column">Total</th>
                    <th class="user-column">Petugas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($details as $index => $barang)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $barang->nomor }}</td>
                        <td>{{ $barang->tanggal->format('d/m/Y') }}</td>
                        <td class="text-right">Rp {{ number_format($barang->total, 0, ',', '.') }}</td>
                        <td>{{ $barang->user->name }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="3" class="text-right">Total</td>
                    <td class="text-right">Rp {{ number_format($details->sum('total'), 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p>{{ $report->kota ?: 'Jakarta' }}, {{ date('d F Y') }}</p>
            <div class="signature">
                <p>{{ $report->user->name }}</p>
                <p>Petugas</p>
            </div>
        </div>
    </div>

    <button onclick="window.print()" class="no-print" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; position: fixed; bottom: 20px; right: 20px;">
        Cetak Laporan
    </button>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>