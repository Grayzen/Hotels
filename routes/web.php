<?php

use App\Http\Controllers\HotelController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return redirect()->route('hotels.index');
})->middleware(['auth'])->name('dashboard');

Route::resource('hotels', HotelController::class)->middleware(['auth']);
Route::post('hotels/rent', [HotelController::class, 'rent'])->name('hotels.rent')->middleware(['auth']);

require __DIR__ . '/auth.php';
