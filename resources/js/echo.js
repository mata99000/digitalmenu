import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

document.addEventListener('DOMContentLoaded', function () {
    window.Echo.channel('orders')
        .listen('OrderCreated', (e) => {
            console.log('Complete event data:', e);
            console.log('Order ID being sent:', e.order.id);
            window.Livewire.dispatch('order-created', { orderId: e.order.id });
             // Reprodukovanje zvuka kada se pojavi novi order
             let audio = new Audio('/audio/beep.mp3');
            audio.play().catch(error => console.log("Error playing the sound:", error));
        });
});
