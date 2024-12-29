<?php

use App\Http\Controllers\PDFController;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return redirect()->to('/access');
});

Route::post('/send-pdf', [PDFController::class, 'uploadAndSendPDF'])->name('send-pdf');
Route::get('/pdf', [PDFController::class, 'index'])->name('pdf');

Route::get('/rancang', function () {
    $images = Report::first()->images;
    return view('pdf.images', compact('images'));
});