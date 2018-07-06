<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Homepage ROUTES
Route::group(['middleware' => 'fw-block-blacklisted'], function ()
{
    Route::get('/', 'PagesController@getHome');
});

// ACCESS DENIED ROUTES
Route::view('/access-denied', 'access_denied');

// Authenticated ROUTES
Auth::routes();

Route::get('/set-user', function (){
    return [
        'user_id'       => Auth::id(),
        'have_token'    => Auth::user() ? Auth::user()->isTokenHolder() : 0
        ];
});

// LOCALE ROUTES
Route::get('setlocale/{locale}', function ($locale) {
    if (in_array($locale, \Config::get('app.locales'))) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
});

Route::get('/activate-disabled/{token}', ['as' => 'authenticated.activate-disabled', 'uses' => 'Auth\ActivateController@activateSilently']);

/* PUBLIC ROUTES */
Route::group(['middleware' => ['web', 'activity', 'fw-block-blacklisted']], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    $s = 'public.';
    Route::get(
        '/beta', ['as' => $s . 'home', 'uses' => 'PagesController@getHome']
    );
    Route::get(
        '/about', ['as' => $s . 'home', 'uses' => 'PagesController@getAbout']
    );
    Route::get(
        '/contact',
        ['as' => $s . 'home', 'uses' => 'PagesController@getContact']
    );

    Route::post(
        '/contact', 'FormController@store_contact'
    ); // Added route for contact page to store data
    Route::post(
        '/ico/create', 'FormController@store_ico'
    ); // Added route for ico page to store data
    Route::get(
        '/ico/listing',
        ['as' => $s . 'home', 'uses' => 'PagesController@getICOListing']
    );
    Route::get(
        '/ico/{coin}',
        ['as' => $s . 'home', 'uses' => 'IcoController@getDetails']
    );


    Route::get('/community', 'CommunityController@index');

    Route::get('/suggestions','SuggestionController@index');
    Route::get('/suggestions/add','SuggestionController@add')->middleware('auth');
    Route::get('/suggestions/{title}','SuggestionController@show');
    Route::post('/suggestions','SuggestionController@store')->middleware('auth');
    Route::post('/suggestions/{id}/addComment','CommentController@store')->middleware('auth');
    Route::post('/suggestions/{id}/vote','SuggestionVoteController@voteUp')->middleware('auth');
    Route::put('/suggestions/{id}/status','SuggestionController@setStatus')->middleware('auth');
    Route::delete('/suggestions/{id}','SuggestionController@reject')->middleware('auth');
    Route::delete('/suggestions/{id}/vote','SuggestionVoteController@voteDown')->middleware('auth');

    Route::get('/getAndUpdateData', 'DataController@getAndUpdateData');
    Route::get('/top_market_cap', 'DataController@get_top_market_cap');
    Route::get('/top_market_cap_block', 'DataController@get_top_market_cap_block');

    Route::get('/whitepaper', 'PagesController@showWhitepaper');
    Route::get('/referral', 'PagesController@getReferral');
    Route::get('/tokensale', 'PagesController@getTokensale');

    Route::get('/terms', 'PagesController@getTerms');
    Route::get('/privacy', 'PagesController@getPrivacy');
    Route::get('/AMLKYC', 'PagesController@getAMLKYC');

    /* DEVELOPMENT ROUTES */
    Route::get('/dev/reftokens', 'DevController@getRefTokens');

    /* Activation routes */
    /* AUTH SOCIAL */
    Route::get('/social/redirect/facebook', 'SocialAuthController@redirect');
    Route::get('/social/callback/facebook', 'SocialAuthController@callback');
    // Activation Routes
    Route::get('/activate', ['as' => 'activate', 'uses' => 'Auth\ActivateController@initial']);
    Route::get('/activate/{token}', ['as' => 'authenticated.activate', 'uses' => 'Auth\ActivateController@activate']);
    Route::get('/activation', ['as' => 'authenticated.activation-resend', 'uses' => 'Auth\ActivateController@resend']);
    Route::get('/exceeded', ['as' => 'exceeded', 'uses' => 'Auth\ActivateController@exceeded']);
    // Deactivation Routes
    Route::get('/deactivate/{token}', ['as' => 'authenticated.deactivate', 'uses' => 'Auth\ActivateController@deactivate']);
    // Route to for user to reactivate their user deleted account.
    Route::get('/re-activate/{token}', ['as' => 'user.reactivate', 'uses' => 'RestoreUserController@userReActivate']);
    // 2-WAY AUTHY.COM ROUTES
    Route::get('auth/token','Auth\LoginController@getToken');
    Route::post('auth/token','Auth\LoginController@postToken');
    Route::get('auth/two-factor/setup/{step?}', 'Auth\TwoFactorController@setupTwoFactorAuth');
    Route::post('auth/two-factor/enable/{provider?}', 'Auth\TwoFactorController@enableTwoFactorAuth');
    Route::post('auth/two-factor/disable', 'Auth\TwoFactorController@disableTwoFactorAuth');

    //Disable 2FA authetication by email link
    Route::post('auth/two-factor/send-reset-email', 'Auth\TwoFactorController@sendResetEmail')->name('send-2fa-reset-email');

    //Disable 2FA authetication by email link
    Route::get('auth/two-factor/reset/{resetToken}', 'Auth\TwoFactorController@reset')->name('reset-2fa');

});


