@extends('layouts.app')

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>Review Activity</h2>
            <hr />
            <div style="width: 600px; margin: auto;">
            	<h2>Overview</h2>
            	<select id="data-changer" onchange="swapDataSet()" style="width: 50%">
            		@foreach ($datasets as $i=>$d)
            			<option value="{{$i}}">{{$d['yLabel']}}</option>
            		@endforeach
            	</select>
            	<br /><br />
            	<input id="start" type="date" /> - <input id="end" type="date" /> <button onclick="changeRange()">↺</button> <button onclick="resetRange()">✖</button>
            	<canvas width="600" height="300" id='graph'>
            	</canvas>
            	<hr />
            	<h2>Most Common Problem Types</h2>
            	<canvas width="600" height="300" id='graph2'>
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
    var chart = $('#graph');
    var chart2 = $('#graph2');
    <?php
    	foreach ($datasets as $key => $value) {
    		echo "sets.push({";
    			echo "yLabel: '". $value['yLabel']."', ";
    			echo "dataset: {";
    				echo "label: '',";
    				echo "backgroundColor: '".$value['color']."', ";
	    			echo "data: ". json_encode($value['data']);
	    		echo "}";
	    	echo "});";
    	}
    ?>

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
				    	source: 'auto'
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

	var myChart2 = new Chart(chart2, {
		type: 'bar',
		data: {
			labels : <?php echo json_encode(array_column($most_pt, 'x')); ?>,
		    datasets : [
			    {
			    	data: <?php echo json_encode(array_column($most_pt, 'y')); ?>,
			    	label: '',
			    	backgroundColor: makeRandomColors(<?php echo count($most_pt); ?>)
			    }
		    ]
		},
		options: {
	        scales: {
	            xAxes: [{
				    barPercentage: 1.0,
				    categoryPercentage: 1.0
	            }],
	            yAxes: [{
	            	scaleLabel: {
	            		display: true,
	            		labelString: 'Logged Problems'
	            	},
	            	ticks: {
	            		beginAtZero: true
	            	}
	            }]
	        }
	    }
	});

});

function makeRandomColors (n)
{
	colors = []
	for (var i = 0; i < n; i++) 
	{
		var r = Math.floor((Math.random() * 255) + 1);
		var g = Math.floor((Math.random() * 255) + 1);
		var b = Math.floor((Math.random() * 255) + 1);
		colors.push('rgb(' + r + "," + g + "," + b + ")");
	}
	return colors;
}
function swapDataSet()
{
	var to = sets[$('#data-changer').val()];

	myChart.options.scales.yAxes[0].scaleLabel.labelString = to.yLabel;
	myChart.data.datasets[0] = to.dataset;
	myChart.update();
}

function changeRange()
{
	var start = $('#start');
	var end = $('#end');
	var startDate = new Date(start.val());
	var endDate = new Date(end.val());
	if (isNaN(startDate.getTime()) || isNaN(endDate.getTime())) 
	{
		resetRange();
		return;
	}
	if (start.val() > end.val())
	{
		alert('Start date cannot be after end date.');
		return;
	}

	myChart.options.scales.xAxes[0].time.min = start.val();
	myChart.options.scales.xAxes[0].time.max = end.val();
	myChart.update();
}

function resetRange()
{
	myChart.options.scales.xAxes[0].time.min = null;
	myChart.options.scales.xAxes[0].time.max = null;
	myChart.update();
}
</script>

@endsection
