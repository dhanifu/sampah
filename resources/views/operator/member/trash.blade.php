@extends('layouts.admin.admin')

@section('title', 'Tempat Sampah Member')

@section('head')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('content-header')
<div class="col-sm-6">
    <h1 class="m-0 text-dark">Member Trash</h1>
</div>
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.village.index') }}">Member</a></li>
        <li class="breadcrumb-item active">Trash</li>
    </ol>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Member Trash</h3>
                <div class="row float-right">
                    <button type="button" class="btn btn-success mr-1" title="Restore All Data"
                        onclick="confirmRestore('restoreall')">Restore All</button>
                    <button type="button" class="btn btn-danger" title="Delete All Data Permanently"
                        onclick="confirmDelete('deleteall')">Delete All</button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="datatable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <td style="width: 1%">#</td>
                            <th>Name</th>
                            <th style="width: 1%">Gender</th>
                            <th>RT/RW</th>
                            <th>Village</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($members as $m)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $m->name }}</td>
                            <td>{{ $m->gender=="Laki-Laki"?'L':'P' }}</td>
                            <td>{{ "RT.{$m->rt}/RW.{$m->rw}" }}</td>
                            <td>{{ $m->village->name }}</td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Action">
                                    <button type="button" class="btn btn-secondary" data-toggle="modal"
                                        data-target="#memberDetail" data-name="{{$m->name}}"
                                        data-birthdate="{{$m->birth_date}}" data-gender="{{$m->gender}}"
                                        data-address="{{$m->address}}" data-rt="{{$m->rt}}"
                                        data-village="{{get_village_name($m->village_id)}}" data-rw="{{$m->rw}}">
                                        Detail
                                    </button>
                                    <button type="button" class="btn btn-success ml-1"
                                        onclick="confirmRestore('{{$m->uuid}}')">
                                        Restore
                                    </button>
                                    <button type="button" class="btn btn-danger ml-1"
                                        onclick="confirmDelete('{{$m->uuid}}')">
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
                let timer = 0;
                if (uuid=='deleteall') {
                    url = '{{ route("operator.member.trash.deleteall",$user_uuid) }}';
                    kalimat = 'Deleting all data permanently';
                    timer = 2000;
                } else {
                    let id = uuid;
                    url = '{{ route("admin.village.trash.delete",":id") }}';
                    kalimat = 'Deleting the data permanently';
                    timer = 1900;
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
                    url = '{{ route("operator.member.trash.restoreall",$user_uuid) }}';
                    kalimat = 'Restoring all data';
                    timer = 2000;
                } else {
                    let id = uuid;
                    url = '{{ route("operator.member.trash.restore",":id") }}';
                    kalimat = 'Restoring the data';
                    timer = 1900;
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