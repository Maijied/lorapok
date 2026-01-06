// Lorapok monitor-listener.js
(function(){
    function initEcho(key, cluster) {
        try {
            if (typeof Echo !== 'undefined') {
                Echo.channel('execution-monitor').listen('.performance-alert', (e) => {
                    console.log('Lorapok: Performance Alert received', e);
                    // Dispatch a DOM event so the widget (or any listener) can react
                    try {
                        window.dispatchEvent(new CustomEvent('lorapok:performance-alert', { detail: e }));
                    } catch(err) { console.warn('Lorapok: dispatch error', err); }
                });
            }
        } catch(e){ console.warn('Lorapok initEcho error', e); }
    }

    function loadScript(src, cb){
        var s = document.createElement('script'); s.src = src; s.async = true; s.onload = cb; s.onerror = cb; document.head.appendChild(s);
    }

    if (typeof Echo !== 'undefined') {
        initEcho();
    } else {
        if (typeof Pusher === 'undefined') {
            loadScript('https://js.pusher.com/7.2/pusher.min.js', function(){
                loadScript('https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.0/echo.iife.min.js', function(){
                    try{ if (typeof Echo !== 'undefined') initEcho(); } catch(e){}
                });
            });
        } else {
            loadScript('https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.0/echo.iife.min.js', function(){
                try{ if (typeof Echo !== 'undefined') initEcho(); } catch(e){}
            });
        }
    }
})();
