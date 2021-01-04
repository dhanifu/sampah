@extends('layouts.admin.admin')

@section('title', 'Tempat Sampah Village')

@section('head')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('content-header')
<div class="col-sm-6">
    <h1 class="m-0 text-dark">Village Trash</h1>
</div>
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.village.index') }}">Village</a></li>
        <li class="breadcrumb-item active">Trash</li>
    </ol>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Show Data Type Trash</h3>
                <div class="row float-right">
                    <button class="btn btn-success mr-1"
                        onclick="confirmRestore('restoreall')">
                        Restore All
                    </button>
                    <button type="button" class="btn btn-danger" title="Permanent Delete"
                        onclick="confirmDelete('deleteall')">Delete All</button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="datatable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <td>#</td>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($villages as $v)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $v->name }}</td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Action">
                                    <button class="btn btn-success mr-1"
                                        onclick="confirmRestore('{{ $v->id }}')">
                                        Restore
                                    </button>
                                    <button type="button" class="btn btn-danger"
                                        onclick="confirmDelete('{{$v->id}}')">
                                        Delete Rermanent
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- DataTables -->
<script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

<script>
    $(function(){
        $('#datatable').DataTable({
            "responsive": true,
            "autoWidth": false,
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 1800,
                timerProgressBar: true
            });
        @endif
        @if(session('error'))
            Swal.fire({
                icon: 'danger',
                title: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 1800,
                timerProgressBar: true
            })
        @endif
    });
    
    function confirmDelete(uuid){
        Swal.fire({
            title: "Are you sure?",
            text: 'Tindakan ini bersifat permanen!',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            allowOutsideClick: false
        }).then((result) => {
            if (result.value) {
                if (uuid=='deleteall') {
                    let url = '{{ route("admin.village.trash.deleteall",$user_uuid) }}';
                    Swal.fire({
                        title: "Deleting all data Permanent",
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true,
                        onOpen: ()=>{
                            window.location.href=url;
                            Swal.showLoading();
                        }
                    });
                } else {
                    let id = uuid;
                    let url = '{{ route("admin.village.trash.delete",":id") }}';
                    url = url.replace(':id', id);
                    Swal.fire({
                        title: "Deleting permanent",
                        showConfirmButton: false,
                        timer: 2100,
                        timerProgressBar: true,
                        allowOutsideClick: false,
                        onOpen: ()=>{
                            window.location.href=url;
                            Swal.showLoading();
                        }
                    });
                }
            }
        });
    }

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
                let timer = 0;
                if (uuid=='restoreall') {
                    url = '{{ route("admin.village.trash.restoreall",$user_uuid) }}';
                    kalimat = 'Restoring all data';
                    timer = 2100;
                } else {
                    let id = uuid;
                    url = '{{ route("admin.village.trash.restore",":id") }}';
                    kalimat = 'Restoring the data';
                    timer = 2100;
                    url = url.replace(':id', id);
                }
                Swal.fire({
                    title: kalimat,
                    showConfirmButton: false,
                    timer: timer,
                    timerProgressBar: true,
                    allowOutsideClick: false,
                    onOpen: ()=>{
                        window.location.href=url;
                        Swal.showLoading();
                    }
                });
            }
        });
    }
</script>
@endsection