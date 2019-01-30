@extends('layouts.app')

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>Reviewing Specialist {{$specialist->forename}} {{$specialist->surname}}</h2>
            <hr />
            Information table.
            <hr />
            <div style="width: 80%; margin: auto;">
            	<canvas id='problems-solved-graph'>
            	</canvas>
        	</div>
        </div>
    </div>
</div>

<script>
$(document).ready( function () 
{
    var chart = $('#problems-solved-graph');

    var myChart = new Chart(chart, {
		type: 'line',
		data: {
		    datasets : [
		        {
		            label: "# of problems solved",
		            borderColor: 'rgba(0, 255, 255, 1)',
		            data : 
		            	<?php echo json_encode($data); ?>
		        },
		        {
		            label: "# of problems reassigned",
		            borderColor: 'rgba(0, 255, 0, 1)',
		            data : 
		            	<?php echo json_encode($data); ?>
		        }
		    ]
		},
		options: {
	        scales: {
	            xAxes: [{
	                type: 'time',
	                time: {
	                    unit: 'month'
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
</script>

@endsection
