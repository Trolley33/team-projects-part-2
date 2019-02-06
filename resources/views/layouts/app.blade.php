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

        function closeModal ()
        {
            modal.html('');
            modal.hide();
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
    

</body>
</html>
