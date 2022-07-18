function reload() {
    location.reload()
}

function playSoundNotification() {
    $.ajax({
        url: "/test-notification",
        method: "GET",
        dataType: "JSON",
        success: function(a) {
            var e = (a.data.mp3, a.data.ogg, '<embed hidden="true" autostart="true" loop="false" src="' + a.data.embed + '">');
            document.getElementById("soundNotification").innerHTML = '<audio autoplay="allowed">' + e + "</audio>"
        }
    })
}

function playSoundHuman(a) {
    $.ajax({
        url: "/test-speech",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        method: "POST",
        dataType: "JSON",
        data: {
            name: a
        },
        success: function(a) {
            var e = "<source src=" + a.data.response + ">";
            document.getElementById("soundWelcome").innerHTML = '<audio autoplay="allowed">' + e + "</audio>"
        }
    })
}

function areaTable() {
    $("#table-area").DataTable({
        processing: !0,
        serverSide: !0,
        order: [
            [0, "desc"]
        ],
        ajax: {
            url: "/area/list",
            dataType: "json",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        },
        drawCallback: function() {
            $("#loadingAreaListTable").hide()
        },
        columns: [{
            data: "no"
        }, {
            data: "name"
        }, {
            data: "longitude"
        }, {
            data: "latitude"
        }, {
            data: "action"
        }]
    })
}

function cameraTable() {
    $.ajax({
        url: "camera/list",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        method: "POST",
        dataType: "JSON",
        success: function(a) {
            $("#loadingCameraListTable").hide();
            var e = a.data.data;
            $("#table-camera").DataTable({
                dom: "Bfrtip",
                buttons: ["pageLength", "excelHtml5", {
                    extend: "pdfHtml5",
                    orientation: "landscape",
                    pageSize: "LEGAL"
                }],
                rowReorder: {
                    selector: "td:nth-child(2)"
                },
                responsive: !0,
                data: e,
                columns: [{
                    data: "no"
                }, {
                    data: "name"
                }, {
                    data: "link_camera"
                }, {
                    data: "prefix_port"
                }, {
                    data: "thumbnail"
                }, {
                    data: "action"
                }],
                columnDefs: [{
                    targets: "_all",
                    className: "dt-body-center"
                }]
            })
        }
    })
}

function startCamera(a) {
    var e = "#startCamera" + a;
    $(e).attr("disabled", !0), $(e).html("Please Wait"), $.ajax({
        url: "{{route('camera.start')}}",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{csrf_token()}}"
        },
        data: {
            id: $id,
            type: "one"
        },
        dataType: "JSON",
        success: function(a) {
            "success" === a.status && location.reload()
        }
    })
}

function stopCameraAll() {
    $("#stop-button").html('<span class="spinner-grow spinner-grow-sm mr-1" role="status" aria-hidden="true"></span> Loading...'), $.ajax({
        url: "/camera/stop/all",
        method: "GET",
        dataType: "JSON",
        success: function(a) {
            location.reload()
        },
        error: function(a) {
            location.reload()
        }
    })
}

function streamCanvas() {
    var a = $("#prefixPort").val(),
        e = $("#streamUrl").val();
    new JSMpeg.Player(e.concat(a), {
        canvas: document.getElementById("camera-preview"),
        poster: "/assets/images/no-signal.jpg",
        disableGl: !0
    })
}

function screenshotCanvas() {
    var a = document.getElementById("camera-preview").toDataURL(),
        e = $("#cameraId").val();
    $.ajax({
        url: "/camera/screenshot",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        data: {
            cameraId: e,
            screenshot: a
        },
        dataType: "JSON",
        success: function(a) {
            "success" === a.status && ($("#screenshot").val(a.data), masking(a.data))
        }
    })
}

