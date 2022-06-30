$(".slider-for").slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: !1,
    autoplay: !0,
    asNavFor: ".slider-nav"
});
$(".slider-nav").slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    asNavFor: ".slider-for",
    arrows: !1,
    dots: !1,
    centerMode: !0,
    focusOnSelect: !0,
    responsive: [{breakpoint: 1680, settings: {slidesToShow: 2, slidesToScroll: 2}}, {
        breakpoint: 1440,
        settings: {slidesToShow: 1, slidesToScroll: 1}
    }, {breakpoint: 1200, settings: {slidesToShow: 3, slidesToScroll: 3}}, {
        breakpoint: 600,
        settings: {slidesToShow: 2, slidesToScroll: 2}
    }, {breakpoint: 480, settings: {slidesToShow: 1, slidesToScroll: 1}}]
});

options = {
    chart: {height: 250, type: "radialBar", offsetY: -20},
    plotOptions: {
        radialBar: {
            startAngle: -135,
            endAngle: 135,
            hollow: {size: "72%"},
            dataLabels: {
                name: {offsetY: -15},
                value: {
                    offsetY: 12, fontSize: "18px", color: void 0, formatter: function (e) {
                        return e + "%"
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
};
(chart = new ApexCharts(document.querySelector("#visitor-morning-chart"), options)).render();
options = {
    chart: {height: 250, type: "radialBar", offsetY: -20},
    plotOptions: {
        radialBar: {
            startAngle: -135,
            endAngle: 135,
            hollow: {size: "72%"},
            dataLabels: {
                name: {offsetY: -15},
                value: {
                    offsetY: 12, fontSize: "18px", color: void 0, formatter: function (e) {
                        return e + "%"
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
};
(chart = new ApexCharts(document.querySelector("#visitor-daylight-chart"), options)).render();
options = {
    chart: {height: 250, type: "radialBar", offsetY: -20},
    plotOptions: {
        radialBar: {
            startAngle: -135,
            endAngle: 135,
            hollow: {size: "72%"},
            dataLabels: {
                name: {offsetY: -15},
                value: {
                    offsetY: 12, fontSize: "18px", color: void 0, formatter: function (e) {
                        return e + "%"
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
};
(chart = new ApexCharts(document.querySelector("#visitor-evening-chart"), options)).render();
options = {
    chart: {height: 250, type: "radialBar", offsetY: -20},
    plotOptions: {
        radialBar: {
            startAngle: -135,
            endAngle: 135,
            hollow: {size: "72%"},
            dataLabels: {
                name: {offsetY: -15},
                value: {
                    offsetY: 12, fontSize: "18px", color: void 0, formatter: function (e) {
                        return e + "%"
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
};
(chart = new ApexCharts(document.querySelector("#visitor-night-chart"), options)).render();


function reload() {
    location.reload();
}
function playSoundNotification() {
    $.ajax({
        url: "/test-notification",
        method: "GET",
        dataType: 'JSON',
        success:
            function (data) {
                var mp3Source = "<source src=" + data.data.mp3 + ">";
                var oggSource = "<source src=" + data.data.ogg + ">";
                var embedSource = '<embed hidden="true" autostart="true" loop="false" src="'+ data.data.embed +'">';
                document.getElementById("soundNotification").innerHTML = '<audio autoplay="allowed">' + embedSource + '</audio>';
            }
    });
}

function playSoundHuman(name) {
    $.ajax({
        url: "/test-speech",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: "POST",
        dataType: 'JSON',
        data: {
            "name": name,
        },
        success:
            function (data) {
                var voice = "<source src=" + data.data.response + ">";
                document.getElementById("soundWelcome").innerHTML = '<audio autoplay="allowed">' + voice + '</audio>';
            }
    });
}

