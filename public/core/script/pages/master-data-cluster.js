function clusterTable() {
    $("#table-cluster").DataTable({
        processing: true,
        serverSide: true,
        order: [[0, "desc"]],
        ajax: {
            url: "/cluster/list",
            dataType: "json",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        },
        drawCallback: function () {
            $("#loadingClusterListTable").hide();
        },
        columns: [
            { data: "no" },
            { data: "name" },
            { data: "longitude" },
            { data: "latitude" },
            { data: "area" },
            { data: "action" },
        ],
    });
}
