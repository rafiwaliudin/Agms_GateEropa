function userTable() {
    $.ajax({
        url: "user/list",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: "POST",
        dataType: "JSON",
        success: function (data) {
            $('#loadingUserListTable').hide();
            var tableData = data.data.data;
            $('#table-user').DataTable({
                "dom": 'Bfrtip',
                "buttons": [
                    'pageLength',
                    'excelHtml5',
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'LEGAL'
                    }
                ],
                "rowReorder": {
                    "selector": 'td:nth-child(2)'
                },
                "responsive": true,
                "data": tableData,
                "columns": [
                    {"data": "no"},
                    {"data": "name"},
                    {"data": "email"},
                    {"data": "role"},
                    {"data": "action"},
                ],
                "columnDefs": [
                    {
                        "targets": '_all',
                        "className": 'dt-body-center'
                    }],
            })
        }
    })
}
