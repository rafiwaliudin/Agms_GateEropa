function allReportTable() {
    var filterDate = $('#filterDate').val();
    $('#report-table').DataTable().destroy();
    $('#report-table').DataTable({
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
        "searching": true,
        "ordering": true,
        "responsive": true,
        "ajax": {
            "headers": {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            "url": "/report/list",
            "type": "POST",
            "data": {
                'filterDate': filterDate,
            },
        },
        "columns": [
            {"data": "no"},
            {"data": "date"},
            {"data": "time"},
            {"data": "view"},
            {"data": "onsite"},
            {"data": "male"},
            {"data": "female"},
            {"data": "average_age"},
            {"data": "average_male_age"},
            {"data": "average_female_age"},
        ],
    });
}

function allReportDownloadTable() {
    $('#report-download-table').DataTable().destroy();
    $('#report-download-table').DataTable({
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
        "searching": true,
        "ordering": true,
        "responsive": true,
        "ajax": {
            "headers": {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            "url": "/report/download-list",
            "type": "POST",
        },
        "columns": [
            {"data": "no"},
            {"data": "name"},
            {"data": "created_at"},
            {"data": "action"},
        ],
    });
}

function downloadReport($type) {
    $('#download-type-modal').modal('toggle');
    $('#downloadReport').replaceWith('<button id="downloadReport" class="btn btn-info" type="button"><span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span>Loading...</button>');
    var startDate = $('#startDate').val();
    var endDate = $('#endDate').val();
    $.ajax({
        url: "/download-report/",
        method: "POST",
        dataType: 'JSON',
        data: {
            'startDate': startDate,
            'endDate': endDate,
            'type': $type
        },
        success:
            function (data) {

            }
    });
}
