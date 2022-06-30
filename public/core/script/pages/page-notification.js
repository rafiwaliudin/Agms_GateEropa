function notificationTable() {
    $.ajax({
        url: "/notification/list",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        method: "POST",
        dataType: "JSON",
        success: function (data) {
            $("#loadingNotificationListTable").hide();
            var tableData = data.data.data;
            $("#table-notification").DataTable({
                responsive: true,
                data: tableData,
                columns: [
                    { data: "no" },
                    { data: "action" },
                    { data: "message" },
                    { data: "location" },
                    { data: "camera_status" },
                    { data: "timestamp" },
                ],
            });
        },
    });
}
