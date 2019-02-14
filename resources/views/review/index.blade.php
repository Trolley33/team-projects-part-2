@extends('layouts.app')

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>Review Activity</h2>
            <hr />
            <div style="width: 600px; margin: auto;">
            	<h2>Overview</h2>
            	<!-- Dropdown for changing graph datasets -->
            	<select id="data-changer" onchange="swapDataSet()" style="width: 50%">
            		@foreach ($datasets as $i=>$d)
            			<option value="{{$i}}">{{$d['yLabel']}}</option>
            		@endforeach
            	</select>
            	<br /><br />
            	<!-- Start and end date pickers -->
            	<input id="start" type="date" /> - <input id="end" type="date" /> <button onclick="changeRange()">↺</button> <button onclick="resetRange()">✖</button>
            	<canvas width="600" height="300" id='graph'>
            	</canvas>
            	<hr />
            	<!-- Graph of most commonly submitted types of problem -->
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
	// Time graph
    var chart = $('#graph');
    // Labelled graph
    var chart2 = $('#graph2');
    // Convert PHP array to json object(s), usable in chart.js
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
    // Initialise graph with options
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
    // Select 1st dataset by default
	swapDataSet(sets[0]);
	// Create labelled graph with options set, using random colours for each bar
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
/**
 * Helper function to generate random RGB colour code.
 */
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
</script>

@endsection