function masking(a) {
    function e(a) {
        var e, t, n = document.getElementById("draw-canvas").width,
            o = document.getElementById("draw-canvas").height,
            r = document.getElementById("draw-canvas").getBoundingClientRect().width,
            s = document.getElementById("draw-canvas").getBoundingClientRect().height;
        return void 0 != a.offsetX && void 0 != a.offsetY && (e = a.offsetX * n / r, t = a.offsetY * o / s), [e, t]
    }

    function t(a) {
        var t = document.getElementById("draw-canvas").width,
            o = document.getElementById("draw-canvas").height,
            r = document.getElementById("draw-canvas"),
            l = r.getContext("2d");
        x = e(a)[0] - this.offsetLeft, y = e(a)[1] - this.offsetTop, 1 != d ? d++ : (l.beginPath(), l.moveTo(i[0], i[1]), l.lineTo(x, y), l.strokeStyle = "#ff0000", l.stroke(), d = 0, s.push(i[0] / t + "," + i[1] / o + "," + x / t + "," + y / o), n.push(i[0] + "," + i[1]), n.push(x + "," + y)), i = [x, y]
    }
    var n = new Array,
        o = document.getElementById("draw-canvas"),
        r = o.getContext("2d"),
        s = [];
    r.fillRect(0, 0, o.width, o.height), o.width = 1280, o.height = 720, $("#draw-canvas").css("background", "url(/assets/uploads/" + a + ") center top / cover no-repeat");
    var d = 0,
        i = [0, 0];
    document.getElementById("draw-canvas").addEventListener("click", t, !1), $("#update-line-button").click(function() {
        $("#update-line-button").html('<span class="spinner-grow spinner-grow-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
        var a = $("#cameraId").val();
        $.ajax({
            url: "/camera/update-line",
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            data: {
                cameraId: a,
                coordinate: s
            },
            dataType: "JSON",
            success: function(a) {
                $("#update-line-button").html("Update Line")
            }
        })
    }), $("#update-masking-button").click(function() {
        $("#update-masking-button").html('<span class="spinner-grow spinner-grow-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
        var a = document.getElementById("draw-canvas"),
            e = a.getContext("2d");
        e.beginPath(), e.fillRect(0, 0, a.width, a.height), e.fillStyle = "#000000", e.fill(), e.beginPath(), e.moveTo(161, 122), jQuery.each(n, function(a, t) {
            var n = t.split(",");
            e.lineTo(n[0], n[1])
        }), e.fillStyle = "#ffffff", e.fill(), $("#draw-canvas").css("background-color: #000000");
        var t = document.getElementById("draw-canvas").toDataURL(),
            o = $("#cameraId").val();
        $.ajax({
            url: "/camera/update-mask",
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            data: {
                cameraId: o,
                maskingThumbnail: t
            },
            dataType: "JSON",
            success: function(a) {
                $("#update-masking-button").html("Update Masking")
            }
        })
    })
}

function clusterTable() {
    $("#table-cluster").DataTable({
        processing: !0,
        serverSide: !0,
        order: [
            [0, "desc"]
        ],
        ajax: {
            url: "/cluster/list",
            dataType: "json",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        },
        drawCallback: function() {
            $("#loadingClusterListTable").hide()
        },
        columns: [{
            data: "no"
        }, {
            data: "name"
        }, {
            data: "longitude"
        }, {
            data: "latitude"
        }, {
            data: "area"
        }, {
            data: "action"
        }]
    })
}

function employeeTable() {
    $.ajax({
        url: "employee/list",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        method: "POST",
        dataType: "JSON",
        success: function(a) {
            $("#loadingEmployeeListTable").hide();
            var e = a.data.data;
            $("#table-employee").DataTable({
                dom: "Bfrtip",
                buttons: ["pageLength", "excelHtml5", {
                    extend: "pdfHtml5",
                    orientation: "landscape",
                    pageSize: "LEGAL"
                }],
                rowReorder: {
                    selector: "td:nth-child(2)"
                },
                responsive: !0,
                data: e,
                columns: [{
                    data: "no"
                }, {
                    data: "nik"
                }, {
                    data: "name"
                }, {
                    data: "pob"
                }, {
                    data: "dob"
                }, {
                    data: "gender"
                }, {
                    data: "position"
                }, {
                    data: "department"
                }, {
                    data: "role"
                }, {
                    data: "photo"
                }, {
                    data: "action"
                }],
                columnDefs: [{
                    targets: "_all",
                    className: "dt-body-center"
                }]
            })
        }
    })
}

function occupantTable() {
    $("#table-occupant").DataTable({
        processing: !0,
        serverSide: !0,
        order: [
            [0, "desc"]
        ],
        ajax: {
            url: "occupant/list",
            dataType: "json",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        },
        drawCallback: function() {
            $("#loadingOccupantListTable").hide()
        },
        columns: [{
            data: "no"
        }, {
            data: "name"
        }, {
            data: "phone"
        }, {
            data: "action"
        }]
    })
}

function residentialGateTable() {
    $("#table-residential-gate").DataTable({
        processing: !0,
        serverSide: !0,
        order: [
            [0, "desc"]
        ],
        ajax: {
            url: "/residential-gate/list",
            dataType: "json",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        },
        drawCallback: function() {
            $("#loadingResidentialGateListTable").hide()
        },
        columns: [{
            data: "no"
        }, {
            data: "name"
        }, {
            data: "longitude"
        }, {
            data: "latitude"
        }, {
            data: "cluster"
        }, {
            data: "phone"
        }, {
            data: "action"
        }]
    })
}

function schedulerTable() {
    $.ajax({
        url: "scheduler/list",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        method: "POST",
        dataType: "JSON",
        success: function(a) {
            $("#loadingSchedulerReportListTable").hide();
            var e = a.data.data;
            $("#table-scheduler").DataTable({
                dom: "Bfrtip",
                buttons: ["pageLength", "excelHtml5", {
                    extend: "pdfHtml5",
                    orientation: "landscape",
                    pageSize: "LEGAL"
                }],
                rowReorder: {
                    selector: "td:nth-child(2)"
                },
                responsive: !0,
                data: e,
                columns: [{
                    data: "no"
                }, {
                    data: "name"
                }, {
                    data: "email_to"
                }, {
                    data: "email_cc_1"
                }, {
                    data: "email_cc_2"
                }, {
                    data: "email_cc_3"
                }, {
                    data: "email_cc_4"
                }, {
                    data: "email_cc_5"
                }, {
                    data: "schedule_time"
                }, {
                    data: "range"
                }, {
                    data: "action"
                }],
                columnDefs: [{
                    targets: "_all",
                    className: "dt-body-center"
                }]
            })
        }
    })
}

function userTable() {
    $.ajax({
        url: "user/list",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        method: "POST",
        dataType: "JSON",
        success: function(a) {
            $("#loadingUserListTable").hide();
            var e = a.data.data;
            $("#table-user").DataTable({
                dom: "Bfrtip",
                buttons: ["pageLength", "excelHtml5", {
                    extend: "pdfHtml5",
                    orientation: "landscape",
                    pageSize: "LEGAL"
                }],
                rowReorder: {
                    selector: "td:nth-child(2)"
                },
                responsive: !0,
                data: e,
                columns: [{
                    data: "no"
                }, {
                    data: "name"
                }, {
                    data: "email"
                }, {
                    data: "role"
                }, {
                    data: "action"
                }],
                columnDefs: [{
                    targets: "_all",
                    className: "dt-body-center"
                }]
            })
        }
    })
}

function vehicleTable() {
    $("#table-vehicle").DataTable({
        processing: !0,
        serverSide: !0,
        order: [
            [0, "desc"]
        ],
        ajax: {
            url: "/vehicle/list",
            dataType: "json",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        },
        drawCallback: function() {
            $("#loadingVehicleListTable").hide()
        },
        columns: [{
            data: "no"
        }, {
            data: "license_plate"
        }, {
            data: "car_type"
        }, {
            data: "car_color"
        }, {
            data: "release_status"
        }, {
            data: "time_status"
        }, {
            data: "position_status"
        }, {
            data: "action"
        }]
    })
}

function attendanceTable() {
    $("#table-attendance").DataTable({
        processing: !0,
        serverSide: !0,
        order: [
            [0, "desc"]
        ],
        columnDefs: [{
            targets: 1,
            className: "img-hover-zoom"
        }],
        ajax: {
            url: "attendance/list",
            dataType: "json",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        },
        columns: [{
            data: "no"
        }, {
            data: "photo"
        }, {
            data: "name"
        }, {
            data: "location"
        }, {
            data: "temperature"
        }, {
            data: "date"
        }, {
            data: "first_detected"
        }, {
            data: "last_detected"
        }]
    })
}

function streamCanvasAttendance() {
    var a = $("#countCameras").val();
    for (i = 0; i < a; i++) {
        var e = $("#prefixPort" + (i + 1)).val(),
            t = $("#ipStreamer" + (i + 1)).val();
        new JSMpeg.Player("ws://" + t.concat(":" + e), {
            canvas: document.getElementById("camera-preview-" + (i + 1)),
            poster: "/assets/images/no-signal.jpg",
            disableGl: !0
        })
    }
}

function tallyChart() {
    var a = $("#camera-id").val();
    $.ajax({
        url: "/cctv/tally/" + a,
        method: "GET",
        dataType: "JSON",
        success: function(a) {
            $("#tally-real").empty(), $("#tally-accumulate").empty(), $("#tally-real").append(a.data.totalRealCount + ' <span class="mdi mdi-counter mr-1"></span>'), $("#tally-accumulate").append(a.data.totalAccumulateCount + ' <span class="mdi mdi-counter mr-1"></span>'), $("#loadingTallyChart").hide();
            var e = {
                chart: {
                    height: 350,
                    type: "bar",
                    toolbar: {
                        show: !1
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: !1,
                        columnWidth: "45%",
                        endingShape: "rounded"
                    }
                },
                dataLabels: {
                    enabled: !1
                },
                stroke: {
                    show: !0,
                    width: 2,
                    colors: ["transparent"]
                },
                series: a.data.data,
                colors: ["#f7b331", "#3d8ef8", "#11c46e", "#f836f4", "#f82c27"],
                xaxis: {
                    categories: a.data.categories
                },
                yaxis: {
                    title: {
                        text: "counting"
                    }
                },
                grid: {
                    borderColor: "#f1f1f1"
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(a) {
                            return a
                        }
                    }
                }
            };
            (chart = new ApexCharts(document.querySelector("#tally-chart"), e)).render()
        }
    })
}

function dataFaceRecognize() {
    function a(a) {
        const e = JSON.parse(a.data);
        if ("face" === e.type) {
            playSoundHuman(e.label), n += 1, $("#imgFace").attr("src", e.img), $("#imgFullBody").attr("src", e.img), $("#nik").empty(), $("#nik").append(e.label), $("#pid").val(e.label), $("#namePerson").empty(), $("#namePerson").html(e.label), fr_pid = e.label, fr_prev_pid != fr_pid && ($.ajax({
                url: "/member/detail/",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                method: "POST",
                dataType: "JSON",
                data: {
                    pid: fr_pid
                },
                success: function(a) {
                    a.data && ($("#name").val(a.data.name), $("#namePerson").html(a.data.name), $("#phone").val(a.data.phone), $("#notes").val(a.data.notes), $("#notes").html(a.data.notes), $("#personNotes").html(a.data.notes))
                }
            }), fr_prev_pid = fr_pid), jQuery.each(e.a_attributes, function(a, e) {
                $("#" + e.attr).empty(), $("#" + e.attr).append(e.label)
            });
            var t;
            for (t = 11; t > 1; t--) $("#img" + t).attr("src", $("#img" + (t - 1)).attr("src"));
            $("#img1").attr("src", e.img)
        }
    }
    var e = $("#dataUrl").val(),
        t = $("#dataPort").val();
    client_ws_tally_recognize_driver = new WebSocket(e.concat(t)), client_ws_tally_recognize_driver.onmessage = a;
    var n = 0
}

function impression(a) {
    $.ajax({
        url: "/cctv/impression/" + a,
        method: "GET",
        dataType: "JSON"
    })
}

function allCustomerTable() {
    var a = $("#startDate").val(),
        e = $("#endDate").val();
    $("#customer-table").DataTable().destroy(), $("#customer-table").DataTable({
        dom: "Bfrtip",
        lengthMenu: [
            [10, 25, 50, 100, 500, 1e3, 2e3, 5e3],
            ["10 rows", "25 rows", "50 rows", "100 rows", "500 rows", "1000 rows", "2000 rows", "5000 rows"]
        ],
        buttons: ["pageLength", "excelHtml5", {
            extend: "pdfHtml5",
            orientation: "landscape",
            pageSize: "LEGAL"
        }],
        searching: !1,
        processing: !0,
        serverSide: !0,
        ordering: !1,
        responsive: !0,
        ajax: {
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            url: "/customer/list",
            type: "POST",
            data: {
                type: "filter",
                startDate: a,
                endDate: e
            }
        },
        columns: [{
            data: "no"
        }, {
            data: "photo"
        }, {
            data: "name"
        }, {
            data: "date"
        }, {
            data: "gender"
        }, {
            data: "emotion"
        }, {
            data: "age"
        }, {
            data: "wear_mask"
        }, {
            data: "detected_duration"
        }, {
            data: "detected_gaze_duration"
        }]
    })
}

function visitorRate(a) {
    $.ajax({
        url: "/visitor/rate",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        data: {
            phase: a
        },
        dataType: "JSON",
        success: function(e) {
            $("#total-visitor-current-" + a).empty(), $("#total-visitor-difference-" + a).empty(), $("#total-visitor-current-" + a).append(e.data.current), $("#total-visitor-difference-" + a).append(e.data.difference)
        }
    })
}

function visitorChart() {
    $.ajax({
        url: "/visitor/chart",
        method: "GET",
        dataType: "JSON",
        success: function(a) {
            $("#visitor-this-month").empty(), $("#visitor-last-month").empty(), $("#visitor-this-month").append(a.data[1].total), $("#visitor-last-month").append(a.data[0].total), $("#loadingVisitorChart").hide();
            var e = {
                chart: {
                    height: 346,
                    type: "line",
                    zoom: {
                        enabled: !1
                    },
                    toolbar: {
                        show: !1
                    }
                },
                dataLabels: {
                    enabled: !1
                },
                stroke: {
                    width: 3,
                    curve: "smooth",
                    dashArray: [0, 8]
                },
                series: [{
                    name: a.data[1].title,
                    data: a.data[1].data
                }, {
                    name: a.data[0].title,
                    type: "area",
                    data: a.data[0].data
                }],
                colors: ["#3d8ef8", "#11c46e"],
                fill: {
                    opacity: [1, .15]
                },
                markers: {
                    size: 0,
                    hover: {
                        sizeOffset: 6
                    }
                },
                xaxis: {
                    categories: a.data[0].categories
                },
                grid: {
                    borderColor: "#f1f1f1"
                }
            };
            (chart = new ApexCharts(document.querySelector("#revenue-chart"), e)).render()
        }
    })
}

function streamCanvasDashboard() {
    var a = $("#countCameras").val();
    for (i = 0; i < a; i++) {
        var e = $("#prefixPort" + (i + 1)).val(),
            t = $("#ipStreamer" + (i + 1)).val();
        new JSMpeg.Player("ws://" + t.concat(":" + e), {
            canvas: document.getElementById("camera-preview-" + (i + 1)),
            poster: "/assets/images/no-signal.jpg",
            disableGl: !0
        })
    }
}
$.fn.dataTable.pipeline = function ( opts , a, e) {
    // ajax: {
        // headers: {
        //     "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        // },
        // url: "/gate/history/list",
        // type: "POST",
        // data: {
        //     startDate: a,
        //     endDate: e
        // }
    // },
    // Configuration options
    console.log(a);
    console.log(e);
    var conf = $.extend( {
        pages: 5,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        url: "/gate/history/list",
        type: "POST",
        data: {
            startDate: a,
            endDate: e
        }
    }, opts );

    // Private variables for storing the cache
    var cacheLower = -1;
    var cacheUpper = null;
    var cacheLastRequest = null;
    var cacheLastJson = null;

    return function ( request, drawCallback, settings ) {
        var ajax          = false;
        var requestStart  = request.start;
        var drawStart     = request.start;
        var requestLength = request.length;
        var requestEnd    = requestStart + requestLength;

        if ( settings.clearCache ) {
            // API requested that the cache be cleared
            ajax = true;
            settings.clearCache = false;
        }
        else if ( cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper ) {
            // outside cached data - need to make a request
            ajax = true;
        }
        else if ( JSON.stringify( request.order )   !== JSON.stringify( cacheLastRequest.order ) ||
                  JSON.stringify( request.columns ) !== JSON.stringify( cacheLastRequest.columns ) ||
                  JSON.stringify( request.search )  !== JSON.stringify( cacheLastRequest.search )
        ) {
            // properties changed (ordering, columns, searching)
            ajax = true;
        }

        // Store the request for checking next time around
        cacheLastRequest = $.extend( true, {}, request );

        if ( ajax ) {
            // Need data from the server
            if ( requestStart < cacheLower ) {
                requestStart = requestStart - (requestLength*(conf.pages-1));

                if ( requestStart < 0 ) {
                    requestStart = 0;
                }
            }

            cacheLower = requestStart;
            cacheUpper = requestStart + (requestLength * conf.pages);

            request.start = requestStart;
            request.length = requestLength*conf.pages;

            // Provide the same `data` options as DataTables.
            if ( typeof conf.data === 'function' ) {
                // As a function it is executed with the data object as an arg
                // for manipulation. If an object is returned, it is used as the
                // data object to submit
                var d = conf.data( request );
                if ( d ) {
                    $.extend( request, d );
                }
            }
            else if ( $.isPlainObject( conf.data ) ) {
                // As an object, the data given extends the default
                $.extend( request, conf.data );
            }

            return $.ajax( {
                "type":     conf.method,
                "url":      conf.url,
                "data":     request,
                "dataType": "json",
                "cache":    false,
                "success":  function ( json ) {
                    cacheLastJson = $.extend(true, {}, json);

                    if ( cacheLower != drawStart ) {
                        json.data.splice( 0, drawStart-cacheLower );
                    }
                    if ( requestLength >= -1 ) {
                        json.data.splice( requestLength, json.data.length );
                    }

                    drawCallback( json );
                }
            } );
        }
        else {
            json = $.extend( true, {}, cacheLastJson );
            json.draw = request.draw; // Update the echo for each response
            json.data.splice( 0, requestStart-cacheLower );
            json.data.splice( requestLength, json.data.length );

            drawCallback(json);
        }
    }
};

$.fn.dataTable.Api.register( 'clearPipeline()', function () {
    return this.iterator( 'table', function ( settings ) {
        settings.clearCache = true;
    } );
} );

function newexportactionMethod(e, dt, button, config) {
    var self = this;
    var oldStart = dt.settings()[0]._iDisplayStart;

        dt.one('preXhr', function (e, s, data) {
            //  load all data from the server...


                data.start = 0;
                data.length = dt.page.info().recordsDisplay;
                //data.length = dt.page.info().recordsTotal;
               console.log(dt.page.info());
               console.log(data);
               console.log(dt.ajax);
            //    this.processing(true);
            //    $.fn.dataTable.ext.buttons.excelHtml5.disable();
            //    console.log($.fn.dataTable.ext.buttons.pdfHtml5);
                $(".buttons-pdf").prop('disabled', true);
                $(".buttons-excel").prop('disabled', true);
                // $.fn.dataTable.ext.errMode = 'throw';
               dt.one('preDraw', function (e, settings) {
                    // Call the original action function
                    console.log('preDraw');
                    $(".buttons-pdf").removeAttr("disabled");
                    $(".buttons-excel").removeAttr("disabled");
                    // button[0].className.indexOf('buttons-excel').enable();
                    if (button[0].className.indexOf('buttons-copy') >= 0) {
                        $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                        $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                        $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                        $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-print') >= 0) {
                        $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                    }
                    // document.getElementsByClassName('buttons-excel').disabled = true;
                    dt.one('preXhr', function (e, s, data) {
                        // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                        // Set the property to what it was before exporting.
                        console.log('one');
                        settings._iDisplayStart = oldStart;
                        data.start = oldStart;

                    });
                    // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                    document.getElementsByClassName('buttons-excel').disabled = false;
                    setTimeout(dt.ajax.reload, 0);
                    // Prevent rendering of the full data to the DOM
                    return false;
                });

        });
        // Requery the server with the new one-time export settings
        dt.ajax.reload();


};
function openExport(codeF){
    var a = $("#startDateExport").val(),
    e = $("#endDateExport").val();
    var startDate = returnFormat(new Date(a));
    var endDate = returnFormat(new Date(e));
    window.open(`http://localhost:9001/export-sql?codeFile=${codeF}&startDate=${startDate}&endDate=${endDate}`, '_blank');
}
function returnFormat(dateIN){
    return `${dateIN.getFullYear()}-${dateIN.getMonth()+1}-${dateIN.getDate()}`;
}
function gateHistoryTable() {
    var a = $("#startDate").val(),
        e = $("#endDate").val();
    // var startDate = returnFormat(new Date(a));
    // var endDate = returnFormat(new Date(e));
    // window.open(`http://localhost:9000/export-sql?codeFile=${0}&startDate=${startDate}&endDate=${endDate}`, '_blank');
    // console.log();
    // console.log();
    // $.ajax({
    //     type: "GET",
    //     url: "http://localhost:9000/export-sql",
    //     cache: false,
    //     xhr: function () {
    //                 var xhr = new XMLHttpRequest();
    //                 xhr.onreadystatechange = function () {
    //                     if (xhr.readyState == 2) {
    //                         if (xhr.status == 200) {
    //                             xhr.responseType = "blob";
    //                         } else {
    //                             xhr.responseType = "text";
    //                         }
    //                     }
    //                 };
    //                 return xhr;
    //             },
    //     data: {
    //         codeFile: 0,
    //         startDate: startDate,
    //         endDate: endDate
    //       },
    //     success: function(data){
    //             var blob = new Blob([data], { type: "application/octetstream" });

    //                 //Check the Browser type and download the File.
    //                 var isIE = false || !!document.documentMode;
    //                 if (isIE) {
    //                     window.navigator.msSaveBlob(blob, fileName);
    //                 } else {
    //                     var url = window.URL || window.webkitURL;
    //                     link = url.createObjectURL(blob);
    //                     var a = $("<a />");
    //                     a.attr("download", fileName);
    //                     a.attr("href", link);
    //                     $("body").append(a);
    //                     a[0].click();
    //                     $("body").remove(a);
    //                 }
    //     },


    //     });

    $("#gate-history-table").DataTable().destroy(),
    $("#gate-history-table").DataTable({

        dom: "Tlfrtip",
        lengthMenu:

            [[10,25, 50,100], [10, 25, "50","100"]]
        ,
        // buttons: [
        //         {
        //             "extend": 'excel',
        //             "titleAttr": 'Excel',
        //             enabled: true ,
        //             "action": newexportactionMethod
        //         },
        //         {
        //             "extend": 'pdf',
        //             "titleAttr": 'PDF',
        //             enabled: true ,
        //             "action": newexportactionMethod
        //         }
        //     ],
        processing: true,
        serverSide: true,
        bPaginate: true,
        sPaginationType: "full_numbers",
        order: [
            [0, "desc"]
        ],
        // ajax:
        //     $.fn.dataTable.pipeline( {

        //     },a,e )
        // ,
        ajax: {
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            url: "/gate/history/list",
            type: "POST",
            data: {
                startDate: a,
                endDate: e
            }
        },
        language: {
            search: '<i class="fa fa-search"></i>',
            searchPlaceholder: "Cari Plat Nomor",
        },
        drawCallback: function() {
            $("#loadingGateHistoryListTable").hide()
        },

        columnDefs: [
            { orderable: false, targets: [0,1,2,3,4,5,6,7] },
        ],
        columns: [{
            data: "no"
        }, {
            data: "image"
        }, {
            data: "license_plate"
        }, {
            data: "area"
        }, {
            data: "cluster"
        }, {
            data: "residentialGate"
        }, {
            data: "status"
        }, {
            data: "timestamp"
        }]
    })
}

function memberTable() {
    $.ajax({
        url: "/member/list",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        method: "POST",
        dataType: "JSON",
        success: function(a) {
            $("#loadingMemberListTable").hide();
            var e = a.data.data;
            $("#table-camera").DataTable({
                rowReorder: {
                    selector: "td:nth-child(2)"
                },
                responsive: !0,
                data: e,
                columns: [{
                    data: "no"
                }, {
                    data: "name"
                }, {
                    data: "nik"
                }, {
                    data: "address"
                }, {
                    data: "photo"
                }, {
                    data: "phone"
                }, {
                    data: "email"
                }, {
                    data: "action"
                }],
                columnDefs: [{
                    targets: "_all",
                    className: "dt-body-center"
                }]
            })
        }
    })
}

function dataCapture() {
    function a(a) {
        const e = JSON.parse(a.data);
        "face" === e.type && (1 == n && o && ($("#capture1").attr("src", e.img), $("#capture1File").val($("#capture1").attr("src")), o = !1, n = 2), 2 == n && o && ($("#capture2").attr("src", e.img), $("#capture2File").val($("#capture2").attr("src")), o = !1, n = 3), 3 == n && o && ($("#capture3").attr("src", e.img), $("#capture3File").val($("#capture3").attr("src")), o = !1, n = 4), 4 == n && o && ($("#capture4").attr("src", e.img), $("#capture4File").val($("#capture4").attr("src")), o = !1, n = 99))
    }
    var e = $("#dataUrl").val(),
        t = $("#dataPort").val();
    new WebSocket(e.concat(t)).onmessage = a;
    var n = 1,
        o = !1;
    setInterval(function() {
        o = !0
    }, 1e3)
}

function updatePhotoMember() {
    $("#updatePhotoButton").html('<span class="spinner-grow spinner-grow-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
    var a = $("#member-id").val(),
        e = $("#capture1File").val(),
        t = $("#capture2File").val(),
        n = $("#capture3File").val(),
        o = $("#capture4File").val();
    $.ajax({
        url: "/member/update",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        method: "POST",
        dataType: "JSON",
        data: {
            memberId: a,
            capture1: e,
            capture2: t,
            capture3: n,
            capture4: o,
            type: "photo"
        },
        success: function(a) {
            window.location.href = "/member"
        },
        error: function(a) {
            location.reload()
        }
    })
}

function notificationTable() {
    $.ajax({
        url: "/notification/list",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        method: "POST",
        dataType: "JSON",
        success: function(a) {
            $("#loadingNotificationListTable").hide();
            var e = a.data.data;
            $("#table-notification").DataTable({
                responsive: !0,
                data: e,
                columns: [{
                    data: "no"
                }, {
                    data: "action"
                }, {
                    data: "message"
                }, {
                    data: "location"
                }, {
                    data: "camera_status"
                }, {
                    data: "timestamp"
                }]
            })
        }
    })
}

function allReportTable() {
    var a = $("#filterDate").val();
    $("#report-table").DataTable().destroy(), $("#report-table").DataTable({
        dom: "Bfrtip",
        lengthMenu: [
            [10, 25, 50, 100, 500, 1e3, 2e3, 5e3],
            ["10 rows", "25 rows", "50 rows", "100 rows", "500 rows", "1000 rows", "2000 rows", "5000 rows"]
        ],
        buttons: ["pageLength", "excelHtml5", {
            extend: "pdfHtml5",
            orientation: "landscape",
            pageSize: "LEGAL"
        }],
        searching: !0,
        ordering: !0,
        responsive: !0,
        ajax: {
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            url: "/report/list",
            type: "POST",
            data: {
                filterDate: a
            }
        },
        columns: [{
            data: "no"
        }, {
            data: "date"
        }, {
            data: "time"
        }, {
            data: "view"
        }, {
            data: "onsite"
        }, {
            data: "male"
        }, {
            data: "female"
        }, {
            data: "average_age"
        }, {
            data: "average_male_age"
        }, {
            data: "average_female_age"
        }]
    })
}

function allReportDownloadTable() {
    $("#report-download-table").DataTable().destroy(), $("#report-download-table").DataTable({
        dom: "Bfrtip",
        lengthMenu: [
            [10, 25, 50, 100, 500, 1e3, 2e3, 5e3],
            ["10 rows", "25 rows", "50 rows", "100 rows", "500 rows", "1000 rows", "2000 rows", "5000 rows"]
        ],
        buttons: ["pageLength", "excelHtml5", {
            extend: "pdfHtml5",
            orientation: "landscape",
            pageSize: "LEGAL"
        }],
        searching: !0,
        ordering: !0,
        responsive: !0,
        ajax: {
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            url: "/report/download-list",
            type: "POST"
        },
        columns: [{
            data: "no"
        }, {
            data: "name"
        }, {
            data: "created_at"
        }, {
            data: "action"
        }]
    })
}

function downloadReport(a) {
    $("#download-type-modal").modal("toggle"), $("#downloadReport").replaceWith('<button id="downloadReport" class="btn btn-info" type="button"><span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span>Loading...</button>');
    var e = $("#startDate").val(),
        t = $("#endDate").val();
    $.ajax({
        url: "/download-report/",
        method: "POST",
        dataType: "JSON",
        data: {
            startDate: e,
            endDate: t,
            type: a
        },
        success: function(a) {}
    })
}

function visitorTable() {
    $("#table-visitor").DataTable({
        processing: !0,
        serverSide: !0,
        order: [
            [0, "desc"]
        ],
        ajax: {
            url: "visitor/list",
            dataType: "json",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        },
        drawCallback: function() {
            $("#loadingVisitorListTable").hide()
        },
        columns: [{
            data: "no"
        }, {
            data: "name"
        }, {
            data: "license_plate"
        }, {
            data: "qrcode_image_path"
        }, {
            data: "qrcode_expiry_date"
        }, {
            data: "additional_information"
        }, {
            data: "position_status"
        }, {
            data: "action"
        }]
    })
}

function prepareSympleClient(a) {
    function e() {
        o.play(), o.engine.sendLocalSDP = function(a) {
            console.log("Send offer:", JSON.stringify(a)), n.send({
                to: r,
                type: "message",
                offer: a
            })
        }, o.engine.sendLocalCandidate = function(a) {
            n.send({
                to: r,
                type: "message",
                candidate: a
            })
        }
    }
    var t = a.peer.user.substr(6);
    if (!(t.length <= 0)) {
        var n, o, r, s = !1;
        WEBRTC_CONFIG = {
            iceServers: [{
                url: "turn:numb.viagenie.ca:3478",
                username: "yuri@alfabeta.co.id",
                credential: "alfabeta123"
            }]
        }, $(".video-player").hide(), o = new Symple.Player({
            element: "#webrtc-video .video-player",
            engine: "WebRTC",
            initiator: !0,
            rtcConfig: WEBRTC_CONFIG,
            iceMediaConstraints: {
                mandatory: {
                    OfferToReceiveAudio: !1,
                    OfferToReceiveVideo: !0
                }
            },
            onStateChange: function(a, e) {
                a.displayStatus(e)
            }
        }), n = new Symple.Client(a), n.on("announce", function(a) {
            console.log("Authentication success:", a)
        }), n.on("addPeer", function(a) {
            console.log("Adding peer:", a), a.user != "abdetection" + t || s || (s = !0, r = a, e())
        }), n.on("removePeer", function(a) {
            console.log("Removing peer:", a)
        }), n.on("message", function(a) {
            if (r && r.id != a.from.id) return void console.log("Dropping message from unknown peer", a);
            a.offer ? alert("Unexpected offer for one-way streaming") : a.answer ? (console.log("Reieve answer:", JSON.stringify(a.answer)), o.engine.recvRemoteSDP(a.answer)) : a.candidate && o.engine.recvRemoteCandidate(a.candidate)
        }), n.on("disconnect", function() {
            console.log("Disconnected from server")
        }), n.on("error", function(a, e) {
            console.log("Peer error:", a, e)
        }), n.connect()
    }
}
$(".slider-for").slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: !1,
    autoplay: !0,
    asNavFor: ".slider-nav"
}), $(".slider-nav").slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    asNavFor: ".slider-for",
    arrows: !1,
    dots: !1,
    centerMode: !0,
    focusOnSelect: !0,
    responsive: [{
        breakpoint: 1680,
        settings: {
            slidesToShow: 2,
            slidesToScroll: 2
        }
    }, {
        breakpoint: 1440,
        settings: {
            slidesToShow: 1,
            slidesToScroll: 1
        }
    }, {
        breakpoint: 1200,
        settings: {
            slidesToShow: 3,
            slidesToScroll: 3
        }
    }, {
        breakpoint: 600,
        settings: {
            slidesToShow: 2,
            slidesToScroll: 2
        }
    }, {
        breakpoint: 480,
        settings: {
            slidesToShow: 1,
            slidesToScroll: 1
        }
    }]
}), options = {
    chart: {
        height: 250,
        type: "radialBar",
        offsetY: -20
    },
    plotOptions: {
        radialBar: {
            startAngle: -135,
            endAngle: 135,
            hollow: {
                size: "72%"
            },
            dataLabels: {
                name: {
                    offsetY: -15
                },
                value: {
                    offsetY: 12,
                    fontSize: "18px",
                    color: void 0,
                    formatter: function(a) {
                        return a + "%"
                    }
                }
            }
        }
    },
    colors: ["#3d8ef8"],
    fill: {
        type: "gradient",
        gradient: {
            shade: "dark",
            shadeIntensity: .15,
            inverseColors: !1,
            opacityFrom: 1,
            opacityTo: 1,
            stops: [0, 50, 65, 91]
        }
    },
    series: [67],
    labels: ["Morning"]
}, (chart = new ApexCharts(document.querySelector("#visitor-morning-chart"), options)).render(), options = {
    chart: {
        height: 250,
        type: "radialBar",
        offsetY: -20
    },
    plotOptions: {
        radialBar: {
            startAngle: -135,
            endAngle: 135,
            hollow: {
                size: "72%"
            },
            dataLabels: {
                name: {
                    offsetY: -15
                },
                value: {
                    offsetY: 12,
                    fontSize: "18px",
                    color: void 0,
                    formatter: function(a) {
                        return a + "%"
                    }
                }
            }
        }
    },
    colors: ["#c4c034"],
    fill: {
        type: "gradient",
        gradient: {
            shade: "dark",
            shadeIntensity: .15,
            inverseColors: !1,
            opacityFrom: 1,
            opacityTo: 1,
            stops: [0, 50, 65, 91]
        }
    },
    series: [72],
    labels: ["Daylight"]
}, (chart = new ApexCharts(document.querySelector("#visitor-daylight-chart"), options)).render(), options = {
    chart: {
        height: 250,
        type: "radialBar",
        offsetY: -20
    },
    plotOptions: {
        radialBar: {
            startAngle: -135,
            endAngle: 135,
            hollow: {
                size: "72%"
            },
            dataLabels: {
                name: {
                    offsetY: -15
                },
                value: {
                    offsetY: 12,
                    fontSize: "18px",
                    color: void 0,
                    formatter: function(a) {
                        return a + "%"
                    }
                }
            }
        }
    },
    colors: ["#f19139"],
    fill: {
        type: "gradient",
        gradient: {
            shade: "dark",
            shadeIntensity: .15,
            inverseColors: !1,
            opacityFrom: 1,
            opacityTo: 1,
            stops: [0, 50, 65, 91]
        }
    },
    series: [83],
    labels: ["Evening"]
}, (chart = new ApexCharts(document.querySelector("#visitor-evening-chart"), options)).render(), options = {
    chart: {
        height: 250,
        type: "radialBar",
        offsetY: -20
    },
    plotOptions: {
        radialBar: {
            startAngle: -135,
            endAngle: 135,
            hollow: {
                size: "72%"
            },
            dataLabels: {
                name: {
                    offsetY: -15
                },
                value: {
                    offsetY: 12,
                    fontSize: "18px",
                    color: void 0,
                    formatter: function(a) {
                        return a + "%"
                    }
                }
            }
        }
    },
    colors: ["#121728"],
    fill: {
        type: "gradient",
        gradient: {
            shade: "dark",
            shadeIntensity: .15,
            inverseColors: !1,
            opacityFrom: 1,
            opacityTo: 1,
            stops: [0, 50, 65, 91]
        }
    },
    series: [95],
    labels: ["Night"]
}, (chart = new ApexCharts(document.querySelector("#visitor-night-chart"), options)).render();
var client_ws_tally_recognize_driver, fr_prev_pid = "",
    fr_pid = "";
