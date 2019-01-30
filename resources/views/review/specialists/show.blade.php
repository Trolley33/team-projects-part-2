@extends('layouts.app')

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>Reviewing Specialist {{$specialist->forename}} {{$specialist->surname}}</h2>
            <hr />
             <table>
                <tbody>
                    <tr class="w3-hover-light-grey">
                        <th># of Problems Solved</th>
                    	<td></td>
                    </tr>
		        </tbody>
            </table>
            <hr />
            <div style="width: 80%; margin: auto;">
            	<input id="start" type="date" /> - <input id="end" type="date" /> <button onclick="changeRange()" ">↺</button>
            	<canvas id='problems-solved-graph'>
            	</canvas>
        	</div>
        </div>
    </div>
</div>

<script>
var myChart;
$(document).ready( function () 
{
    var chart = $('#problems-solved-graph');

    myChart = new Chart(chart, {
		type: 'line',
		data: {
		    datasets : [
		        {
		            label: "# of problems solved",
		            borderColor: 'rgba(0, 255, 255, 1)',
		            data : 
		            	<?php echo json_encode($solved); ?>
		        },
		        {
		            label: "# of problems reassigned",
		            borderColor: 'rgba(0, 255, 0, 1)',
		            data : 
		            	<?php echo json_encode($reassigned); ?>
		        }
		    ]
		},
		options: {
	        scales: {
	            xAxes: [{
	                type: 'time',
	                time: {
	                    unit: 'month'
	                },
	                ticks: {
	                	source: 'data'
	                }
	            }],
	            yAxes: [{
	            	ticks: {
	            		beginAtZero: true
	            	}
	            }]
	        }
	    }
	});
});

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
