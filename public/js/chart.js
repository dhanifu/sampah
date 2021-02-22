$(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    let areaChartData = {
        labels: [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
        ],
        datasets: [
            {
                label: "Digital Goods",
                backgroundColor: "rgba(60,141,188,0.9)",
                borderColor: "rgba(60,141,188,0.8)",
                pointRadius: false,
                pointColor: "#3b8bba",
                pointStrokeColor: "rgba(60,141,188,1)",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(60,141,188,1)",
                data: [28, 48, 40, 19, 86, 27, 90],
            },
            {
                label: "Electronics",
                backgroundColor: "rgba(210, 214, 222, 1)",
                borderColor: "rgba(210, 214, 222, 1)",
                pointRadius: false,
                pointColor: "rgba(210, 214, 222, 1)",
                pointStrokeColor: "#c1c7d1",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: [65, 59, 80, 81, 56, 55, 40],
            },
        ],
    };

    let areaChartOptions = {
        maintainAspectRatio: false,
        responsive: true,
        legend: {
            display: false,
        },
        scales: {
            xAxes: [
                {
                    gridLines: {
                        display: false,
                    },
                },
            ],
            yAxes: [
                {
                    gridLines: {
                        display: false,
                    },
                },
            ],
        },
    };

    let lineChartCanvas = $("#lineChart").get(0).getContext("2d");
    let lineChartOptions = $.extend(true, {}, areaChartOptions);
    let lineChartData = $.extend(true, {}, areaChartData);
    lineChartData.datasets[0].fill = false;
    lineChartData.datasets[1].fill = false;
    lineChartOptions.datasetFill = false;

    let lineChart = new Chart(lineChartCanvas, {
        type: "line",
        data: lineChartData,
        options: lineChartOptions,
    });
});
