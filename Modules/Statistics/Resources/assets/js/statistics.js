document.addEventListener('DOMContentLoaded', function () {
	Highcharts.setOptions({
	    lang: {
	        thousandsSep: ','
	    }
	});
    var topFiveBorrowers = Highcharts.chart('borrowres-top-5', {
	    chart: {
	        type: 'column'
	    },
	    title: {
	        text: 'Top 5 Borrowers'
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
	            text: 'Total Project Amount'
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
	        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>₦{point.y:,.2f}</b> of total<br/>'
	    },
	    series: [
	        {
	            name: "Borrowers",
	            colorByPoint: true,
	            data: top5Borrowers
	        }
	    ],
	});
	var topFiveInvestors = Highcharts.chart('investors-top-5', {
	    chart: {
	        type: 'column'
	    },
	    title: {
	        text: 'Top 5 Investors'
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
	            text: 'Total Invested Amount'
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
	        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>₦{point.y:,.2f}</b> of total<br/>'
	    },
	    series: [
	        {
	            name: "Investors",
	            colorByPoint: true,
	            data: top5Investors
	        }
	    ],
	});
	var topFiveInvestors = Highcharts.chart('projects-top-5', {
	    chart: {
	        type: 'column'
	    },
	    title: {
	        text: 'Top 5 Projects'
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
	            text: 'Total Project Amount'
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
	            name: "Projects",
	            colorByPoint: true,
	            data: top5Projects
	        }
	    ],
	});
});