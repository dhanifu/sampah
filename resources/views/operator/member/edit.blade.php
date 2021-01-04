@extends('layouts.admin.admin')

@section('title', 'Edit Member')

@section('content-header')
<div class="col-sm-6">
    <h1 class="m-0 text-dark">Edit Member</h1>
</div>
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('operator.member.data.index') }}">Member</a></li>
        <li class="breadcrumb-item active">Edit Member</li>
    </ol>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                            class="fas fa-minus"></i></button>
                </div>
            </div>
            <!-- /.card-header -->
            <form action="{{ route('operator.member.data.update', $member->id) }}" method="POST" role="form">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" autofocus autocomplete="off"
                                    class="form-control @error('name') is-invalid @enderror" placeholder="Enter Name"
                                    value="{{ $member->name }}">
                                <p class="text-danger">{{ $errors->first('name') }}</p>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="birth_date">Birth Date</label>
                                <input type="date" name="birth_date" id="birth_date"
                                    class="form-control @error('birth_date')is-invalid @enderror"
                                    value="{{ $member->birth_date }}">
                                <p class="text-danger">{{ $errors->first('birth_date') }}</p>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select name="gender" id="gender"
                                    class="form-control @error('gender')is-invalid @enderror">
                                    <option value="" class="text-secondary">-- Select --</option>
                                    <option value="Laki-Laki" {{ $member->gender=="Laki-Laki"?'selected':'' }}>
                                        Male
                                    </option>
                                    <option value="Perempuan" {{ $member->gender=="Perempuan"?'selected':'' }}>
                                        Female
                                    </option>
                                </select>
                                <p class="text-danger">{{ $errors->first('gender') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="rt">RT</label>
                                <input type="text" name="rt" id="rt" placeholder="Enter ..." autocomplete="off"
                                    class="form-control @error('rt') is-invalid @enderror" value="{{ $member->rt }}"
                                    maxlength="2" onkeypress="return onlyNumber(event)">
                                <p class="text-danger">{{ $errors->first('rt') }}</p>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="rw">RW</label>
                                <input type="text" name="rw" id="rw" placeholder="Enter ..." autocomplete="off"
                                    class="form-control @error('rw') is-invalid @enderror" value="{{ $member->rw }}"
                                    maxlength="2" onkeypress="return onlyNumber(event)">
                                <p class="text-danger">{{ $errors->first('rw') }}</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="village_id">Village</label>
                                <select name="village_id" id="village_id" style="width: 100%"
                                    class="form-control select2 @error('village_id') is-invalid @enderror">
                                    <option value="">-- Select --</option>
                                    @foreach ($villages as $v)
                                    <option value="{{ $v->id }}" {{ $member->village->id==$v->id?'selected':'' }}>
                                        {{ $v->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <p class="text-danger">{{ $errors->first('village_id') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6"></div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea name="address" id="address" placeholder="Enter ..."
                                    class="form-control @error('address') is-invalid @enderror"
                                    rows="3">{{ $member->address }}</textarea>
                                <p class="text-danger">{{ $errors->first('address') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button class="btn btn-primary float-right">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(function(){
        $('.select2').select2({
            theme: 'bootstrap4'
        })
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            })
        @endif
    })

    function onlyNumber(evt){
        let charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 32 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
</script>
@endsection