<div x-data="orderTimer()" class="container mx-auto p-6">
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3" id="orders-container">
        @foreach ($orders as $order)
            <div id="order-{{ $order->id }}" 
                 class="order-card flex flex-col bg-white shadow-lg rounded-lg overflow-hidden transition-all duration-300 border-t-4"
                 :class="orderBorderClass({{ $order->id }})"
                 x-init="startTimer({{ $order->id }}, '{{ $order->created_at }}')">
                <div class="flex-grow p-4">
                    <h3 class="text-xl font-bold mb-4">Order #{{ $order->id }}</h3>
                    <ul class="space-y-2">
                        @foreach ($order->orderItems as $item)
                            @if ($item->item->type === 'food')
                                <li class="flex justify-between items-center">
                                    <span>{{ $item->item->name }}</span>
                                    <span class="text-gray-600">
                                        x{{ $item->quantity < 0 ? '-' : '' }}{{ abs($item->quantity) }}
                                    </span>
                                </li>
                                @if ($item->orderItemOptions->isNotEmpty())
                                    <ul class="pl-4 text-sm text-gray-500">
                                        @foreach ($item->orderItemOptions as $option)
                                            <li class="{{ $option->option->type === 'add' ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $option->option->name }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            @endif
                        @endforeach
                    </ul>
                </div>
                <div class="bg-gray-50 p-4 flex items-center justify-between">
                    <div>
                        <span class="text-sm font-semibold text-gray-700">Time Elapsed:</span>
                        <span x-text="timers['{{ $order->id }}'].formattedTime" class="text-sm"></span>
                    </div>
                    @if($order->status !== 'completed')
                        <button @click="selectedOrderId = {{ $order->id }}; showModal = true"
                                class="bg-blue-500 text-white px-3 py-2 md:px-4 md:py-2 rounded-md hover:bg-blue-600 transition duration-300 text-xs md:text-sm">
                            Mark as Completed
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal for Confirmation -->
    <div x-show="showModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
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
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Confirm
                </button>
                <button @click="showModal = false"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 sm:mt-0 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
