<?php

use App\Http\Controllers\PDFController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/send-pdf', [PDFController::class, 'uploadAndSendPDF'])->name('send-pdf');
Route::get('/pdf', [PDFController::class, 'index'])->name('pdf');

Route::get('/oauth/callback/google/test', function () {
    $google = Socialite::driver('google')->user();
    $user = User::updateOrCreate([
        'github_id' => $google->id,
    ], [
        'name' => $google->name,
        'email' => $google->email,
        'github_token' => $google->token,
        'github_refresh_token' => $google->refreshToken,
    ]);

    Auth::login($user);
    return redirect()->to('/access');
});