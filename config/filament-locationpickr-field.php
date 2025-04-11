<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Plugin Options
    |--------------------------------------------------------------------------
    */
    'key' => env('GOOGLE_API_KEY', ''),

    'default_location' => [
        'lat' => -6.179079,
        'lng' => 106.826929,
    ],

    'default_zoom' => 8,

    'default_draggable' => true,

    'default_clickable' => true,

    'default_height' => '400px',

    'my_location_button' => 'Location',
];
