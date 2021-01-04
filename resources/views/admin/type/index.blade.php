@extends('layouts.admin.admin')

@section('title', 'Type')

@section('head')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('content-header')
<div class="col-sm-6">
    <h1 class="m-0 text-dark">Type</h1>
</div>
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Type</li>
    </ol>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Show Data From Type</h3>
                <div class="float-right">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal"
                            data-tipemodal="tambah">
                        Tambah
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="datatable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($types as $tipe)
                        <tr>
                            <td>{{ $tipe->name }}</td>
                            <td>Rp {{ number_format($tipe->price) }}</td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Action">
                                    <button type="button" class="btn btn-info mr-1" data-toggle="modal" data-target="#modal"
                                        data-tipemodal="edit" data-id="{{$tipe->id}}" data-name="{{$tipe->name}}"
                                        data-price="{{$tipe->price}}">
                                        <i class="far fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger"
                                        onclick="confirmDelete('{{$tipe->id}}')">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                    <form id="data-{{$tipe->id}}" action="{{route('admin.type.destroy', $tipe->id)}}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                    </form>
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

@section('modal')
{{-- MODAL ADDNEW TYPE --}}
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 10px">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Form Type</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-modal" action="" method="POST">
                @csrf
                <div id="buatmethod"></div>
                <div class="modal-body">
                    <div class="col-md-12 mt-2">
                        <div class="row">
                            <label for="name" class="col-md-3 col-form-label">Name</label>
                            <div class="col-md-9">
                                <input id="name" type="text" class="form-control" autocomplete="off"
                                    name="name" value="{{ old('name') }}" placeholder="Type Name">
                                <p class="text-danger">{{ $errors->first('name') }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <label for="price" class="col-md-3 col-form-label">Price</label>
                            <div class="col-md-9">
                                <input id="price" type="number" class="form-control"
                                    name="price" value="{{ old('price') }}" placeholder="Price">
                                <p class="text-danger">{{ $errors->first('price') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btnsubmit" disabled>Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
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

        @if(count($errors) > 0)
            $('#modal').modal('show');
        @endif

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000,
            });
        @endif
        @if(session('error'))
            Swal.fire({
                icon: 'danger',
                title: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 2000,
            })
        @endif


        $('#modal').on('show.bs.modal', function(e){
            let button = $(e.relatedTarget);
            let tipemodal = button.data('tipemodal');
            let modal = $(this);

            if (tipemodal == "tambah") {
                modal.find('#form-modal #buatmethod').html('');
                let url = "{{route('admin.type.store')}}";
                modal.find('#form-modal').attr('action', url);
            }
            if (tipemodal == "edit") {
                modal.find('#form-modal #buatmethod').html(`@method('put')`);
                let id = button.data('id');
                let name = button.data('name');
                let price = button.data('price');
                let url = '{{ route("admin.type.update",":id") }}';
                url = url.replace(':id', id);
                modal.find('#form-modal').attr('action', url);
                modal.find('.modal-body #name').val(name);
                modal.find('.modal-body #price').val(price)
            }

            $('#name').keyup(function(){
                if ($(this).val() && $('#price').val()) {
                    $('#btnsubmit').attr('disabled', false);
                }else{
                    $('#btnsubmit').attr('disabled', true);
                }
            });
            $('#price').keyup(function(){
                if ($(this).val() && $('#name').val()) {
                    $('#btnsubmit').attr('disabled', false);
                }else{
                    $('#btnsubmit').attr('disabled', true);
                }
            });
        });
    });

    $('#btnsubmit').click(function(){
        $('#modal').modal('hide');
        Swal.fire({
            title: "Saving data",
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            onOpen: ()=>{
                Swal.showLoading();
            }
        });
    });


    function confirmDelete(id){
        Swal.fire({
            title: "Are you sure?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            allowOutsideClick: false,
        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    title: "Deleting to trash",
                    showConfirmButton: false,
                    timer: 2300,
                    timerProgressBar: true,
                    onOpen: ()=>{
                        $('#data-' + id).submit();
                        Swal.showLoading();
                    }
                });
            }
        });
    }
</script>
@endsection