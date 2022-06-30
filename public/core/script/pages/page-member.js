function memberTable() {
    $.ajax({
        url: "/member/list",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: "POST",
        dataType: "JSON",
        success: function (data) {
            $('#loadingMemberListTable').hide();
            var tableData = data.data.data;
            $('#table-camera').DataTable({
                "rowReorder": {
                    "selector": 'td:nth-child(2)'
                },
                "responsive": true,
                "data": tableData,
                "columns": [
                    {"data": "no"},
                    {"data": "name"},
                    {"data": "nik"},
                    {"data": "address"},
                    {"data": "photo"},
                    {"data": "phone"},
                    {"data": "email"},
                    {"data": "action"}
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

function dataCapture() {
    var dataUrl = $('#dataUrl').val();
    var dataPort = $('#dataPort').val();

    var client_ws_tally_capture_driver = new WebSocket(dataUrl.concat(dataPort));

    client_ws_tally_capture_driver.onmessage = onmessageCapture;

    var number = 1; // set default value
    var check_face_crop = false;
    var interval = setInterval(function () {
        check_face_crop = true;
    }, 1000);

    function onmessageCapture(event) {

        const messageObject = JSON.parse(event.data);
        // console.log(messageObject);

        if (messageObject.type === "face") {
            if (number == 1 && check_face_crop) {
                $('#capture1').attr('src', messageObject.img);
                $('#capture1File').val($('#capture1').attr('src'));
                check_face_crop = false;
                number = 2;
            }

            if (number == 2 && check_face_crop) {
                $('#capture2').attr('src', messageObject.img);
                $('#capture2File').val($('#capture2').attr('src'));
                check_face_crop = false;
                number = 3;
            }

            if (number == 3 && check_face_crop) {
                $('#capture3').attr('src', messageObject.img);
                $('#capture3File').val($('#capture3').attr('src'));
                check_face_crop = false;
                number = 4;
            }

            if (number == 4 && check_face_crop) {
                $('#capture4').attr('src', messageObject.img);
                $('#capture4File').val($('#capture4').attr('src'));
                check_face_crop = false;
                number = 99;
            }
        }

    }
}

function updatePhotoMember() {
    $("#updatePhotoButton").html('<span class="spinner-grow spinner-grow-sm mr-1" role="status" aria-hidden="true"></span> Loading...');

    var memberId = $('#member-id').val();
    var capture1 = $('#capture1File').val();
    var capture2 = $('#capture2File').val();
    var capture3 = $('#capture3File').val();
    var capture4 = $('#capture4File').val();

    $.ajax({
        url: "/member/update",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: "POST",
        dataType: "JSON",
        data: {
            'memberId': memberId,
            'capture1': capture1,
            'capture2': capture2,
            'capture3': capture3,
            'capture4': capture4,
            'type': 'photo'
        },
        success:
            function (data) {
                window.location.href = "/member"
            },
        error:
            function (data) {
                location.reload();
            }
    })
}

$('#formAddMemberWithIdCard').on('submit', function (event) {
    var formData = new FormData(this);

    $("#submit").html("Please Wait");

    event.preventDefault();
    $.ajax({
        url: "/member/store",
        method: "POST",
        data: formData,
        dataType: 'JSON',
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {

            $('#submit').removeAttr("disabled");
            $("#submit").html("Send File");

            $('#name').html(data.data.name);
            $('#id-card-number').html(data.data.nik);
            $('#pob').html(data.data.pob);
            $('#dob').html(data.data.dob);
        }
    })
});
