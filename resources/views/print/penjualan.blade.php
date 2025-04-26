<!-- filepath: d:\Herd\tiga-saudara\resources\views\print\sale.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Penjualan #{{ $penjualan->nomor }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .receipt {
            width: 80mm;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .info {
            margin-bottom: 20px;
        }
        .info p {
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
        .total {
            text-align: right;
            margin-top: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="logo">
            <div class="title">{{ config('app.name') }}</div>
            <p>Jl. Jendral Sudirman No. 1, Kota Pekanbaru, Riau</p>
        </div>

        <div class="info">
            <p>No: {{ $penjualan->nomor }}</p>
            <p>Tanggal: {{ $penjualan->tanggal->format('d/m/Y H:i') }}</p>
            <p>Kasir: {{ $penjualan->user->name }}</p>
        </div>

        <table>
            <tr>
                <th>Item</th>
                <th style="text-align: right">Qty</th>
                <th style="text-align: right">Harga</th>
                <th style="text-align: right">Total</th>
            </tr>
            @foreach($penjualan->detail as $item)
            <tr>
                <td>{{ $item->produk->nama }}</td>
                <td style="text-align: right">{{ $item->jumlah }}</td>
                <td style="text-align: right">{{ number_format($item->harga, 0, ',', '.') }}</td>
                <td style="text-align: right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </table>

        <div class="total">
            <p>Total: Rp {{ number_format($penjualan->total, 0, ',', '.') }}</p>
            <p>Bayar: Rp {{ number_format($penjualan->jumlah_bayar, 0, ',', '.') }}</p>
            <p>Kembali: Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</p>
        </div>

        <div class="footer">
            <p>Terima kasih atas kunjungan anda</p>
            <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>