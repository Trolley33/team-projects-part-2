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

    <!-- nice tables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
  
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>

</head>
<body>

<style>
.navbar {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #333;
}

.navbar-item {
    float: left;
}

.navbar-item a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

.navbar-item a:hover {
    background-color: #111;
}

.active {
    background-color: #FFF;
}

.active a {
    color: black;
}

.active a:hover {
    background-color: #FFF;
    color: black;
}

.editbutton:hover
{
    background-color: #BBBBBB !important;
    cursor: pointer;
}

.slideHeader:hover
{
    background-color: #BBBBBB !important;
    cursor: pointer;
}
.editbutton:hover .icon
{
    display: block;
}
.icon {
    float: right;
    display: none;
}
</style>
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
