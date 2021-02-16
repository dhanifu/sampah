jQuery(function ($) {

    const table = (from_date = '', to_date = '') => {
        $("table").DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: ajaxUrl,
                type: 'get',
                data: {
                    from_date: from_date,
                    to_date: to_date
                },
                error: err => {
                    console.log(err)
                }
            },
            columns: [
                { data: 'DT_RowIndex' },
                { data: 'operator' },
                { data: 'member' },
                { data: 'date' },
                { data: 'weight' },
                { data: 'total' },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        })
    }

    table()

    const refresh = () => {
        $('#from_date').val('')
        $('#to_date').val('')
        $('table').DataTable().destroy()
        table()
    }

    $("#refresh").on('click', function () {
        refresh()
    })

    const success = msg => {
        Swal.fire({
            icon: 'success',
            title: msg,
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true
        })

        refresh()
    }
    const error = msg => {
        Swal.fire({
            icon: 'error',
            title: msg,
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true
        })
    }

    const remove = id => {
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
                const url = deleteUrl.replace(':id', id)

                $.ajax({
                    url: url,
                    type: 'post',
                    data: {
                        _token: csrf,
                        _method: 'DELETE'
                    },
                    success: res => {
                        $("#history-count-trash").html(res.count)
                        success(res.success)
                    },
                })
            }
        });
    }


    $('#filter').on('click', function () {
        let from_date = $('#from_date').val()
        let to_date = $('#to_date').val()
        if (from_date != '' && to_date != '') {
            $('table').DataTable().destroy()
            table(from_date, to_date)
        } else {
            error('Pilih daterange terlebih dahulu')
        }
    })

    $('tbody').on('click', '#remove', function () {
        const id = $(this).attr('data-id')
        remove(id)
    })
})