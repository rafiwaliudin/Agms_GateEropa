function allCustomerTable() {
    var startDate = $('#startDate').val();
    var endDate = $('#endDate').val();
    $('#customer-table').DataTable().destroy();
    $('#customer-table').DataTable({
        "dom": 'Bfrtip',
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, 2000, 5000],
            ['10 rows', '25 rows', '50 rows', '100 rows', '500 rows', '1000 rows', '2000 rows', '5000 rows']
        ],
        "buttons": [
            'pageLength',
            'excelHtml5',
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
            }
        ],
        "searching": false,
        "processing": true,
        "serverSide": true,
        "ordering": false,
        "responsive": true,
        "ajax": {
            "headers": {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            "url": "/customer/list",
            "type": "POST",
            "data": {
                'type': 'filter',
                'startDate': startDate,
                'endDate': endDate
            },
        },
        "columns": [
            {"data": "no"},
            {"data": "photo"},
            {"data": "name"},
            {"data": "date"},
            {"data": "gender"},
            {"data": "emotion"},
            {"data": "age"},
            {"data": "wear_mask"},
            {"data": "detected_duration"},
            {"data": "detected_gaze_duration"},
        ],
    });
}