$("#formAddMemberWithIdCard").on("submit", function(a) {
    var e = new FormData(this);
    $("#submit").html("Please Wait"), a.preventDefault(), $.ajax({
        url: "/member/store",
        method: "POST",
        data: e,
        dataType: "JSON",
        contentType: !1,
        cache: !1,
        processData: !1,
        success: function(a) {
            $("#submit").removeAttr("disabled"), $("#submit").html("Send File"), $("#name").html(a.data.name), $("#id-card-number").html(a.data.nik), $("#pob").html(a.data.pob), $("#dob").html(a.data.dob)
        }
    })
});

function vehicleCountingHistoryTable() {
    var a = $("#startDate").val(),
        e = $("#endDate").val();
    $("#vehicleCounting-history-table").DataTable().destroy(), $("#vehicleCounting-history-table").DataTable({
        dom: "Bfrtip",
        lengthMenu: [
            [10, 25, 50, 100, 500, 1e3, 2e3, 5e3],
            ["10 rows", "25 rows", "50 rows", "100 rows", "500 rows", "1000 rows", "2000 rows", "5000 rows"]
        ],
        buttons: ["pageLength", "excelHtml5", {
            extend: "pdfHtml5",
            orientation: "landscape",
            pageSize: "LEGAL"
        }],
        processing: false,
        serverSide: !0,
        order: [
            [0, "desc"]
        ],
        ajax: {
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            url: "/vehicle_counting/history/list",
            type: "POST",
            data: {
                startDate: a,
                endDate: e
            }
        },
        drawCallback: function() {
            $("#loadingVehicleCountingHistoryListTable").hide()
        },
        columns: [{
            data: "no"
        }, {
            data: "image"
        }, {
            data: "license_plate"
        }, {
            data: "area"
        }, {
            data: "cluster"
        }, {
            data: "residentialGate"
        }, {
            data: "status"
        }, {
            data: "timestamp"
        }]
    })
}

