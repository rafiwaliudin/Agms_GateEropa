function areaTable() {
    $("#table-area").DataTable({
        processing: true,
        serverSide: true,
        order: [[0, "desc"]],
        ajax: {
            url: "/area/list",
            dataType: "json",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        },
        drawCallback: function () {
            $("#loadingAreaListTable").hide();
        },
        columns: [
            { data: "no" },
            { data: "name" },
            { data: "longitude" },
            { data: "latitude" },
            { data: "action" },
        ],
    });
}
