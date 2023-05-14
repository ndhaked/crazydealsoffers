document.addEventListener('DOMContentLoaded', function () {
	Highcharts.setOptions({
	    lang: {
	        thousandsSep: ','
	    }
	});
    var topFiveBorrowers = Highcharts.chart('users-top-5', {
	    chart: {
	        type: 'column'
	    },
	    title: {
	        text: 'Top 5 Users'
	    },
	    subtitle: {
	        text: ''
	    },
	    accessibility: {
	        announceNewData: {
	            enabled: true
	        }
	    },
	    xAxis: {
	        type: 'category'
	    },
	    yAxis: {
	        title: {
	            text: 'Total Users'
	        }

	    },
	    legend: {
	        enabled: false
	    },
	    plotOptions: {
	        series: {
	            allowPointSelect: true
	        }
	    },

	    tooltip: {
	        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
	        pointFormat: '<span style="color:{point.color}">{point.name}</span>'
	    },
	    series: [
	        {
	            name: "Users",
	            colorByPoint: true,
	            data: top5Users
	        }
	    ],
	});
	
	var topFiveInvestors = Highcharts.chart('products-top-5', {
	    chart: {
	        type: 'column'
	    },
	    title: {
	        text: 'Top 5 Products'
	    },
	    subtitle: {
	        text: ''
	    },
	    accessibility: {
	        announceNewData: {
	            enabled: true
	        }
	    },
	    xAxis: {
	        type: 'category'
	    },
	    yAxis: {
	        title: {
	            text: 'Total Product Amount'
	        }

	    },
	    legend: {
	        enabled: false
	    },
	    plotOptions: {
	        series: {
	            allowPointSelect: true
	        }
	    },

	    tooltip: {
	        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
	        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:,.2f}</b> of total<br/>'
	    },
	    series: [
	        {
	            name: "Products",
	            colorByPoint: true,
	            data: top5Products
	        }
	    ],
	});
});