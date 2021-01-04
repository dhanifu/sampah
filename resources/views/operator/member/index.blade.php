@extends('layouts.admin.admin')

@section('title', 'Member')

@section('head')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('content-header')
<div class="col-sm-6">
    <h1 class="m-0 text-dark">Member</h1>
</div>
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Member List</li>
    </ol>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Show Data Member</h3>
                <div class="float-right">
                    <button type="button" class="btn btn-primary"
                        onclick="document.location.href='{{ route('operator.member.data.create') }}'">
                        Tambah
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="datatable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th style="width: 1px">Gender</th>
                            <th>RT/RW</th>
                            <th>Vilage</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($members as $member)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->gender=="Laki-Laki"?'L':'P' }}</td>
                            <td>Rt {{ $member->rt }}/ Rw {{ $member->rw }}</td>
                            <td>{{ $member->village->name }}</td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Action">
                                    <button type="button" class="btn btn-secondary" data-toggle="modal"
                                        data-target="#memberDetail" data-name="{{$member->name}}"
                                        data-birthdate="{{$member->birth_date}}" data-gender="{{$member->gender}}"
                                        data-address="{{$member->address}}" data-rt="{{$member->rt}}"
                                        data-village="{{get_village_name($member->village_id)}}"
                                        data-rw="{{$member->rw}}">
                                        <i class="far fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-info"
                                        onclick="document.location.href='{{ route('operator.member.data.edit',$member->uuid) }}'">
                                        <i class="far fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger"
                                        onclick="confirmDelete('{{$member->uuid}}')">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                    <form id="data-{{$member->uuid}}"
                                        action="{{route('operator.member.data.destroy', $member->uuid)}}" method="POST">
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
<div class="modal fade" id="memberDetail" tabindex="-1" role="dialog" aria-labelledby="memberDetailLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 10px">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="memberDetailLabel">Member Detail</h5>
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
                            <label class="col-md-2 col-form-label">Name</label>
                            <div class="col-sm-1 d-none d-md-block"><span>:</span></div>
                            <div class="col-md-9">
                                <p class="form-control bg-light" id="detail-name"></p>
                            </div>
                            <label class="col-md-2 col-form-label">Birth Date</label>
                            <div class="col-sm-1 d-none d-md-block"><span>:</span></div>
                            <div class="col-md-9">
                                <p class="form-control bg-light" id="detail-birth"></p>
                            </div>
                            <label class="col-md-2 col-form-label">Gender</label>
                            <div class="col-sm-1 d-none d-md-block"><span>:</span></div>
                            <div class="col-md-9">
                                <p class="form-control bg-light" id="detail-gender"></p>
                            </div>
                            <label class="col-md-2 col-form-label">Rt/Rw</label>
                            <div class="col-sm-1 d-none d-md-block"><span>:</span></div>
                            <div class="col-md-9">
                                <p class="form-control bg-light" id="detail-rtrw"></p>
                            </div>
                            <label class="col-md-2 col-form-label">Village</label>
                            <div class="col-sm-1 d-none d-md-block"><span>:</span></div>
                            <div class="col-md-9">
                                <p class="form-control bg-light" id="detail-village"></p>
                            </div>
                            <label class="col-md-2 col-form-label">Address</label>
                            <div class="col-sm-1 d-none d-md-block"><span>:</span></div>
                            <div class="col-md-9">
                                <textarea class="form-control bg-light" id="detail-address" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
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

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
        @endif
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            })
        @endif

        $('#memberDetail').on('show.bs.modal', function(e){
            let button = $(e.relatedTarget);
            let modal = $(this);
            let data = {
                'name':button.data('name'), 'birth':button.data('birthdate'),
                'gender':button.data('gender'), 'village':button.data('village'),
                'address':button.data('address'), 'rtrw':`RT.${button.data('rt')}/RW.${button.data('rw')}`,
            };
            
            for (let i in data) {
                modal.find(`.modal-body #detail-${i}`).text(data[i]);
            }
        })
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
                    allowOutsideClick: false,
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