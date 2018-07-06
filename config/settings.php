<?php

return [

    /*
     * Is email activation required
     */
    'activation' => env('ACTIVATION', true),

    /*
     * Is email activation required
     */
    'timePeriod' => env('ACTIVATION_LIMIT_TIME_PERIOD', 24),

    /*
     * Is email activation required
     */
    'maxAttempts' => env('ACTIVATION_LIMIT_MAX_ATTEMPTS', 3),

    /*
     * NULL Ip to enter to match database schema
     */
    'nullIpAddress' => env('NULL_IP_ADDRESS', '0.0.0.0'),

    /*
     * User list pagination size
     */
    'userListPaginationSize' => env('USER_LIST_PAGINATION_SIZE', 50),

    /*
     * User restore encryption type
     */
    'restoreUserEncType' => 'AES-256-ECB',

    /*
     * User restore days past cutoff
     */
    'restoreUserCutoff' => env('USER_RESTORE_CUTOFF_DAYS', 31),

    /*
     * User restore encryption key
     */
    'restoreKey' => env('USER_RESTORE_ENCRYPTION_KEY', 'sup3rS3juki@35t0r3K3y21!'),

    /*
     * ReCaptcha Status
     */
    'reCaptchStatus' => env('ENABLE_RECAPTCHA', true),

    /*
     * Google Maps API V3 Status
     */
    'googleMapsAPIStatus' => env('GOOGLEMAPS_API_STATUS', false),

];