Route::group(['middleware' => ['auth', 'activity', 'fw-block-blacklisted']], function () {
    Route::get('/logout', 'Auth\LoginController@logout');
});

// Registered and Activated User Routes and is current user routes.
Route::group(['middleware' => ['auth', 'activated', 'activity', 'fw-block-blacklisted']], function () {

    //Route::get('/profile', 'ProfileController@index');
    Route::get(
        '/profile', ['as' => 'profile_update', 'uses' => 'ProfileController@update']
    );

    Route::get(
        '/profile/update', ['as' => 'profile_update', 'uses' => 'ProfileController@update']
    );

    Route::get(
        'profile/projects', ['as' => 'projects.list', 'uses' => 'IcoController@index']
    );

    Route::get('project/create', ['as' => 'project.create', 'uses' => 'IcoController@create']);

    Route::get('profile/projects/json', ['as' => 'projects.list.json', 'uses' => 'IcoController@listProjects']);
    Route::get('project/{id}/members', 'IcoController@listProjectMembers');

    Route::post(
        'profile/update',
        ['as' => 'profile_store', 'uses' => 'ProfileController@store']
    );

    Route::post(
        '/profile_files',
        ['as' => 'profile_files', 'uses' => 'ProfileController@profileFiles']
    );


    Route::get('profile/{username}', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@show',
    ]);



    Route::get(
        '/id-proof', ['as' => 'id-proof', 'uses' => 'ProfileController@idProof']
    );

    Route::get('profile/projects', ['as' => 'projects.list', 'uses' => 'IcoController@index']);
    Route::get('profile/{id}/projects', ['as' => 'profile.projects.list', 'uses' => 'IcoController@userProjects']);
    Route::get('project/create', ['as' => 'project.create', 'uses' => 'CommunityController@handleVue']);
    Route::post('project/store', ['as' => 'project.store', 'uses' => 'IcoController@store']);

    Route::get('profile/projects/json', ['as' => 'projects.list.json', 'uses' => 'IcoController@listProjects']);
    Route::get('project/{id}/members', 'IcoController@listProjectMembers');

    Route::post(
        'profile/update',
        ['as' => 'profile_store', 'uses' => 'ProfileController@store']
    );

    Route::post(
        '/profile_files',
        ['as' => 'profile_files', 'uses' => 'ProfileController@profileFiles']
    );

    Route::post('profile/{username}/like', [
        'uses' => 'ProfilesController@toggleLike',
    ])->name('profile.like');

    Route::post('profile/{username}/follow', [
        'uses' => 'ProfilesController@toggleFollow',
    ])->name('profile.follow');


    // Form to get early access
    Route::get('tokenholder/access', [
        'as' => 'tokenholderForm',
        'uses' => 'FormController@showEarlyAccess',
    ]);
    Route::post(
        'tokenholder/access',
        ['as' => 'tokenholder_store', 'uses' => 'FormController@storeEarlyAccess']
    );

    // Activation by user
    Route::get('/activation-required', ['uses' => 'Auth\ActivateController@activationRequired'])->name('activation-required');

    // EXCHANGE
    Route::get('/exchange/beta', ['uses' => 'ExchangeController@getExchangeBeta']);

});

//Route::get('/exchange/basic/next', ['uses' => 'ExchangeController@getExchangeNext']);
//Route::get('/exchange/basic/token/{symbol}', ['uses' => 'ExchangeController@getExchangeToken']);

// Tokenmarket and tokenexchange
Route::get('/tokenmarket', ['uses' => 'ExchangeController@getTokens']);
Route::get('/tokenmarket/{symbol}', ['uses' => 'ExchangeController@getExchangeToken']);


