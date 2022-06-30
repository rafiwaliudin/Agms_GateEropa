function tallyChart() {
    var cameraId = $('#camera-id').val();
    $.ajax({
        url: "/cctv/tally/" + cameraId,
        method: "GET",
        dataType: 'JSON',
        success:
            function (data) {
                $('#tally-real').empty();
                $('#tally-accumulate').empty();
                $('#tally-real').append(data.data.totalRealCount + ' <span class="mdi mdi-counter mr-1"></span>');
                $('#tally-accumulate').append(data.data.totalAccumulateCount + ' <span class="mdi mdi-counter mr-1"></span>');
                $('#loadingTallyChart').hide();
                var options = {
                    chart: {height: 350, type: "bar", toolbar: {show: !1}},
                    plotOptions: {bar: {horizontal: !1, columnWidth: "45%", endingShape: "rounded"}},
                    dataLabels: {enabled: !1},
                    stroke: {show: !0, width: 2, colors: ["transparent"]},
                    series: data.data.data,
                    colors: ["#f7b331", "#3d8ef8", "#11c46e", "#f836f4", "#f82c27"],
                    xaxis: {categories: data.data.categories},
                    yaxis: {title: {text: "counting"}},
                    grid: {borderColor: "#f1f1f1"},
                    fill: {opacity: 1},
                    tooltip: {
                        y: {
                            formatter: function (e) {
                                return e
                            }
                        }
                    }
                };
                (chart = new ApexCharts(document.querySelector("#tally-chart"), options)).render()
            }
    });
}

var client_ws_tally_recognize_driver;
var fr_prev_pid = "";
var fr_pid = "";

function dataFaceRecognize() {
    var dataUrl = $('#dataUrl').val();
    var dataPort = $('#dataPort').val();

    client_ws_tally_recognize_driver = new WebSocket(dataUrl.concat(dataPort));

    client_ws_tally_recognize_driver.onmessage = onmessageRecog;

    var number = 0; // set default value

    function onmessageRecog(event) {

        const messageObject = JSON.parse(event.data);

        if (messageObject.type === "face") {
            playSoundHuman(messageObject.label);
            number = number + 1;
            $('#imgFace').attr('src', messageObject.img);
            $('#imgFullBody').attr('src', messageObject.img);

            $('#nik').empty();
            $('#nik').append(messageObject.label);

            // #pid & #name
            $('#pid').val(messageObject.label);
            $('#namePerson').empty();
            $('#namePerson').html(messageObject.label);

            // Find person with ID (nik/phone/UNKNOWN..) if different person id-ed
            // Get data member if exist
            fr_pid = messageObject.label;
            if (fr_prev_pid != fr_pid) {
                $.ajax({
                    url: "/member/detail/",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "POST",
                    dataType: 'JSON',
                    data: {
                        pid: fr_pid,
                    },
                    success:
                        function (data) {
                            if (data.data) {
                                $('#name').val(data.data.name);
                                $('#namePerson').html(data.data.name);
                                $('#phone').val(data.data.phone);
                                $('#notes').val(data.data.notes);
                                $('#notes').html(data.data.notes);
                                $('#personNotes').html(data.data.notes);
                            }
                        }
                });
                fr_prev_pid = fr_pid;
            }

            jQuery.each(messageObject.a_attributes, function (i, val) {
                $("#" + val.attr).empty();
                $("#" + val.attr).append(val.label);
            });
            var i;
            for (i = 11; i > 1; i--) {
                $('#img' + i).attr("src", $('#img' + (i - 1)).attr('src'));
            }

            $('#img1').attr('src', messageObject.img);
        }
    }
}

function impression(cameraId) {
    $.ajax({
        url: "/cctv/impression/" + cameraId,
        method: "GET",
        dataType: 'JSON',
    });
}
