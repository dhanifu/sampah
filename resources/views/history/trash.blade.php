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
                        <button type="button" class="btn btn-secondary" id="reload" title="Refresh"
                            data-action="refresh">
                            <i class="fa fa-redo-alt"></i>
                        </button>
                        <button type="button" class="btn btn-success mr-1" title="Restore All Data"
                            data-action="restore-all">Restore All</button>
                        <button type="button" class="btn btn-danger" title="Delete All Data Permanently"
                            data-action="remove-all">Delete All</button>
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
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('head')
<link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('script')
    <script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

    <script>
        const ajaxUrl = '{{ route('transaction.trash.index') }}'
        const restoreUrl = '{{ route('transaction.trash.restore', ':id') }}'
        const deleteUrl = '{{ route('transaction.trash.delete', ':id') }}'
        const restoreAllUrl = '{{ route('transaction.trash.restore-all') }}'
        const deleteAllUrl = '{{ route('transaction.trash.delete-all') }}'
    </script>
    <script src="{{ asset('js/history-trash.js') }}"></script>
@endsection