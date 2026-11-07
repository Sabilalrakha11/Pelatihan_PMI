<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BranchMapController;

Route::get('/peta', [BranchMapController::class, 'index']);

Route::get('/', function () {
    return view('welcome');
});
