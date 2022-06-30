function vehicleTable() {
    $("#table-vehicle").DataTable({
        processing: true,
        serverSide: true,
        order: [[0, "desc"]],
        ajax: {
            url: "/vehicle/list",
            dataType: "json",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        },
        drawCallback: function () {
            $("#loadingVehicleListTable").hide();
        },
        columns: [
            { data: "no" },
            { data: "license_plate" },
            { data: "car_type" },
            { data: "car_color" },
            { data: "release_status" },
            { data: "time_status" },
            { data: "position_status" },
            { data: "action" },
        ],
    });
}
