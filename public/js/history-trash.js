jQuery(function ($) {
    const table = $("table").DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: ajaxUrl,
            type: "get",
        },
        columns: [
            { data: "DT_RowIndex" },
            { data: "operator" },
            { data: "member" },
            { data: "date" },
            { data: "weight" },
            { data: "total" },
            {
                data: "action",
                orderable: false,
                searchable: false,
            },
        ],
    });

    const reload = () => table.ajax.reload();

    const success = (msg) => {
        Swal.fire({
            icon: "success",
            title: msg,
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
        });
        reload();
    };

    const error = (msg) => {
        Swal.fire({
            icon: "error",
            title: msg,
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
        });
        reload();
    };

    const restore = (id) => {
        const url = restoreUrl.replace(":id", id);
        Swal.fire({
            title: "Are you sure?",
            text: "Aksi ini akan merestore data",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, restore it!",
            allowOutsideClick: false,
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: url,
                    type: "get",
                    success: (res) => {
                        success(res.success);
                        setTimeout(() => {
                            $("#history-count-trash").html(res.count);
                        }, 2000);
                    },
                });
            }
        });
    };

    const remove = (id) => {
        const url = deleteUrl.replace(":id", id);
        Swal.fire({
            title: "Apakah Anda yakin?",
            text: "Aksi ini akan menghapus data secara permanen",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
            allowOutsideClick: false,
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: url,
                    type: "get",
                    success: (res) => {
                        success(res.success);
                        setTimeout(() => {
                            $("#history-count-trash").html(res.count);
                        }, 2000);
                    },
                });
            }
        });
    };

    const restoreAll = () => {
        const url = restoreAllUrl;
        Swal.fire({
            title: "Are you sure?",
            text: "Aksi ini akan merestore semua data",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, restore it!",
            allowOutsideClick: false,
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: url,
                    type: "get",
                    success: (res) => {
                        success(res.success);
                        setTimeout(() => {
                            $("#history-count-trash").html(res.count);
                        }, 2000);
                    },
                    error: (err) => {
                        console.log(err);
                    },
                });
            }
        });
    };

    const removeAll = () => {
        const url = deleteAllUrl;
        Swal.fire({
            title: "Apakah Anda yakin?",
            text: "Aksi ini akan menghapus semua data secara permanen",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
            allowOutsideClick: false,
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: url,
                    type: "get",
                    success: (res) => {
                        success(res.success);
                        setTimeout(() => {
                            $("#history-count-trash").html(res.count);
                        }, 2000);
                    },
                });
            }
        });
    };

    $("tbody").on("click", "button", function () {
        const action = $(this).data("action");
        const data = table.row($(this).parents("tr")).data();

        switch (action) {
            case "restore":
                restore(data.id);
                break;
            case "remove":
                remove(data.id);
                break;
        }
    });

    $(".card-tools").on("click", "button", function () {
        const action = $(this).data("action");

        switch (action) {
            case "refresh":
                reload();
                break;
            case "restore-all":
                restoreAll();
                break;
            case "remove-all":
                removeAll();
                break;
        }
    });
});
