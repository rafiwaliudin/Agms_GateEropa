function vehicleCountingHistoryTable() {
    var startDate = $("#startDate").val();
    var endDate = $("#endDate").val();
    $("#vehicleCounting-history-table").DataTable().destroy();
    $("#vehicleCounting-history-table").DataTable({
        dom: "Bfrtip",
        lengthMenu: [
            [10, 25, 50, 100, 500, 1000, 2000, 5000],
            [
                "10 rows",
                "25 rows",
                "50 rows",
                "100 rows",
                "500 rows",
                "1000 rows",
                "2000 rows",
                "5000 rows",
            ],
        ],
        buttons: [
            "pageLength",
            "excelHtml5",
            {
                extend: "pdfHtml5",
                orientation: "landscape",
                pageSize: "LEGAL",
            },
        ],
        processing: true,
        serverSide: true,
        order: [[0, "desc"]],
        ajax: {
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: "/vehicle_counting/history/list",
            type: "POST",
            data: {
                startDate: startDate,
                endDate: endDate,
            },
        },
        drawCallback: function () {
            $("#loadingVehicleCountingHistoryListTable").hide();
        },
        columns: [
            { data: "no" },
            { data: "image" },
            { data: "license_plate" },
            { data: "area" },
            { data: "cluster" },
            { data: "residentialGate" },
            { data: "status" },
            { data: "timestamp" },
        ],
    });
}