<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Transaksi ({{ localDateTime($transaction->date) }})</title>
    <link rel="stylesheet" href="{{ base_path().'/public/admin/dist/css/adminlte.min.css' }}">
    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> --}}
</head>
<body>
    @php
        $alamat = $transaction->member->address;
        $rt_rw = 'RT.'. $transaction->member->rt .'/RW.'. $transaction->member->rw;
        $desa = 'Ds. '. $transaction->member->village->name;
    @endphp

    <center>
        <h3 class="h3">Sampah</h3>
        <p>{{ localDateTime($transaction->date) }}</p>
    </center>

    <table cellspacing="0" cellpadding="4">
        <tr>
            <th width="1%">Tanggal</th>
            <td align="right">:</td>
            <td>{{ localDate($transaction->date) }}</td>
        </tr>
        <tr>
            <th>Operator</th>
            <td align="right">:</td>
            <td>{{ $transaction->user->name }}</td>
        </tr>
        <tr>
            <th>Nama</th>
            <td align="right">:</td>
            <td>{{ $transaction->member->name }}</td>
        </tr>
        <tr>
            <th>Alamat</th>
            <td align="right">:</td>
            <td>{{ $alamat }}, {{ $rt_rw }}, {{ $desa }}</td>
        </tr>
    </table>

    <br>

    <div class="row">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%">
                <thead>
                    <tr>
                        <th width="1%">No</th>
                        <th>Tipe</th>
                        <th>Harga/Kg</th>
                        <th>Berat (Kg)</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                        $weight = 0.00;
                    @endphp
                    @foreach($data as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->type->name }}</td>
                            <td>Rp {{ number_format($d->price) }}</td>
                            <td>{{ $d->weight }} Kg</td>
                            <td>Rp {{ number_format($d->price * $d->weight) }}</td>
                            @php
                                $total += $d->price * $d->weight;
                                $weight += $d->weight
                            @endphp
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" align="right"><strong class="pr-4">Subtotal</strong></td>
                        <td>{{ $weight }} Kg</td>
                        <td>Rp {{ number_format($total) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    {{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script> --}}
</body>
</html>