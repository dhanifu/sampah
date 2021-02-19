<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Transaksi</title>
    <style>
        * {
            font-size: 100%;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        .mv-2 {
            margin: 20px 0 20px 0;
        }
    </style>
</head>
<body>
    <h3 style="text-align: center">Laporan Transaksi Periode ({{ $date[0] }} s.d {{ $date[1] }})</h3>
    <hr class="mv-2">
    <table width=100% align="center" border="1" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th>Operator</th>
                <th>Member</th>
                <th>Tipe</th>
                <th>Tanggal</th>
                <th>Weight</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; $total_weight = 0; @endphp
            @forelse ($trashes as $row)
                <tr>
                    <td><strong>{{ $row->trash->user->name }}</strong></td>
                    <td>
                        <strong>{{ $row->trash->member->name }}</strong><br>
                        <label><strong>Rt/Rw:</strong> {{ $row->trash->member->rt }}/{{ $row->trash->member->rw }}</label><br>
                        <label><strong>Alamat:</strong> {{ $row->trash->member->address }} {{ $row->trash->member->village->name }}</label>
                    </td>
                    <td>{{ $row->type->name }}</td>
                    <td>{{ $row->created_at->format('d-m-Y') }}</td>
                    <td>{{ $row->weight }} Kg</td>
                    <td>Rp {{ number_format($row->subtotal) }}</td>
                </tr>

                @php
                    $total += $row->subtotal; 
                    $total_weight += $row->weight;
                @endphp
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" align="center">Total</td>
                <td>{{ $total_weight }} Kg</td>
                <td>Rp {{ number_format($total) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>