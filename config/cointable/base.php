<?php

return [

    'seo' => [
        'name' => 'CoinTable',
        'url' => 'http://your.domain',
        'title' => 'CoinTable - Full CryptoCurrency Market',
        'description' => 'Get all real-time cryptocurrencies market info',
        'keywords' => 'cryptocurrency,bitcoin,ethereum,dash,litecoin,monero',
    ],

    'layout' => [
        'theme' => 'blue',
        'columns' => 'name,price,market_cap,available_supply,volume_24h,change_1h,change_24h,change_7d',
        'orderby' => 'market_cap'
    ],

    'social' => [
        'facebook' => 'https://facebook.com/your_page',
        'twitter' => 'https://twitter.com/your_page',
        'linkedin' => 'https://linkedin.com/in/your_page',
        'google_plus' => 'https://plus.google.com/your_page',
        'youtube' => null,
        'instagram' => null,
        'pinterest' => null,
        'tumblr' => null,
        'reddit' => null,
        'github' => null,
        'stackoverflow' => null,
        'codepen' => null,
        'dribble' => null,
        'flickr' => null
    ],

    'custom_links' => [
        'Your Link #1' => 'http://example.link',
        'Your Link #2' => 'http://example.link',
        'Your Link #3' => 'http://example.link',
        'Your Link #4' => 'http://example.link'
    ],

    'ads' => [
        'top' => false,
        'bottom' => false
    ],

    'donation' => [
        'show' => true,
        'title' => 'Donation Title',
        'subtitle' => 'Write here your donation subtitle',
        'message' => 'Write here your donation message',
        'addresses' => [
            'Bitcoin' => 'your bitcoin address',
            'Ethereum' => 'your ethereum address',
            'Ripple' => 'your ripple address',
            'Litecoin' => 'your litecoin address'
        ]
    ]

];