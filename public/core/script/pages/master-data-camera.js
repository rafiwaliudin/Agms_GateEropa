function cameraTable() {
    $.ajax({
        url: "camera/list",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: "POST",
        dataType: "JSON",
        success: function (data) {
            $('#loadingCameraListTable').hide();
            var tableData = data.data.data;
            $('#table-camera').DataTable({
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
                    {"data": "link_camera"},
                    {"data": "prefix_port"},
                    {"data": "thumbnail"},
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

function startCamera(idCamera) {
    var idButton = '#startCamera' + idCamera;
    $(idButton).attr("disabled", true);
    $(idButton).html("Please Wait");

    $.ajax({
        url: "{{route('camera.start')}}",
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': '{{csrf_token()}}'
        },
        data: {
            'id': $id,
            'type': 'one',
        },
        dataType: 'JSON',
        success:
            function (data) {
                if (data.status === 'success') {
                    location.reload();
                }
            }
    })
}

function stopCameraAll() {
    $("#stop-button").html('<span class="spinner-grow spinner-grow-sm mr-1" role="status" aria-hidden="true"></span> Loading...');

    $.ajax({
        url: "/camera/stop/all",
        method: "GET",
        dataType: 'JSON',
        success:
            function (data) {
                location.reload();
            },
        error:
            function (data) {
                location.reload();
            }
    })
}

function streamCanvas() {
    var prefixPort = $('#prefixPort').val();
    var streamUrl = $('#streamUrl').val();

    var jsmpeg_ws_eagle_eye = new JSMpeg.Player(streamUrl.concat(prefixPort), {
        canvas: document.getElementById("camera-preview"),
        poster: "/assets/images/no-signal.jpg",
        disableGl: true
    });

}

function screenshotCanvas() {
    var screenshot = document.getElementById('camera-preview').toDataURL();
    var cameraId = $('#cameraId').val();
    $.ajax({
        url: "/camera/screenshot",
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            'cameraId': cameraId,
            'screenshot': screenshot
        },
        dataType: 'JSON',
        success:
            function (data) {
                if (data.status === 'success') {
                    $('#screenshot').val(data.data);
                    masking(data.data);
                }
            }
    });
}

function masking(screenshot) {
    var coordinatePoint = new Array();

    // background canvas
    var canvas = document.getElementById("draw-canvas");
    var context = canvas.getContext("2d");
    var coordinate = [];
    context.fillRect(0, 0, canvas.width, canvas.height);

    canvas.width = 1280;
    canvas.height = 720;

    $('#draw-canvas').css("background", "url(" + "/assets/uploads/" + screenshot + ") center top / cover no-repeat");

    var clicks = 0;
    var lastClick = [0, 0];

    document.getElementById('draw-canvas').addEventListener('click', drawLine, false);

    function getCursorPosition(e) {
        var x;
        var y;

        var w = document.getElementById('draw-canvas').width;
        var h = document.getElementById('draw-canvas').height;
        var wd = document.getElementById('draw-canvas').getBoundingClientRect().width;
        var hd = document.getElementById('draw-canvas').getBoundingClientRect().height;

        if (e.offsetX != undefined && e.offsetY != undefined) {
            x = e.offsetX * w / wd;
            y = e.offsetY * h / hd;
        }

        return [x, y];
    }

    function drawLine(e) {
        var w = document.getElementById('draw-canvas').width;
        var h = document.getElementById('draw-canvas').height;
        var canvas = document.getElementById("draw-canvas");
        var context = canvas.getContext("2d");

        x = (getCursorPosition(e)[0] - this.offsetLeft);
        y = (getCursorPosition(e)[1] - this.offsetTop);

        if (clicks != 1) {
            clicks++;
        } else {
            context.beginPath();
            context.moveTo(lastClick[0], lastClick[1]);
            context.lineTo(x, y);

            context.strokeStyle = '#ff0000';
            context.stroke();

            clicks = 0;

            coordinate.push((lastClick[0] / w + "," + lastClick[1] / h + "," + x / w + "," + y / h));
            coordinatePoint.push(lastClick[0] + "," + lastClick[1]);
            coordinatePoint.push(x + "," + y);
        }
        lastClick = [x, y];
    };

    $("#update-line-button").click(function () {
        $("#update-line-button").html('<span class="spinner-grow spinner-grow-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
        var cameraId = $('#cameraId').val();

        $.ajax({
            url: "/camera/update-line",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                'cameraId': cameraId,
                'coordinate': coordinate
            },
            dataType: 'JSON',
            success:
                function (data) {
                    $("#update-line-button").html('Update Line');
                }
        });
    });

    $("#update-masking-button").click(function () {
        $("#update-masking-button").html('<span class="spinner-grow spinner-grow-sm mr-1" role="status" aria-hidden="true"></span> Loading...');

        var canvas = document.getElementById("draw-canvas");
        var ctx = canvas.getContext('2d');

        ctx.beginPath();
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = '#000000';
        ctx.fill();

        ctx.beginPath();
        ctx.moveTo(161, 122);
        jQuery.each(coordinatePoint, function (index, item) {
            var dotPoint = item.split(",");
            ctx.lineTo(dotPoint[0], dotPoint[1]);
        });
        ctx.fillStyle = '#ffffff';
        ctx.fill();

        $('#draw-canvas').css("background-color: #000000");
        var maskingThumbnail = document.getElementById('draw-canvas').toDataURL();
        var cameraId = $('#cameraId').val();

        $.ajax({
            url: "/camera/update-mask",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                'cameraId': cameraId,
                'maskingThumbnail': maskingThumbnail
            },
            dataType: 'JSON',
            success:
                function (data) {
                    $("#update-masking-button").html('Update Masking');
                }
        });
    });
}
