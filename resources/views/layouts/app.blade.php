<html>
<head>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- Graphing library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-zoom/0.6.6/chartjs-plugin-zoom.js"></script>

    <!-- fancy dropdown code -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>    
    <title>{{config('app.name')}}</title>

    <!-- nice tables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js">
    </script>
    <!-- -->
    <script type="text/javascript">

        var modal;
        $(document).ready(function () {

            modal = $('#myModal');

            $(".modalOpener").click(function() {
                $.get(
                    $(this).attr('value'),
                    function (data) {
                        modal.html(data);
                        $('#myModal div').first().prepend('<span onclick="closeModal()" class="close">&times;</span>')
                    }
                );
                modal.show();
            });

            $(window).click(function(event) {
                var target = $(event.target);

                if (!target.hasClass('modalOpener'))
                { 
                  if (target.closest('.modal div').length == 0)
                  {
                    closeModal();
                  }
                }
            });

            $('.slideHeader').click(function(){
                $(this).next('.slideable').slideToggle();
            });
        });
        // Modal helper function.
        function closeModal ()
        {
            modal.html('');
            modal.hide();
        }
        
        // Graphing helper functions
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

        // Zero padding function for YYYY-MM-DD
        // *(https://stackoverflow.com/a/7379989)
        function zfill(num, len) {
            return (Array(len).join("0") + num).slice(-len);
        }
    </script>
</head>
<body>
    <div id="myModal" class="modal" value=''>
    </div>
    <div class="header w3-container w3-dark-grey">
        <div class="w3-center">
            <h1>{{$title}}</h1>
            <h4>{{$desc}}</h4>
        </div>
    </div>
    <ul class="navbar">
        @if (isset($links))
            @foreach ($links as $link)
            @if ($link['href'] == 'back')
                <li class="navbar-item"><a href="javascript:history.back()"><img src="https://i.imgur.com/Cic9Xby.png" style="height: 1.45em;" /></a></li>
            @elseif ($link['text'] == $active)
                <li class="navbar-item active"><a href="/{{$link['href']}}">{{$link['text']}}</a></li>
            @else
                <li class="navbar-item"><a href="/{{$link['href']}}">{{$link['text']}}</a></li>
            @endif

            @endforeach
            <li class="navbar-item" style="float: right !important; background-color: #515151;"><a href="/logout">Logout</a></li>
        @endif
    </ul>
    @include('messages')
    @yield('content')
    
<script>
    $(document).ready (function () {
        // On pages with a chart object.
        if (typeof myChart != 'undefined')
        {
            $('#data-changer').select2();
            // Find any non-empty dataset.
            var flag = -1;
            for (var i = sets.length - 1; i >= 0; i--) 
            {
                if (sets[i].dataset.data.length != 0)
                {
                    flag = i;
                }
            }

            // If all datasets empty, remove graph.
            if (flag == -1)
            {
                $('#graph').replaceWith("<div><h2>No Data to Show</h2></div>");
                return;
            }
            
            // Select first graph that has valid data.
            $('#data-changer').val(flag).trigger('change');

            // Perform date manipulation, in order to get start -> end = 1 quarter, with current month in center.
            var startDate = new Date();
            // Shift back 1 month
            startDate.setMonth(startDate.getMonth()-1);
            startDate.setDate(1);
            // Set start to first sunday of month
            startDate.setDate((7+1) - startDate.getDay());

            // Shift forward 3 months.
            var endDate = new Date(startDate.getTime() + (1000*60*60*24*7*4*3));
            // Format date to YYYY-MM-DD using zfill helper function.
            $('#start').val(startDate.getFullYear() + "-" + zfill(startDate.getMonth() + 1, 2) + "-" + zfill(startDate.getDate(), 2));
            $('#end').val(endDate.getFullYear() + "-" + zfill(endDate.getMonth() + 1, 2) + "-" + zfill(endDate.getDate(), 2));
            changeRange();
        }
    });
</script>
</body>
</html>
