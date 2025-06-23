<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: 'Calibri', sans-serif;
            color: #333;
            font-size: 11px;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px;
            font-size: 11px;
        }
        th {
            background-color: #2F75B5;
            color: #fff;
            text-align: center;
        }
        .footer {
            margin-top: 15px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>

    <div class="title">Laporan Transaksi Keuangan</div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Tipe</th>
                <th>Nominal</th>
                <th>Keterangan</th>
                <th>Dompet</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksis as $item)
                <tr>
                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $item->kategori->nama ?? '-' }}</td>
                    <td>{{ ucfirst($item->kategori->tipe ?? '-') }}</td>
                    <td>Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                    <td>{{ $item->dompet->nama ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>
