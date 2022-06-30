function occupantTable() {
    $('#table-occupant').DataTable({
        processing: true,
        serverSide: true,
        order: [[ 0, "desc" ]],
        ajax: {
            "url": "occupant/list",
            "dataType": "json",
            "type": "POST",
            "headers": {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        },
        "drawCallback": function() {
            $('#loadingOccupantListTable').hide();
        },
        "columns": [
            {"data": "no"},
            {"data": "name"},
            {"data": "phone"},
            {"data": "action"},
        ]
    });
}
