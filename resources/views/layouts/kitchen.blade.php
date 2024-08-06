
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
        <style>
  .border-green-500 {
    border-color: #48bb78;
  }
  .border-orange-500 {
    border-color: #ed8936;
  }
  .border-red-500 {
    border-color: #f56565;
  }

  .glow {
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }

  .border-green-500.glow {
    box-shadow: 0 0 15px rgba(72, 187, 120, 0.5);
  }

  .border-orange-500.glow {
    box-shadow: 0 0 15px rgba(237, 137, 54, 0.5);
  }

  .border-red-500.glow {
    box-shadow: 0 0 20px rgba(245, 101, 101, 1);
  }

  .pulse {
    animation: pulse 2s infinite;
  }

  @keyframes pulse {
    0% {
      transform: scale(1);
    }
    50% {
      transform: scale(1.05);
    }
    100% {
      transform: scale(1);
    }
  }
</style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body>
    <livewire:kitchen-nav />
        @livewire('kitchen-orders')
        <div id="orders-container">
            <!-- Narudžbine će biti dodate ovde dinamički -->
        </div>
       
     <!-- resources/views/livewire/kitchen-orders.blade.php -->
    
        @livewireScripts
        <script>
            function orderTimer() {
    return {
        showModal: false,
        selectedOrderId: null,
        timers: {},
        currentTime: new Date().toLocaleString(),

        startTimer(orderId, createdAt) {
            const startTime = Date.parse(createdAt);
            if (!this.timers) {
                this.timers = {};
            }
            this.timers[orderId] = { startTime, formattedTime: '' };
            this.updateTime(orderId);

            this.$nextTick(() => {
                this.updateBorderClass(orderId);
            });

            setInterval(() => {
                this.updateTime(orderId);
                this.updateBorderClass(orderId);
                this.currentTime = new Date().toLocaleString(); // Ažuriranje vremena
            }, 1000);
        },

        updateTime(orderId) {
            if (!this.timers[orderId]) return;
            const now = Date.now();
            const elapsed = Math.max(Math.floor((now - this.timers[orderId].startTime) / 1000), 0);
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
        },

        orderBorderClass(orderId) {
            if (!this.timers[orderId]) return '';
            const now = Date.now();
            const elapsed = Math.max(Math.floor((now - this.timers[orderId].startTime) / 1000), 0);
            let borderClass = 'glow';

            if (elapsed < 300) {
                borderClass += ' border-green-500';
            } else if (elapsed < 900) {
                borderClass += ' border-orange-500 pulse';
            } else {
                borderClass += ' border-red-500 pulse';
            }

            return borderClass;
        },

        updateBorderClass(orderId) {
            const element = document.getElementById(`order-${orderId}`);
            if (element) {
                element.className = 'order-card flex flex-col bg-white shadow-lg rounded-lg overflow-hidden transition-all duration-300 border-t-4 ' + this.orderBorderClass(orderId);
            }
        }
    }
}
document.addEventListener('DOMContentLoaded', function () {
    window.Echo.channel('orders')
        .listen('OrderCreated', (e) => {
            window.Livewire.dispatch('order-created', { orderId: e.order.id });

            let containsFood = false;
            e.order.order_items.forEach(item => {
                if (item.item.type === 'food') {
                    containsFood = true;
                }
            });

            if (containsFood) {
                let audioFood = new Audio('/audio/beep.mp3');
                audioFood.play().catch(error => console.log("Error playing the food sound:", error));
            }

            // Update DOM to reflect new order
            const ordersContainer = document.getElementById('orders-container');
            const newOrderHTML = `
            <div id="order-${e.order.id}" 
                 class="order-card flex flex-col bg-white shadow-lg rounded-lg overflow-hidden transition-all duration-300 border-t-4"
                 x-data="orderTimer()" x-init="startTimer(${e.order.id}, '${e.order.created_at}')">
                <div class="flex-grow p-4">
                    <h3 class="text-xl font-bold mb-4">Order #${e.order.id}</h3>
                    <ul class="space-y-2">
                        ${e.order.order_items.map(item => `
                            <li class="flex justify-between items-center">
                                <span>${item.item.name}</span>
                                <span class="text-gray-600">x${item.quantity < 0 ? '-' : ''}${Math.abs(item.quantity)}</span>
                            </li>
                            ${item.order_item_options ? item.order_item_options.map(option => `
                                <ul class="pl-4 text-sm text-gray-500">
                                    <li class="${option.option.type === 'add' ? 'text-green-600' : 'text-red-600'}">${option.option.name}</li>
                                </ul>
                            `).join('') : ''}
                        `).join('')}
                    </ul>
                </div>
                <div class="bg-gray-50 p-4 flex items-center justify-between">
                    <div>
                        <span class="text-sm font-semibold text-gray-700">Time Elapsed:</span>
                        <span x-text="timers['${e.order.id}'].formattedTime" class="text-sm"></span>
                    </div>
                    <button @click="selectedOrderId = ${e.order.id}; showModal = true"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300">
                        Mark as Completed
                    </button>
                </div>
            </div>`;
            ordersContainer.insertAdjacentHTML('beforeend', newOrderHTML);
            Alpine.initTree(document.getElementById(`order-${e.order.id}`)); // Inicijalizacija Alpine.js za novododati element
        });
});

        </script>
    </body>
   
</html>
