function employeeTable() {
    $.ajax({
        url: "employee/list",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: "POST",
        dataType: "JSON",
        success: function (data) {
            $('#loadingEmployeeListTable').hide();
            var tableData = data.data.data;
            $('#table-employee').DataTable({
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
                    {"data": "nik"},
                    {"data": "name"},
                    {"data": "pob"},
                    {"data": "dob"},
                    {"data": "gender"},
                    {"data": "position"},
                    {"data": "department"},
                    {"data": "role"},
                    {"data": "photo"},
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
