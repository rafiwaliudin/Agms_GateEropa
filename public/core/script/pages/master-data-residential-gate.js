function residentialGateTable() {
    $("#table-residential-gate").DataTable({
        processing: true,
        serverSide: true,
        order: [[0, "desc"]],
        ajax: {
            url: "/residential-gate/list",
            dataType: "json",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        },
        drawCallback: function () {
            $("#loadingResidentialGateListTable").hide();
        },
        columns: [
            { data: "no" },
            { data: "name" },
            { data: "longitude" },
            { data: "latitude" },
            { data: "cluster" },
            { data: "phone" },
            { data: "action" },
        ],
    });
}
