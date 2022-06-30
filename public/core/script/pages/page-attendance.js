function attendanceTable() {
    $('#table-attendance').DataTable({
        processing: true,
        serverSide: true,
        order: [[ 0, "desc" ]],
        columnDefs: [
            {
                targets: 1,
                className: 'img-hover-zoom'
            }
        ],
        ajax: {
            "url": "attendance/list",
            "dataType": "json",
            "type": "POST",
            "headers": {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        },
        "columns": [
            {"data": "no"},
            {"data": "photo"},
            {"data": "name"},
            {"data": "location"},
            {"data": "temperature"},
            {"data": "date"},
            {"data": "first_detected"},
            {"data": "last_detected"},
        ]
    });
}

function streamCanvasAttendance() {
    var countCameras = $('#countCameras').val();

    for (i = 0; i < countCameras; i++) {
        var prefixPort = $('#prefixPort' + (i + 1)).val();
        var ipStreamer = $('#ipStreamer' + (i + 1)).val();

        new JSMpeg.Player('ws://' + ipStreamer.concat(':' + prefixPort), {
            canvas: document.getElementById("camera-preview-" + (i + 1)),
            poster: "/assets/images/no-signal.jpg",
            disableGl: true
        });
    }
}
