
function render(arr) {
$("#chart").toggle();

google.charts.load('current', {'packages': ['corechart']});
google.charts.setOnLoadCallback(drawChart);


function drawChart() {
var data = google.visualization.arrayToDataTable(arr);

var options = {
title: 'Course Completion Statistics',
is3D: true,
width: '100%',
height: '100%',
chartArea: {
left: "10%",
// top: "3%",
height: "80%",
width: "80%"
}
};

var chart = new google.visualization.PieChart(document.getElementById('myChart'));
chart.draw(data, options);
}

}
