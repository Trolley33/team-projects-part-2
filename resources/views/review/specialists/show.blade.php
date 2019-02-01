@extends('layouts.app')

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>Reviewing Specialist {{$specialist->forename}} {{$specialist->surname}}</h2>
            <hr />
            <div style="width: 600px; margin: auto;">
            	<select id="data-changer" onchange="swapDataSet()">
            		<option value="0" selected>Problems Solved</option>
            		<option value="1">Time Taken to Solve Problem</option>
            	</select>
            	<br />
            	<input id="start" type="date" /> - <input id="end" type="date" /> <button onclick="changeRange()">↺</button>
            	<canvas width="600" height="300" id='problems-solved-graph'>
            	</canvas>
        	</div>
        	</div>
        </div>
    </div>
</div>

<script>
var myChart;
var sets = [];

$(document).ready( function () 
{
    var chart = $('#problems-solved-graph');

    sets.push(
    {
    	ylabel: 'Problems Solved Per Week',
		dataset: {
			label: '',
	        backgroundColor: 'rgb(0,128,128)',
	    	data: <?php echo json_encode($solved); ?>
	    }
	});
	sets.push(
    {
    	ylabel: 'AVG Time to Solve Problems (Minutes)',
		dataset: {
			label: '',
	        backgroundColor: 'rgb(191, 53, 84)',
	    	data: <?php echo json_encode($timeToSolve); ?>
	    }
	});


    myChart = new Chart(chart, {
		type: 'bar',
		data: {
		    datasets : [
		    ]
		},
		options: {
	        scales: {
	            xAxes: [{
	            	bounds: 'ticks',
	                type: 'time',
	                time: {
	                	unit: 'week'
				    },
				    ticks: {
				    	source: 'data'
				    },
				    barPercentage: 1.0,
				    categoryPercentage: 1.0
	            }],
	            yAxes: [{
	            	scaleLabel: {
	            		display: true
	            	},
	            	ticks: {
	            		beginAtZero: true
	            	}
	            }]
	        },
	        // Container for pan options
			pan: {
				enabled: true,
				mode: 'x',
				speed: 10,
				threshold: 10
			},
			zoom: {
				enabled: true,
				mode: 'x',
				limits: {
					max: 10,
					min: 0.5
				}
			},
	    }
	});

	swapDataSet(sets[0]);

});
function swapDataSet()
{
	var to = sets[$('#data-changer').val()];

	myChart.options.scales.yAxes[0].scaleLabel.labelString = to.ylabel;
	myChart.data.datasets[0] = to.dataset;
	myChart.update();
}

function changeRange()
{
	var start = $('#start');
	var end = $('#end');
	myChart.options.scales.xAxes[0].time.min = start.val();
	myChart.options.scales.xAxes[0].time.max = end.val();
	myChart.update();
}
</script>

@endsection