function peopleCountingHistoryTable() {
    var a = $("#startDate").val(),
        e = $("#endDate").val();
    $("#people-counting-table").DataTable().destroy(), $("#people-counting-table").DataTable({
        dom: "Bfrtip",
        lengthMenu: [
            [10, 25, 50, 100, 500, 1e3, 2e3, 5e3],
            ["10 rows", "25 rows", "50 rows", "100 rows", "500 rows", "1000 rows", "2000 rows", "5000 rows"]
        ],
        buttons: ["pageLength", "excelHtml5", {
            extend: "pdfHtml5",
            orientation: "landscape",
            pageSize: "LEGAL"
        }],
        processing: false,
        serverSide: !0,
        order: [
            [0, "desc"]
        ],
        ajax: {
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            url: "/people_counting/history/list",
            type: "POST",
            data: {
                startDate: a,
                endDate: e
            }
        },
        drawCallback: function() {
            $("#loadingPeopleCountingHistoryListTable").hide()
        },
        columns: [{
            data: "no"
        }, {
            data: "image"
        }, {
            data: "license_plate"
        }, {
            data: "area"
        }, {
            data: "cluster"
        }, {
            data: "residentialGate"
        }, {
            data: "status"
        }, {
            data: "timestamp"
        }]
    })
}

