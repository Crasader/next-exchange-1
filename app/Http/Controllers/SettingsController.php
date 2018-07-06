<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function getEchoURL() {

        return env('LARAVEL_ECHO', null);
    }
}
