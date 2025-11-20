<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});
Route::get('/test', function(){
    return view('test');
});
Route::get('/mobile-legend', function(){
    return view('mobile-legend');
});
Route::get('/admin/dashboard', function(){
    return view('admin.dashboard');
});
