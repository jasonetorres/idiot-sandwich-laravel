import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'd84c68522eed76aeff71', // Your key hardcoded
    cluster: 'us2',             // Your cluster hardcoded
    forceTLS: false
});