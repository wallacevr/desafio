<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Produtos')</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="{{ mix('js/app.js') }}"></script>
    <style>
        .product-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.2s;
        }
        .product-card:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .product-image {
            height: 200px;
            object-fit: cover;
        }
        .product-info {
            padding: 15px;
            text-align: center;
        }
        .product-price {
            font-size: 1.2rem;
            font-weight: bold;
            color: #28a745;
        }
        .product-description {
            font-size: 0.9rem;
            color: #666;
        }
    </style>
     @livewireStyles()
</head>
<body>
    <div class="container my-5">
  
       @yield('content')

    </div>
    @livewireScripts()
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
 
</body>
</html>
