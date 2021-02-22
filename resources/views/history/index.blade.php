@extends('layouts.admin.admin')

@section('title', 'Histori Transaksi')

@section('content-header')
<div class="col-sm-6">
    <h1 class="m-0 text-dark">History Transaksi</h1>
</div>
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Histories</li>
    </ol>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Grafik</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                            class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body">
                
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Histories</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                            class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="input-group mb-3 col-md-2 float-left">
                    <button type="button" class="btn btn-secondary" id="refresh" title="Refresh">
                        <i class="fa fa-redo-alt"></i>
                    </button>
                </div>
                <div class="input-group col-md-8 float-right input-daterange">
                    <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" readonly />
                    <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly />
                    <div class="input-group-append">
                        <button class="btn btn-secondary" id="filter" type="button">Filter</button>
                    </div>

                    <div class="btn-group-vertical">
                        <div class="btn-group" role="group">
                            <button id="btnExport" type="button" class="btn btn-primary dropdown-toggle ml-2" 
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
                                Export
                            </button>
                            <div class="dropdown-menu mr-5" aria-labelledby="btnExport">
                                <a href="javascript:void(0)" class="dropdown-item" id="exportpdf">
                                    PDF <i class="far fa-file-excel float-right"></i>
                                </a>
                                <a href="javascript:void(0)" class="dropdown-item" id="exportexcel">
                                    Excel <i class="far fa-file-pdf float-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="table-responsive">
                    <br>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 1%">#</th>
                                <th>Operator</th>
                                <th>Member</th>
                                <th>Date</th>
                                <th style="width: 1%">Weight</th>
                                <th style="width: 60px">Total</th>
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
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('script')
    <!-- DataTables -->
<script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>

<script>
    const ajaxUrl = '{{ route('transaction.history.index') }}'
    const deleteUrl = '{{ route('transaction.history.destroy', ':id') }}'
    const pdfUrl = '{{ route('transaction.history.history-pdf', ':date') }}'
    const excelUrl = '{{ route('transaction.history.history-excel', ':date') }}'
    const csrf = '{{ csrf_token() }}'
    $('.input-daterange').datepicker({
        todayBtn:'linked',
        format:'yyyy-mm-dd',
        autoclose:true
    });

</script>
<script src="{{ asset('js/history.js') }}"></script>
@endsection