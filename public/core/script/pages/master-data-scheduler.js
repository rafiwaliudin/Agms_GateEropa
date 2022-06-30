function schedulerTable() {
    $.ajax({
        url: "scheduler/list",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: "POST",
        dataType: "JSON",
        success: function (data) {
            $('#loadingSchedulerReportListTable').hide();
            var tableData = data.data.data;
            $('#table-scheduler').DataTable({
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
                    {"data": "email_to"},
                    {"data": "email_cc_1"},
                    {"data": "email_cc_2"},
                    {"data": "email_cc_3"},
                    {"data": "email_cc_4"},
                    {"data": "email_cc_5"},
                    {"data": "schedule_time"},
                    {"data": "range"},
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
