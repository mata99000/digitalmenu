<div id="orders-container" class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 p-4" wire:ignore >
    @foreach ($orders as $order)
        <div id="order-{{ $order->id }}" class="bg-white shadow-md rounded-lg p-4">
            <h3 class="text-lg font-bold mb-2">Order #{{ $order->id }}</h3>
            <ul class="mb-4">
                @foreach ($order->orderItems as $item)
                    @if ($item->item->type === 'food')
                        <li class="flex justify-between items-center py-2">
                            <span>{{ $item->item->name }}</span>
                            <span class="text-gray-600">x{{ $item->quantity }}</span>
                        </li>
                    @endif
                @endforeach
            </ul>
            @if($order->status !== 'completed')
                <button wire:click="completeOrder({{ $order->id }})" class="complete-order bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300">
                    Mark as Completed
                </button>
            @endif
        </div>
    @endforeach

    
    <script>document.addEventListener('DOMContentLoaded', function () {
        function addOrderToPage(order) {
            const ordersContainer = document.getElementById('orders-container');
            if (ordersContainer) {
                const foodItems = order.order_items.filter(item => item.item.type === 'food');
    
                if (foodItems.length > 0) {
                    const orderElement = document.createElement('div');
                    orderElement.setAttribute('id', `order-${order.id}`);
                    orderElement.classList.add('bg-white', 'shadow-md', 'rounded-lg', 'p-4', 'mb-4');
                    orderElement.innerHTML = `
                        <h3 class="text-lg font-bold mb-2">Order #${order.id}</h3>
                        <ul class="mb-4">
                            ${foodItems.map(item => `
                                <li class="flex justify-between items-center py-2">
                                    <span>${item.item.name}</span>
                                    <span class="text-gray-600">x${item.quantity}</span>
                                </li>
                            `).join('')}
                        </ul>
                        ${order.status !== 'completed' ? `
                            <button data-order-id="${order.id}" class="complete-order bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300">
                                Mark as Completed
                            </button>
                        ` : ''}
                    `;
                    ordersContainer.appendChild(orderElement);
                }
            } else {
                console.error('Container for orders not found');
            }
        }
    
        function removeOrderFromPage(orderId) {
            const orderElement = document.getElementById(`order-${orderId}`);
            if (orderElement) {
                orderElement.remove();
            }
        }
    
        window.Echo.channel('orders')
            .listen('OrderCreated', (e) => {
                console.log(e.order);
                addOrderToPage(e.order);
            })
            .listen('OrderUpdated', (e) => {
                console.log('Order updated:', e.order);
                removeOrderFromPage(e.order.id);
            });
    
        document.addEventListener('order-updated', event => {
            const orderId = event.detail.orderId;
            removeOrderFromPage(orderId);
        });
    });
    </script>
</div>
