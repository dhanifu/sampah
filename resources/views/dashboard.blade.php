@extends('layouts.admin.admin')

@section('title', 'Dashboard')

@section('content-header')
<div class="col-sm-6">
    <h1 class="m-0 text-dark">Dashboard</h1>
</div><!-- /.col -->
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item active">Home</li>
        <li class="breadcrumb-item"></li>
    </ol>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0">Halo, {{ $greeting }}</h5>
            </div>
            <div class="card-body">
                <h6 class="card-title">Special title treatment</h6>

                <p class="card-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Totam labore numquam
                    distinctio, quisquam dolorum aut omnis facilis atque consectetur fugiat, repellendus in ex. Eveniet
                    delectus consectetur dicta, rerum veniam atque.</p>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(function(){
        @if (session('success'))
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            })
        @endif
    });
</script>
@endsection