//Sparkline Charts
var labels = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];

var options = {
    legend: {
        display: false,
    },
    scales: {
        xAxes: [{
            display:false,
        }],
        yAxes: [{
            display:false,
        }]
    },
    elements: {
        point: {
            radius: 0,
            hitRadius: 10,
            hoverRadius: 4,
            hoverBorderWidth: 3,
        }
    },
};

var data1 = {
    labels: labels,
    datasets: [
        {
            backgroundColor: 'transparent',
            borderColor: $.brandPrimary,
            borderWidth: 2,
            data: [35, 23, 56, 22, 97, 23, 64]
        }
    ]
};
var ctx = $('#sparkline-chart-1');
var sparklineChart1 = new Chart(ctx, {
    type: 'line',
    data: data1,
    options: options
});

var data2 = {
    labels: labels,
    datasets: [
        {
            backgroundColor: 'transparent',
            borderColor: $.brandDanger,
            borderWidth: 2,
            data: [78, 81, 80, 45, 34, 12, 40]
        }
    ]
};
var ctx = $('#sparkline-chart-2');
var sparklineChart2 = new Chart(ctx, {
    type: 'line',
    data: data2,
    options: options
});

var data3 = {
    labels: labels,
    datasets: [
        {
            backgroundColor: 'transparent',
            borderColor: $.brandWarning,
            borderWidth: 2,
            data: [35, 23, 56, 22, 97, 23, 64]
        }
    ]
};
var ctx = $('#sparkline-chart-3');
var sparklineChart3 = new Chart(ctx, {
    type: 'line',
    data: data3,
    options: options
});

var data4 = {
    labels: labels,
    datasets: [
        {
            backgroundColor: 'transparent',
            borderColor: $.brandSuccess,
            borderWidth: 2,
            data: [78, 81, 80, 45, 34, 12, 40]
        }
    ]
};
var ctx = $('#sparkline-chart-4');
var sparklineChart4 = new Chart(ctx, {
    type: 'line',
    data: data4,
    options: options
});

var data5 = {
    labels: labels,
    datasets: [
        {
            backgroundColor: 'transparent',
            borderColor: '#d1d4d7',
            borderWidth: 2,
            data: [35, 23, 56, 22, 97, 23, 64]
        }
    ]
};
var ctx = $('#sparkline-chart-5');
var sparklineChart5 = new Chart(ctx, {
    type: 'line',
    data: data5,
    options: options
});

var data6 = {
    labels: labels,
    datasets: [
        {
            backgroundColor: 'transparent',
            borderColor: $.brandInfo,
            borderWidth: 2,
            data: [78, 81, 80, 45, 34, 12, 40]
        }
    ]
};
var ctx = $('#sparkline-chart-6');
var sparklineChart6= new Chart(ctx, {
    type: 'line',
    data: data6,
    options: options
});