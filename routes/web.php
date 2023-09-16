<?php

use Illuminate\Support\Facades\Route;

Route::get('/{all}', function () {
    return view('index');
})->where("all", ".*");