function intruderCountingHistoryTable() {
    var a = $("#startDate").val(),
        e = $("#endDate").val();
    $("#intruder-counting-table").DataTable().destroy(), $("#intruder-counting-table").DataTable({
        dom: "Bfrtip",
        lengthMenu: [
            [10, 25, 50, 100, 500, 1e3, 2e3, 5e3],
            ["10 rows", "25 rows", "50 rows", "100 rows", "500 rows", "1000 rows", "2000 rows", "5000 rows"]
        ],
        buttons: ["pageLength", "excelHtml5", {
            extend: "pdfHtml5",
            orientation: "landscape",
            pageSize: "LEGAL"
        }],
        processing: false,
        serverSide: !0,
        order: [
            [0, "desc"]
        ],
        ajax: {
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            url: "/intruder_counting/history/list",
            type: "POST",
            data: {
                startDate: a,
                endDate: e
            }
        },
        drawCallback: function() {
            $("#loadingIntruderHistoryListTable").hide()
        },
        columns: [{
            data: "no"
        }, {
            data: "image"
        }, {
            data: "license_plate"
        }, {
            data: "area"
        }, {
            data: "cluster"
        }, {
            data: "residentialGate"
        }, {
            data: "status"
        }, {
            data: "timestamp"
        }]
    })
}

function speedHistoryTable() {
    var a = $("#startDate").val(),
        e = $("#endDate").val();
    $("#people-counting-table").DataTable().destroy(), $("#people-counting-table").DataTable({
        dom: "Bfrtip",
        lengthMenu: [
            [10, 25, 50, 100, 500, 1e3, 2e3, 5e3],
            ["10 rows", "25 rows", "50 rows", "100 rows", "500 rows", "1000 rows", "2000 rows", "5000 rows"]
        ],
        buttons: ["pageLength", "excelHtml5", {
            extend: "pdfHtml5",
            orientation: "landscape",
            pageSize: "LEGAL"
        }],

        processing: true,
        serverSide: true,
        order: [
            [0, "desc"]
        ],
        ajax: {
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            url: "/speed_history/history/list",
            type: "POST",
            data: {
                startDate: a,
                endDate: e
            }
        },
        drawCallback: function() {
            $("#loadingSpeedHistoryTable").hide()
        },
        columns: [{
            data: "no"
        }, {
            data: "image"
        }, {
            data: "plate_number"
        }, {
            data: "speed"
        }, {
            data: "timestamp"
        }]
    })
}
