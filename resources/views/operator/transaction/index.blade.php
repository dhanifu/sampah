@extends('layouts.admin.admin')

@section('title', 'Transaksi')

@section('head')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<style>
    .select2 {
        width: 100%!important;
    }
</style>
@endsection

@section('content-header')
<div class="col-sm-6">
    <h1 class="m-0 text-dark">Transaksi</h1>
</div>
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Transaksi</li>
    </ol>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <form method="post" id="dynamic_form">
                @csrf
                <span id="result"></span>
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-2">
                            <label for="member_id">Member Name</label>
                        </div>
                        <div class="col-md-8">
                            <select name="member_id" id="member_id" class="form-control mb-2"></select>
                            @error('member_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-primary" onclick="document.location.href='{{ route('operator.member.data.create') }}'">New Member</button>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered" id="table">
                        <thead>
                            <tr>
                                <th width="68px">No</th>
                                <th>Type</th>
                                <th style="width: 15%">Price</th>
                                <th style="width: 15%">Weight (Kg)</th>
                                <th style="width: 15%">Subtotal</th>
                                <th style="width: 1%">Act</th>
                            </tr>
                        </thead>
                        <tbody id="dynamic_field">

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6" align="center">
                                    <button type="submit" class="btn btn-primary btn-block btn-md col-md-6 mt-3" id="save"><i class="fa fa-save"></i> Save</button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
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
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
    $(document).ready(function() {
        $('#member_id').select2({
            placeholder: "Pilih Member",
            ajax: {
                url: `{{ route('api.get-member') }}`,
                type: 'post',
                dataType: "json",
                data: params => {
                    return {
                        _token: CSRF_TOKEN,
                        name: params.term
                    }
                },
                processResults: data => {
                    return {
                        results: data
                    }
                },
                cache: true
            },
            templateSelection: data => {
                return data.name
            }
        });
    });

    async function transactionEachColumn(index) {
        let fetchData = await fetch(`{{ route('api.get-type') }}`)
        let response = JSON.parse(await fetchData.text())

        let $select2 = $('select[name="transactions['+index+'][type_id]"]').select2({
            placeholder: "Pilih Tipe"
        }).empty()

        $select2.append($("<option></option>").attr("value", '').text('Choose Type'))

        $.each(response, function(key, data){
            $select2.append($("<option></option>").attr("value", data.id).text(data.name))
        })

        // Get Price
        $select2.on('select2:select', async function(e) {
            let data = e.params.data

            let fetchData = await fetch(`{{ route('api.get-type-object') }}?` + new URLSearchParams(data))
            let response = JSON.parse(await fetchData.text())
            // console.log(response.price)

            $('input[name="transactions['+index+'][price]"]').val(response.price)
            $('input[name="transactions['+index+'][weight]"]').attr('disabled', false)
        }).trigger('change')

        // Sum of price and weight
        $('input[name="transactions['+index+'][weight]"]').keyup(function(e){
            if (($(this).val() == '')) {
                $(this).attr('placeholder', '0')
            }
            let weight = $(this).val()
            let price = $('input[name="transactions['+index+'][price]"]').val()

            let subtotal = price * weight
            $('input[name="transactions['+index+'][subtotal]"]').val(subtotal)
        })

    }

    function field_dinamis(){
        let index = $('#dynamic_field tr').length
        let html = `<tr class="rowComponent">
                <input type="hidden" width="10px" name="transactions[${index}][id]" value="${undefined}">
                <td class="no">
                    <input type="text" value="${index + 1}" class="form-control" disabled>
                </td>
                <td>
                    <select name="transactions[${index}][type_id]" class="form-control select-${index}"></select>
                </td>
                <td>
                    <input type="number" name="transactions[${index}][price]" class="form-control" readonly>
                </td>
                <td>
                    <input type="text" name="transactions[${index}][weight]" class="form-control" disabled maxlength="3" onkeypress="return onlyNumber(event)">
                </td>
                <td>
                    <input type="number" name="transactions[${index}][subtotal]" class="form-control" readonly>
                </td>`
        if (index >= 1) {
            html += `<td>
                    <button type="text" name="remove" class="btn btn-danger text-white btn_remove"><i class="fa fa-trash"></i></button>
                </td></tr>`
            $("#dynamic_field").append(html)
        } else {
            html += `<td>
                    <button type="button" class="btn btn-success" id="add"><i class="fa fa-plus"></i></button>
                </td></tr>`
            $("#dynamic_field").append(html)
        }

        transactionEachColumn(index)
    }

    field_dinamis()

    $(document).ready(function(){
        getNumberOfTr()
        
        $('#add').click(function(){
            field_dinamis()
        })

        $(document).on('click', '.btn_remove', function() {
            let parent = $(this).parent()
            let id = parent.data('id')

            let delete_data = $("input[name='delete_data']").val()
            if(id !== 'undefined' && id !== undefined) {
                $("input[name='delete_data']").val(delete_data + ';' + id)
            }

            $('.btn_remove').eq($('.btn_remove').index(this)).parent().parent().remove()
            getNumberOfTr()
        })
        
    })

    function getNumberOfTr() {
        $('#dynamic_field tr').each(function(index, tr) {
            $(this).find("td.no input").val(index + 1)
        })
    }

    function onlyNumber(evt){
        let charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode > 32 && (charCode < 48 || charCode > 57)) {
            return false
        }
        return true
    }
    $('#dynamic_form').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url:'{{ route("operator.transaction.data.store") }}',
            method:'post',
            data:$(this).serialize(),
            dataType:'json',
            beforeSend:function(){
                $('#save').attr('disabled', true)
            },
            success: async function(data)
            {
                if (data.error) {
                    $('#result').html('<div class="alert alert-danger">'+data.error+'</div>')
                } else {
                    $('#result').html('<div class="alert alert-success">'+data.success+'</div>')
                    $("#dynamic_field").html('')
                    getNumberOfTr()
                    field_dinamis()
                    $('#add').click(function(){
                        field_dinamis()
                    })
                }
                $('#save').attr('disabled', false)
            }
        }).then((response) => {
            let tanggal = response.tanggal
            detailTransaksi(tanggal)
        })
    })
    function detailTransaksi(tanggal) {
        setTimeout(function() {
            window.location.href=`transaction/history/detail?date=${tanggal}`
        }, 1000);
    }

</script>
@endsection