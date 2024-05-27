<?php

return [
    'enabled' => env('PARTYTOWN_ENABLE', false),
    /**
     * Some scripts dont have their cors headers set correctly.
     * By adding their domains here they will be proxied and have correct CORS headers
     * see: https://partytown.builder.io/proxying-requests
     */
    'domain_whitelist' => array_merge(
        array_filter(explode(',', env('PARTYTOWN_DOMAIN_WHITELIST', ''))),
        [
            // Google
            'googletagmanager.com',
            'www.googletagmanager.com',
            'www.google-analytics.com',
            'www.googleadservices.com',
            'googleads.g.doubleclick.net',
            'pagead2.googlesyndication.com',
            // Facebook
            'connect.facebook.net',
            // Linkedin
            'snap.licdn.com',
            // Pinterest
            'ct.pinterest.com',
            's.pinimg.com',
            // Bing
            'bat.bing.com',
            // Tiktok
            'analytics.tiktok.com',
            // Hotjar
            'vars.hotjar.com',
            // Klaviyo
            "static.klaviyo.com",
            "static-tracking.klaviyo.com",
        ]
    ),
    /**
     * Sending data from outside of partytown to partytown must be possible.
     * Here you can define which functions should be forwarded
     * see: https://partytown.builder.io/forwarding-events
     */
    'forward_functions' => array_merge(
        array_filter(explode(',', env('PARTYTOWN_FORWARD_FUNCTIONS', ''))),
        [
            'dataLayer.push' => ['preserveBehavior' => true],
            // 'fbq'
        ]
    ),

    /**
     * Sometimes GTM loads frontend facing scripts (like cookiebot and chatbots)
     * These should be added here so they're pushed to the frontend instead
     * see: https://partytown.builder.io/configuration#:~:text=is%20/~partytown/-,loadScriptsOnMainThread,-An%20array%20of
     */
    'load_scripts_on_main_thread' => array_merge(
        array_filter(explode(',', env('PARTYTOWN_LOAD_SCRIPTS_ON_MAIN_THREAD', ''))),
        [
            // Cookiebot
            '/consent\.cookiebot\.com/',
            'https://cmp.justbetter.nl/cmp.js',
        ]
    ),
];
