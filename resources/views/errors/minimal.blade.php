<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; padding: 50px; }
        h1 { font-size: 80px; color: #e74c3c; margin-bottom: 10px; }
        p { font-size: 24px; margin-bottom: 30px; }
        .btn-home { 
            text-decoration: none; 
            color: #ff0000; 
            font-weight: bold; 
            border: 1px solid #ff0000; 
            padding: 10px 20px; 
            border-radius: 5px; 
            transition: 0.3s;
        }
        .btn-home:hover { background-color: #ff0000; color: #fff; }
    </style>
</head>
<body>
    <h1>@yield('code')</h1>
    <p>@yield('message')</p>

    <div class="navigation">
        @if(auth()->check()) 
            {{-- التحقق من الأدوار بناءً على الـ Roles اللي عندك في الصورة --}}
            @if (auth()->user()->hasRole('Gérant_de_stock') || auth()->user()->hasRole('Vendeur')) 
                <a href="{{ route('dashboardstock') }}" class="btn-home">Retourner à l'accueil</a>
            @elseif (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Admin2'))
                <a href="{{ route('benefice-marge.dashboard') }}" class="btn-home">Retourner à l'accueil</a>
            @else
                {{-- هادي للأدوار الأخرى بحال Assistant و Opérateur --}}
                <a href="{{ route('dashboard') }}" class="btn-home">Retourner à l'accueil</a>
            @endif
        @else
            {{-- يلا مكانش مسجل الدخول --}}
            <a href="{{ url('/') }}" class="btn-home">Retourner à l'accueil</a>
        @endif
    </div>
</body>
</html>