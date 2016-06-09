<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>POSCO SSVINA - @yield('title')</title>

    {{--STYLE--}}
    @section('style')
        {{--MATERIALIZE--}}
        <link href="/resources/assets/sass/materialize/materialize.css" rel="stylesheet" type="text/css">
        {{--<link href="/assets/sass/materialize/materialize.css" rel="stylesheet" type="text/css">--}}
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!-- Angular Material style sheet -->
        {{--<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/angular_material/1.0.0/angular-material.min.css">--}}
        {{--Font Awesome--}}
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        {{--TLCB--}}
        <link href="/resources/assets/sass/site.css" rel="stylesheet" type="text/css">
    @show
</head>
<body class="blue-grey darken-3">
@include('site.master.header')
@yield('content')

@include('site.master.footer')
@section('script')
    {{--JQUERY--}}
    <script type="text/javascript" src="/resources/assets/js/jquery-2.2.1.min.js"></script>
    <script type="text/javascript" src="/resources/assets/js/shared/materialize/bin/materialize.min.js"></script>
    <script type="text/javascript" src="/resources/assets/js/site.js"></script>
    {{--@include('site.master.myscript')--}}

@show
</body>
</html>



