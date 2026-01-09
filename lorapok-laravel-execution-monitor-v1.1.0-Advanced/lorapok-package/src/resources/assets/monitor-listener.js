// Lorapok monitor-listener.js
(function () {
    function subscribeToChannel() {
        try {
            if (window.Echo && typeof window.Echo.channel === 'function') {
                window.Echo.channel('execution-monitor').listen('.performance-alert', (e) => {
                    console.log('Lorapok: Performance Alert received', e);
                    try { window.dispatchEvent(new CustomEvent('lorapok:performance-alert', { detail: e })); } catch (err) { console.warn('Lorapok: dispatch error', err); }
                });
                return true;
            }
        } catch (err) { console.warn('Lorapok subscribe error', err); }
        return false;
    }

    function tryInitEcho(key, cluster) {
        // If a global initializer is provided by the page, call it (widget may set window.__lorapok_init_echo)
        try {
            if (typeof window.__lorapok_init_echo === 'function') {
                window.__lorapok_init_echo();
            }
        } catch (e) { console.warn('Lorapok: __lorapok_init_echo failed', e); }

        // If Echo constructor exists but no instance, try to create a minimal instance if key/cluster provided
        try {
            if (typeof Echo === 'function' && (!window.Echo || typeof window.Echo.channel !== 'function') && key) {
                try {
                    window.Echo = new Echo({ broadcaster: 'pusher', key: key, cluster: cluster || undefined, forceTLS: true });
                } catch (e) { console.warn('Lorapok: Echo instantiation failed', e); }
            }
        } catch (e) { console.warn('Lorapok: tryInitEcho error', e); }

        // Attempt to subscribe if possible
        return subscribeToChannel();
    }

    function loadScript(src, cb) {
        var s = document.createElement('script'); s.src = src; s.async = true; s.onload = cb; s.onerror = cb; document.head.appendChild(s);
    }

    // Try to subscribe now; if unavailable, load dependencies and try again
    if (!tryInitEcho()) {
        if (typeof Pusher === 'undefined') {
            loadScript('https://js.pusher.com/7.2/pusher.min.js', function () {
                loadScript('https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.0/echo.iife.min.js', function () {
                    tryInitEcho();
                });
            });
        } else {
            loadScript('https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.0/echo.iife.min.js', function () {
                tryInitEcho();
            });
        }
    }
})();
