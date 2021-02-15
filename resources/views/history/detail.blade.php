@extends('layouts.admin.admin')

@section('title', 'Detail Transaction')

@section('content-header')
<div class="col-sm-6">
    <h1 class="m-0 text-dark">Detail Transaksi</h1>
</div>
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('transaction.history.index') }}">Histories</a></li>
        <li class="breadcrumb-item active">Transaction Detail</li>
    </ol>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-8">
                <h1 class="h3">Sampah</h1>
                <p>{{ localDateTime($transaction->date) }}</p>
            </div>
            <div class="col-sm-4">
                <dl class="row mx-0">
                    <dt class="col-sm-4 px-0">Tanggal</dt>
                    <dd class="col-sm-8 row px-0 mx-0 text-right">
                        <span class="col-sm-2 px-0">:</span>
                        <span class="col-sm-10 px-0">{{ localDate($transaction->date) }}</span>
                    </dd>
                    <dt class="col-sm-4 px-0">Operator</dt>
                    <dd class="col-sm-8 row px-0 mx-0 text-right">
                        <span class="col-sm-2 px-0">:</span>
                        <span class="col-sm-10 px-0">{{ $transaction->user->name }}</span>
                    </dd>
                    <dt class="col-sm-4 px-0">Nama Warga</dt>
                    <dd class="col-sm-8 row px-0 mx-0 text-right">
                        <span class="col-sm-2 px-0">:</span>
                        <span class="col-sm-10 px-0">{{ $transaction->member->name }}</span>
                    </dd>
                </dl>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tipe</th>
                        <th>Harga/Kg</th>
                        <th>Berat (Kg)</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
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
                            @endphp
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" align="right"><strong>Subtotal</strong></td>
                        <td>Rp {{ number_format($total) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{-- route('transaction.print',$transaction->invoice) --}}" class="btn btn-primary">Print</a>
        <a href="{{ route('transaction.history.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection