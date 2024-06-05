import './bootstrap';
// Asumiramo da ste već podesili Echo kako treba.
// resources/js/app.js (ili odgovarajući JS fajl)


  

Echo.channel('order-channel')
    .listen('OrderCreated', (event) => {
        console.log('Received data:', event);
        // existing code to handle the event
    });
