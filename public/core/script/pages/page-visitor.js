function visitorTable() {
    $("#table-visitor").DataTable({
        processing: true,
        serverSide: true,
        order: [[0, "desc"]],
        ajax: {
            url: "visitor/list",
            dataType: "json",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        },
        drawCallback: function () {
            $("#loadingVisitorListTable").hide();
        },
        columns: [
            { data: "no" },
            { data: "name" },
            { data: "license_plate" },
            { data: "qrcode_image_path" },
            { data: "qrcode_expiry_date" },
            { data: "additional_information" },
            { data: "position_status" },
            { data: "action" },
        ],
    });
}
