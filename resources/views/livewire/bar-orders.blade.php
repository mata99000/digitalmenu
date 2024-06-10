<div>
    <div x-data="orderTimer()" id="orders-container" class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 p-4">
        @foreach ($orders as $order)
            <div id="order-{{ $order->id }}" class="bg-white shadow-md rounded-lg overflow-hidden"
                 x-init="startTimer({{ $order->id }}, '{{ $order->created_at }}')">
                <div class="p-4">
                    <h3 class="text-lg font-bold mb-2">Order #{{ $order->id }}</h3>
                    <ul class="mb-4">
                        @foreach ($order->orderItems as $item)
                            @if ($item->item->type === 'drink')
                                <li class="flex justify-between items-center py-2">
                                    <span>{{ $item->item->name }}</span>
                                    <span class="text-gray-500">x{{ $item->quantity }}</span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                    @if($order->status !== 'completed')
                        <button @click="selectedOrderId = {{ $order->id }}; showModal = true"
                                class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition duration-300">
                            Mark as Completed
                        </button>
                    @endif
                </div>
                <div class="px-4 py-2 bg-gray-100 text-right">
                    <span class="text-sm font-semibold">Time Elapsed:</span>
                    <span x-text="timers['{{ $order->id }}'].formattedTime" class="text-sm"></span>
                </div>
            </div>
        @endforeach
    
        <!-- Modal for Confirmation -->
        <div x-show="showModal" style="background-color: rgba(0, 0, 0, 0.5);" class="fixed inset-0 flex items-center justify-center">
            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Confirm Completion
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to mark order #<span x-text="selectedOrderId"></span> as completed?
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="completeOrder(selectedOrderId)" @click="showModal = false"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-500 text-base font-medium text-white hover:bg-blue-400 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150 sm:ml-3 sm:w-auto sm:text-sm">
                        Confirm
                    </button>
                    <button @click="showModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    


<script>
    function orderTimer() {
        return {
            showModal: false,
            selectedOrderId: null,
            timers: {},
    
            startTimer(orderId, createdAt) {
                this.timers[orderId] = { startTime: Date.parse(createdAt), formattedTime: '' };
                this.updateTime(orderId);
    
                setInterval(() => {
                    this.updateTime(orderId);
                }, 1000);
            },
    
            updateTime(orderId) {
                const elapsed = Math.floor((Date.now() - this.timers[orderId].startTime) / 1000);
                this.timers[orderId].formattedTime = this.formatTime(elapsed);
            },
    
            formatTime(seconds) {
                const h = Math.floor(seconds / 3600);
                const m = Math.floor((seconds % 3600) / 60);
                const s = seconds % 60;
                return [
                    h > 0 ? `${h}h` : '',
                    m > 0 ? `${m}m` : '',
                    `${s}s`
                ].join(' ');
            }
        }
    }
     // Provera tipa stavki u narudžbi
     document.addEventListener('DOMContentLoaded', function () {
    window.Echo.channel('orders')
        .listen('OrderCreated', (e) => {
            console.log('Complete event data:', e);
            console.log('Order ID being sent:', e.order.id);
            window.Livewire.dispatch('order-created', { orderId: e.order.id });

            // Provera tipa stavki u narudžbi
            let containsFood = false;
            let containsDrink = false;
            
            e.order.order_items.forEach(item => {
                if (item.item.type === 'food') {
                    containsFood = true;
                } else if (item.item.type === 'drink') {
                    containsDrink = true;
                }
            });

            // Reprodukovanje zvuka za hranu
           
            // Reprodukovanje zvuka za piće
            if (containsDrink) {
                let audioDrink = new Audio('/audio/beep.mp3');
                audioDrink.play().catch(error => console.log("Error playing the drink sound:", error));
            }
        });
});

    </script>
</div>