<html>
<head>
    <!-- <link rel="stylesheet" type="text/css" href="http://cort.sci-project.lboro.ac.uk/team4/style.css" /> -->
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- fancy dropdown code -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>    
    <title>{{config('app.name')}}</title>
</head>
<body>

    <div class="header w3-container w3-dark-grey w3-center">
        <h1>{{$title}}</h1>
        <h4>{{$desc}}</h4>
    </div>

    @yield('content')
</body>
</html>
