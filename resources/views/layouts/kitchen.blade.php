
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <style>.order-block {
    border: 1px solid #ccc;
    padding: 15px;
    margin-bottom: 10px;
    background-color: #f9f9f9;
}
</style>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body>
        @livewire('kitchen-orders')
        <div id="orders-container">
            <!-- Narudžbine će biti dodate ovde dinamički -->
        </div>
       
     <!-- resources/views/livewire/kitchen-orders.blade.php -->
    
        @livewireScripts
    </body>
   
</html>