// Registered, activated and is USER routes.
Route::group(['middleware' => ['auth', 'activated', 'currentUser', 'activity', 'fw-block-blacklisted']], function () {
    // User Profile and Account Routes
    Route::resource(
        'profile',
        'ProfilesController', [
            'only' => [
                'show',
                'edit',
                'update',
                'create',
            ],
        ]
    );
    Route::put('profile/{username}/updateUserAccount', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@updateUserAccount',
    ]);
    Route::put('profile/{username}/updateUserPassword', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@updateUserPassword',
    ]);
    Route::delete('profile/{username}/deleteUserAccount', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@deleteUserAccount',
    ]);
    // Route to show user avatar
    Route::get('images/profile/{id}/avatar/{image}', [
        'uses' => 'ProfilesController@userProfileAvatar',
    ]);
    // Route to upload user avatar.
    Route::post('avatar/upload', ['as' => 'avatar.upload', 'uses' => 'ProfilesController@upload']);

    Route::post('articles/create', 'ArticleController@create')->name('article.create');

    /**
     * Articles api routes
     */
    Route::get('profile/{id}/articles', 'ArticleController@listUserArticles');
    Route::post('article/{article_id}/comments', 'ArticleController@postComment')
        ->middleware(['throttle:15,1']);
    Route::post('article/{article_id}/like', 'ArticleController@like')
        ->middleware(['throttle:10:1']);
});


