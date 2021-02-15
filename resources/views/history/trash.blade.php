@extends('layouts.admin.admin')

@section('title', 'Tempat Sampah Transaksi')

@section('content-header')
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Transaction Trash</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('transaction.history.index') }}">History</a></li>
            <li class="breadcrumb-item active">Trash</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Transaction Trash</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-success mr-1" title="Restore All Data"
                            onclick="confirmRestore('restoreall')">Restore All</button>
                        <button type="button" class="btn btn-danger" title="Delete All Data Permanently"
                            onclick="confirmDelete('deleteall')">Delete All</button>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                            class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 1%">#</th>
                                    <th>Operator</th>
                                    <th>Member</th>
                                    <th>Date</th>
                                    <th style="width: 1%">Weight</th>
                                    <th>Total</th>
                                    <th style="width: 1%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($histories as $row)
                                    <tr>
                                        <td><strong>{{ $loop->iteration }}</strong></td>
                                        <td><strong>{{ $row->user->name }}</strong></td>
                                        <td>
                                            <strong>{{ $row->member->name }}</strong><br>
                                            <span><strong>Rt/Rw:</strong> {{ $row->member->rt .'/'.$row->member->rw }}</span><br>
                                            <span><strong>Alamat:</strong> {{ $row->member->address }} {{ $row->member->village->name }}</span>
                                        </td>
                                        <td>{{ localDate($row->date) }}</td>
                                        <td>{{ $row->weight }} Kg</td>
                                        <td>Rp {{ number_format($row->total) }}</td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Action">
                                                <button type="button" class="btn btn-info btn-sm" title="Restore"
                                                        onclick="confirmRestore(`{{$row->id}}`)">
                                                    <i class="fas fa-redo-alt fa-flip-horizontal"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm" title="Delete Permanent"
                                                        onclick="confirmDelete(`{{$row->id}}`)">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="col-md-12">
                        {!! $histories->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true
            });
        @endif
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true
            })
        @endif


        // Confirm Restore
        function confirmRestore(uuid){
            let teks = '';
            if (uuid == 'restoreall') {
                teks = 'This action will restore all data!';
            } else {
                teks = 'This action will restore the data!';
            }
            Swal.fire({
                title: "Are you sure?",
                text: teks,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, restore it!',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    let url = '';
                    let kalimat = '';
                    if (uuid=='restoreall') {
                        url = '{{ route("transaction.trash.restore-all") }}';
                        kalimat = 'Restoring all data';
                    } else {
                        let id = uuid;
                        url = '{{ route("transaction.trash.restore",":id") }}';
                        kalimat = 'Restoring the data';
                        url = url.replace(':id', id);
                    }
                    document.location.href=url;
                }
            });
        }

        // Confirm Delete
        function confirmDelete(uuid){
            let teks = '';
            if (uuid == 'deleteall') {
                teks = 'This action will delete all data permanently!';
            } else {
                teks = 'This action will delete the data permanently!';
            }
            Swal.fire({
                title: "Are you sure?",
                text: teks,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    let url = '';
                    let kalimat = '';
                    if (uuid=='deleteall') {
                        url = '{{ route("transaction.trash.delete-all") }}';
                        kalimat = 'Deleting all data permanently';
                    } else {
                        let id = uuid;
                        url = '{{ route("transaction.trash.delete",":id") }}';
                        kalimat = 'Deleting the data permanently';
                        url = url.replace(':id', id);
                    }
                    document.location.href=url;
                }
            });
        }
    </script>
@endsection