### About Next-Exchange

Next-Exchange is the Laravel PHP Based web application, providing an exchange platform to deal with Crypto Currencies.

[![Build Status](https://travis-ci.org/jeremykenedy/laravel-auth.svg?branch=master)](https://travis-ci.org/jeremykenedy/laravel-auth)
[![StyleCI](https://styleci.io/repos/44714043/shield?branch=master)](https://styleci.io/repos/44714043)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jeremykenedy/laravel-auth/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jeremykenedy/laravel-auth/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/jeremykenedy/laravel-auth/badges/build.png?b=master)](https://scrutinizer-ci.com/g/jeremykenedy/laravel-auth/build-status/master)
[![License](http://jeremykenedy.com/license-mit.svg)](https://raw.githubusercontent.com/jeremykenedy/laravel-auth/LICENSE)


## Installation instructions for developers
#### Configure your .env file for Laravel
````
# cp .env.example .env
# vi .env
````

## Check the .env 
````
COIN_NODE_SERVER=http://163.172.253.254:3000
LARAVEL_ECHO=https://static.next.exchange:6001
````

#### Install the Laravel packages
````
# composer update
````
#### Install the Bower packages (frontend)
Update the bower packages
````
# bower update
````
#### Install the NodeJS 8 and NPM 5.6 packages (backend)
````
# sudo apt-get install python-software-properties
# curl -sL https://deb.nodesource.com/setup_8.x | sudo -E bash -
# sudo apt-get install nodejs
# sudo apt-get install build-essential
````
#### Install Laravel Echo server (broadcasting)
````
# npm install -g laravel-echo-server
# laravel-echo-server init
# laravel-echo-server start
````
#### Installing the cronjob (tasks)
````
# crontab -e
````
Put this content in the crontab file:
````
* * * * * /usr/bin/php /app/www/laravel/artisan schedule:run >> /dev/null 2>&1
````
#### Migrate the database (MYSQL)
````
# php artisan migrate
````
#### Seed the database (MYSQL)
````
# php artisan db:seed
````
#### Compile the application
````
# npm run dev
````
#### Generate the data.json file
````
# php artisan NextExchange:updatecryptocap
````

#### Use NPM Decimal for all javascript number calculations
````
# Refer link https://www.npmjs.com/package/decimal for tutorials.
````


### Features
#### A [Laravel](http://laravel.com/) 5.6.x with minimal [Bootstrap](http://getbootstrap.com) 4.0.x project.

| NEXT Features  |
| :------------ |
|Built on [Laravel](http://laravel.com/) 5.6|
|Secured with [Laravel Firewall](https://github.com/antonioribeiro/firewall) 2.1|
|Secured for XSS inputs [Laravel Security](https://github.com/GrahamCampbell/Laravel-Security) 5.0|
|Uses [MySQL](https://github.com/mysql) Database|
|Uses [Artisan](http://laravel.com/docs/5.6/artisan) to manage database migration, schema creations, and create/publish page controller templates|
|Dependencies are managed with [COMPOSER](https://getcomposer.org/)|
|Laravel Scaffolding **User** and **Administrator Authentication**.|
|Interactive [charts](http://www.chartjs.org/docs/latest/)|
|User [Socialite Logins](https://github.com/laravel/socialite) ready to go - See API list used below|
|[Google Maps API v3](https://developers.google.com/maps/documentation/javascript/) for User Location lookup and Geocoding|
|CRUD (Create, Read, Update, Delete) User Management|
|Robust [Laravel Logging](https://laravel.com/docs/5.5/errors#logging) with admin UI using MonoLog|
|Google [reCaptcha Protection with Google API](https://developers.google.com/recaptcha/)|
|User Registration with email verification|
|Makes us of Laravel [Mix](https://laravel.com/docs/5.5/mix) to compile assets|
|Makes use of [Language Localization Files](https://laravel.com/docs/5.5/localization)|
|Active Nav states using [Laravel Requests](https://laravel.com/docs/5.5/requests)|
|Restrict User Email Activation Attempts|
|Capture IP to users table upon signup|
|Uses [Laravel Debugger](https://github.com/barryvdh/laravel-debugbar) for development|
|Makes us of [Password Strength Meter](https://github.com/elboletaire/password-strength-meter)|
|Makes use of [hideShowPassword](https://github.com/cloudfour/hideShowPassword)|
|User Avatar Image AJAX Upload with [Dropzone.js](http://www.dropzonejs.com/#configuration)|
|User Gravatar using [Gravatar API](https://github.com/creativeorange/gravatar)|
|User Password Reset via Email Token|
|User Login with remember password|
|User [Roles/ACL Implementation](https://github.com/jeremykenedy/laravel-roles)|
|Makes of [Laravel's Soft Delete Structure](https://laravel.com/docs/5.5/eloquent#soft-deleting)|
|Soft Deleted Users Management System|
|Permanently Delete Soft Deleted Users|
|User Delete Account with Goodbye email|
|User Restore Deleted Account Token|
|Restore Soft Deleted Users|
|View Soft Deleted Users|
|Captures Soft Delete Date|
|Captures Soft Delete IP|
|Admin Routing Details UI|
|Admin PHP Information UI|
|Eloquent user profiles|
|User Themes|
|404 Page|
|403 Page|
|Configurable Email Notification via [Laravel-Exception-Notifier](https://github.com/jeremykenedy/laravel-exception-notifier)|
|User Delete with Goodby email|
|User Restore Deleted Account|
|Activity Logging using [Laravel-logger](https://github.com/jeremykenedy/laravel-logger)|

#### Sources

https://github.com/Warchiefs/stock_exchange_integration
http://codemirror.net/index.html

#### Concurrents

https://bter.com
https://duo.com/pricing/duo-free (see Intersango)
https://satoshibox.com
https://nanopool.org
https://currencio.co/btc/usd/

#### Promotions
https://icobench.com/ico/blockbid
http://theicodaily.com/blockbid-announces-launch-of-ico-backed-by-first-of-its-kind-multi-cryptocurrency-trading-platform/
https://www.producthunt.com/posts/blockbid
https://icobazaar.com/blockbid
http://www.digitaljournal.com/pr/3524450
https://cryptocanucks.com/ico/Blockbid/
https://icowatchlist.com/ico/blockbid
http://ico.newsbtc.com/listing/blockbid/
https://bl4nkcode.info/en/ico_description/84/BID
https://tokentops.com/ico/blockbid/
https://topicolist.com/ico/blockbid
https://www.coinspeaker.com/2017/10/19/blockbid-launches-token-sale-backed-first-kind-multi-cryptocurrency-trading-platform/
http://crobitcoin.com/ultra-siguran-sustav-zatrgovanje-kriptovalutama-blockbid/
https://www.coinschedule.com/icos/e1328/blockbid-ico.html
http://coinaffairs.com/2017/10/18/blockbid-announces-launch-of-ico-backed-by-first-of-its-kind-multi-cryptocurrency-trading-platform/
http://allcrypto.life/news/blockbid-announces-launch-of-ico-backed-by-multi-cryptocurrency-trading-platform/
http://icofinder.com/index.php/past/
