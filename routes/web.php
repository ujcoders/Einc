<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientDataController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\ClientRecordsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [ClientDataController::class, 'index'])->name('dashboard');
    Route::get('/file-upload', [FileUploadController::class, 'show'])->name('file-upload.show');
    Route::post('/file-upload', [FileUploadController::class, 'upload'])->name('file.upload.post');
    Route::get('/client-records', [ClientRecordsController::class, 'index'])->name('clientrecords.index');
    Route::get('/client-records/data', [ClientRecordsController::class, 'data'])->name('clientrecords.data');

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/data', [ProductController::class, 'data'])->name('data');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/store', [ProductController::class, 'store'])->name('store');
        Route::get('/{id}/dashboard', [ProductController::class, 'show'])->name('dashboard');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');           // Correct here
        Route::put('/{id}', [ProductController::class, 'update'])->name('update');            // Correct here
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');      // Correct here
        Route::post('/{product}/send-emails', [ProductController::class, 'sendEmails'])->name('sendEmails');
        Route::get('/{id}/campaign-report', [ProductController::class, 'campaignReport'])->name('campaign_report');

    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';



// Route::get('/register', function () {
//     return redirect('/login');
// })->name('register');

// Route::post('/register', function () {
//     abort(403, 'Registration disabled');
// });
