<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
            padding: 50px;
        }
        h1 {
            font-size: 80px;
            color: #e74c3c;
        }
        p {
            font-size: 24px;
        }
        a {
            text-decoration: none;
            color: #3498db;
            font-size: 18px;
            padding: 10px 20px;
            background-color: #fff;
            border: 2px solid #3498db;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
        a:hover {
            background-color: #3498db;
            color: #fff;
        }
    </style>
</head>
<body>
    <h1>@yield('code')</h1>
    <p>@yield('message')</p>
    
    
        @if (auth()->user()->hasRole('Admin')|| auth()->user()->hasRole(roles: 'Admin'))
            <a href="{{ route('benefice-marge.dashboard') }}">Retourner à l'accueil</a>
        @elseif (auth()->user()->hasRole('Gérant_de_stock') || auth()->user()->hasRole('Vendeur'))
            <a href="{{ route('dashboardstock') }}">Retourner à l'accueil</a>
        @else
            <a href="{{ route('dashboard') }}">Retourner à l'accueil</a>
        @endif
    @else
        <a href="{{ url('/') }}">Retourner à l'accueil</a>
   
</body>
</html>