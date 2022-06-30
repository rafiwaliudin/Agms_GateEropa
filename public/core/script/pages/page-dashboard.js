function visitorRate(phase) {
    $.ajax({
        url: "/visitor/rate",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            phase: phase,
        },
        dataType: "JSON",
        success: function (data) {
            $("#total-visitor-current-" + phase).empty();
            $("#total-visitor-difference-" + phase).empty();
            $("#total-visitor-current-" + phase).append(data.data.current);
            $("#total-visitor-difference-" + phase).append(
                data.data.difference
            );
        },
    });
}

function visitorChart() {
    $.ajax({
        url: "/visitor/chart",
        method: "GET",
        dataType: "JSON",
        success: function (data) {
            $("#visitor-this-month").empty();
            $("#visitor-last-month").empty();
            $("#visitor-this-month").append(data.data[1].total);
            $("#visitor-last-month").append(data.data[0].total);
            $("#loadingVisitorChart").hide();
            var options = {
                chart: {
                    height: 346,
                    type: "line",
                    zoom: { enabled: !1 },
                    toolbar: { show: !1 },
                },
                dataLabels: { enabled: !1 },
                stroke: { width: 3, curve: "smooth", dashArray: [0, 8] },
                series: [
                    {
                        name: data.data[1].title,
                        data: data.data[1].data,
                    },
                    {
                        name: data.data[0].title,
                        type: "area",
                        data: data.data[0].data,
                    },
                ],
                colors: ["#3d8ef8", "#11c46e"],
                fill: { opacity: [1, 0.15] },
                markers: { size: 0, hover: { sizeOffset: 6 } },
                xaxis: {
                    categories: data.data[0].categories,
                },
                grid: { borderColor: "#f1f1f1" },
            };
            (chart = new ApexCharts(
                document.querySelector("#revenue-chart"),
                options
            )).render();
        },
    });
}

function streamCanvasDashboard() {
    var countCameras = $("#countCameras").val();

    for (i = 0; i < countCameras; i++) {
        var prefixPort = $("#prefixPort" + (i + 1)).val();
        var ipStreamer = $("#ipStreamer" + (i + 1)).val();

        new JSMpeg.Player("ws://" + "159.89.206.10" + (":" + prefixPort), {
            canvas: document.getElementById("camera-preview-" + (i + 1)),
            poster: "/assets/images/no-signal.jpg",
            disableGl: true,
        });
    }
}