// Registered, activated, and is ADMIN routes.
Route::group(['middleware' => ['auth', 'role:admin', 'activity', 'fw-block-blacklisted']], function () {
    Route::resource('/users/deleted', 'SoftDeletesController', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ]);
    Route::resource('users', 'UsersManagementController', [
        'names' => [
            'index'   => 'users',
            'destroy' => 'user.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::post('search-users', 'UsersManagementController@search')->name('search-users');

    // Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    // Route::get('php', 'AdminDetailsController@listPHPInfo');
    Route::get('routes', 'AdminDetailsController@listRoutes');
    Route::get('active-users', 'AdminDetailsController@activeUsers');
});


/* TEST ROUTES */
/* DASHBOARD */
$d = 'dashboard.';

Route::get('/set-echo-url', ['as' => $d.'dashboard', 'uses' => 'SettingsController@getEchoURL']);

// DEVELOPMENTS
//Route::get('test/ethereum', 'CryptoController@demo_ethereum');
//Route::get('test/electroneumd', 'CryptoController@demo_electroneumd');

/* PAYMENT AND CALLBACK ROUTES */
//Route::get('/bitcoin', ['as' => $d.'bitcoin', 'uses' => 'BitcoinController@getBitcoin']);
//Route::post('/bitcoin/transfer', ['as' => $d.'bitcoin', 'uses' => 'BitcoinController@postBitcoinTransfer']);
//Route::get('/bitcoin/balance', ['as' => $d.'bitcoin', 'uses' => 'BitcoinController@getBalance']);
//Route::get('/callback/bitcoin', ['as' => $d.'bitcoin', 'uses' => 'BitcoinController@callback']);

/* TO TEST ---- OLD DASHBOARD ---- CAN BE DELETED INCLUDING CONTROLLER */
//Route::get('/wallet', ['as' => $d.'wallet', 'uses' => 'AppController@getWallet']);
//Route::get('/tokens/buy/next', ['as' => $d.'wallet', 'uses' => 'AppController@buyTokens']);
//Route::get('/api', ['as' => $d.'wallet', 'uses' => 'AppController@showApi']);
//Route::get('/exchange', ['as' => $d.'wallet', 'uses' => 'AppController@showExchange']);
//Route::get('/messages', ['as' => $d.'wallet', 'uses' => 'AppController@showMessages']);

/* API */
//Route::get('/tasks', ['as' => $d.'tasks', 'uses' =>
// 'TaskController@showIndex']);

/* VUE DASHBOARD */
//Route::get('/markets', ['as' => $d.'tasks', 'uses' => 'AppController@getVueDashboard'])->middleware('fw-block-blacklisted');

Route::prefix('api')->group(function() {
    Route::resource('tasks', 'TaskController');
    Route::resource('wallet', 'WalletController');
    Route::get('init-wallet', 'WalletController@initWallet');
    Route::get('transactions','TransactionController@index');
    Route::get('user_list','UserController@user_list');
    Route::get('black_list','UserController@black_list');
    Route::get('whitelisted','UserController@whitelisted');
    Route::get('blacklisted','UserController@blacklisted');
    Route::get('currentUser','UserController@currentUser');

    Route::get('addr/{symbol}', 'CryptoController@getAddressNew');
    Route::post('crypto/withdraw', 'CryptoController@withdraw');
    Route::post('crypto/withdrawconfirm/{info}', 'CryptoController@withdrawConfirm');
    Route::post('crypto/transfer', 'CryptoController@transfer');

    //For getting the Access page listing
    Route::get('access', 'AccessController@index');
    Route::get('access-status', 'AccessController@access_status');

    Route::get('user/profile', 'UserController@getAuthenticatedUser');
    Route::get('user/profile/{id}', 'ProfilesController@getUserProfile');

    //Connections routes
    Route::put('user/connections/{id}/update', 'ProfileController@updateConnectionRequest');
    Route::post('user/{id}/connections/send', 'ProfileController@sendConnectionRequest');
    Route::get('user/connections', 'ProfileController@getConnections');

    //Private messages api routes
    Route::get('user/{conversation_id}/messages', 'PrivateMessagesController@fetchHistory');
    Route::post('user/{conversation_id}/messages', 'PrivateMessagesController@sendMessage');
    Route::get('user/conversations', 'PrivateMessagesController@listConversations');

    Route::post('user/{user_id}/conversation', 'ConversationController@create');

    //Token market
    Route::get('token-market', ['as' => 'market.data.get', 'uses' => 'CoinController@coinMarketData']);
});

Route::group(['middleware' => ['auth', 'activated', 'activity', 'fw-block-blacklisted']], function () {
    Route::get('exchangewalletdetails', 'ExchangeController@exchangewalletdetails')->middleware('auth');
    Route::post('saveorders', 'VueDashboardController@saveOrders')->middleware('auth');
    Route::post('exchange-orders', 'VueDashboardController@exchangeOrders')->middleware('auth');
    Route::get('/vue-dashboard/admin', 'VueDashboardController@admin')->middleware('auth');
    Route::get('/chartdata', 'VueDashboardController@getChartData')->middleware('auth');
    Route::post('/chartzoom', 'VueDashboardController@chartZoomed')->middleware('auth');
    Route::post('/vue-dashboard/fetch-wallet', 'VueDashboardController@initializeBuyBox')->middleware('auth');
    Route::post('/vue-dashboard/fetch-orders-executed', 'VueDashboardController@initializeSellBox')->middleware('auth');
    Route::get('/vue-dashboard/fetch-user-orders', 'VueDashboardController@ordersByUser')->middleware('auth');
    Route::post('/vue-dashboard/delete-order', 'VueDashboardController@deleteOrder')->middleware('auth');
    Route::get('/vue-dashboard/coins', 'VueDashboardController@getTransactionCoins')->middleware('auth');
    Route::get('/vue-dashboard/fetch-ids-symbols', 'VueDashboardController@getCoinsByStatusAndFiat');
    Route::post('/fetch-orders', 'VueDashboardController@fetchOrdersByCoins')->middleware('auth');
    Route::get('/fetch-user-transactions', 'VueDashboardController@transactionsByUser')->middleware('auth');
    Route::get('/fetch-order-history', 'VueDashboardController@getOrderHistory')->middleware('auth');
    Route::get('/fetch-coins', 'WalletController@getCoins');
    Route::get('/show-coin', 'WalletController@showCoin');
    Route::post('/create-update-wallet', 'WalletController@createUpdateWallet');

    Route::get('getCoindetails', 'CoinController@marketDataDisplay')->middleware('auth');
    Route::get('getCoindata/{id}', 'CoinController@viewCoin');
    Route::get('getCoindelete/{id}', 'CoinController@deleteCoin')->middleware('auth');
    Route::post('updateCoindetails/{id}', 'CoinController@updateCoindetails')->middleware('auth');
    Route::post('SaveCoindetails', 'CoinController@SaveCoindetails')->middleware('auth');
    Route::post('Storeimage', 'CoinController@Storeimage');
    Route::get('checkadmin', 'CoinController@checkadmin')->middleware('auth');
    Route::get('getPerCoinDetails', 'CoinController@marketPerCoinDataDisplay')->middleware('auth');
    Route::get('coins/search', 'CoinController@searchCoins');


    Route::get('token/list', 'TokenController@list')->middleware('auth');
    Route::get('token/get/{symbol}', 'TokenController@get')->middleware('auth');
    Route::get('token/rate', 'TokenController@rate')->middleware('auth');

    Route::get('getethadress', 'EthController@createwalletaddress');
    Route::post('/paypal', 'Paymentcontroller@paypal'); // In case of paypal
    Route::post('/creditcard-paypal', 'Paymentcontroller@creditCardPayPal'); // In case of paypal creditcard
    Route::post('/pay', 'Paymentcontroller@showForm');
    Route::post('/userdisclaimer', 'TransactionController@userdisclaimer');
    Route::post('/saveuserdisclaimer', 'TransactionController@saveuserdisclaimer');

    Route::get('paywithpaypal', array('as' => 'paywithpaypal', 'uses' => 'Paymentcontroller@payWithPaypal'));
    Route::post('paypal', array('as' => 'paypal', 'uses' => 'Paymentcontroller@postPaymentWithpaypal'));
    Route::get('paypalstatus', array('as' => 'status', 'uses' => 'Paymentcontroller@getPaymentStatus'));
    Route::post('paypal', array('as' => 'status', 'uses' => 'Paymentcontroller@paypal'));

    Route::get('getdetails', 'ProfileController@getDetails');
    Route::post('getRates', 'ExchangeController@getRates');
    Route::post('icowhitelist', 'ProfileController@icoWhitelist');

    /** ETN VUE */
    Route::get('coin-id', 'CoinController@coinIDByName')->middleware('auth');
    Route::get('etn-withdraw', 'ETNController@withDraw')->middleware('auth');
    Route::get('etn-address/{coin_id}', 'ETNController@getAddress')->middleware('auth');
    Route::get('my-etn-address', 'ETNController@myAddress')->middleware('auth');

    /** UPLOAD COIN IMAGE */
    Route::get('upload/image/coin', 'CoinController@uploadCoinImageForm')->middleware('auth');
    Route::post('upload/image/coin', 'CoinController@uploadCoinImage')->middleware('auth');

    Route::get(
        '/addr/{symbol}', 'CryptoController@getAddressNew'
    );

    /** TEST FOR DEVS */
    Route::get('myip', 'DevController@getIpAddress')->middleware('auth');

    /** CHAT FUNCTION */
    Route::prefix('chat')->group(function () {
        Route::get('/room/{name}', 'ChatController@getRoom');
        Route::post('/{room}/messages', 'ChatController@postMessage');
        Route::get('/{room}/messages', 'ChatController@getMessages');
    });

    /**
     * This routes is reserved for dashboard.
     * Since this application is running half blade & half vue
     * we need to make sure after reloading page we get same
     * output as if we click on vue-route. Very important
     * whenever you add new route to dashboard, define
     * respective route here.
     *
     * Do not use (.*) or similar wildcard!! since it will broke redirects.
     * This routes also helps laravel to manage redirects properly.
     */
    Route::get('/markets', 'ExchangeController@getDashboard')->middleware('auth');
    Route::get('/orderbook', 'ExchangeController@getDashboard')->middleware(['auth', 'checkistokenholder']);
    Route::get('/orderbook/{market}/{coin}', 'ExchangeController@getDashboard')->middleware(['auth', 'checkistokenholder']);
    Route::get('/wallet', 'ExchangeController@getDashboard')->middleware('auth');
    Route::get('/wallet/{info}', 'ExchangeController@getDashboard')->middleware('auth');
    Route::get('/transactions', 'TransactionController@getTransactionView')->middleware('auth');
    Route::get('/coin', 'ExchangeController@getDashboard')->middleware('auth');
    Route::get('/exchange', 'ExchangeController@getDashboard')->middleware('auth');
    Route::get('/user/settings', 'ExchangeController@getDashboard')->middleware('auth');
    Route::get('/change-password', 'ExchangeController@getDashboard')->middleware('auth');
    Route::get('/black_list', 'ExchangeController@getDashboard')->middleware('auth');
    Route::get('/tokenexchange/{symbol}', 'ExchangeController@getDashboard')->middleware('auth');
    Route::get('/ico', 'ExchangeController@getDashboard')->middleware('auth');
    Route::get('/minepool', 'ExchangeController@getDashboard')->middleware('auth');
    Route::get('/access', 'ExchangeController@getDashboard')->middleware('auth');
    Route::get('/coin-details/{market?}/{coin?}', 'ExchangeController@getDashboard')->middleware('auth');

});


/* TEST SOCKET ROUTES */

Route::get('fire', function () {
    // this fires the event
    event(new App\Events\Coinupdate());
    return "event fired";
});






