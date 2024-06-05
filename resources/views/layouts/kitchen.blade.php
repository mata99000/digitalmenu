
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
    </head>
    <body>
        
        <div id="orders-container"></div>
<script>document.addEventListener('DOMContentLoaded', function() {
    window.Echo.channel('order-channel')
        .listen('OrderCreated', (e) => {
            const ordersContainer = document.getElementById('orders-container');
            if (ordersContainer) {  // Corrected variable name here
                const orderElement = document.createElement('div');
                orderElement.classList.add('order-block');

                let itemsHtml = '';
                e.order.items.forEach(item => {
                    itemsHtml += `<p>${item.name} - Quantity: ${item.quantity}</p>`;
                });

                orderElement.innerHTML = `
                    <h4>Order ID: ${e.order.id}</h4>
                    ${itemsHtml}
                    <hr>
                `;

                ordersContainer.appendChild(orderElement);
            } else {
                console.error('Orders container not found');  // Proper error handling
            }
        });
});
</script>
    </body>
   
</html>
