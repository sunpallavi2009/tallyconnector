<?php

use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TenantController;
use Laravel\Jetstream\Http\Controllers\CurrentTeamController;
use Laravel\Jetstream\Http\Controllers\Inertia\TeamController;
use Laravel\Jetstream\Http\Controllers\TeamInvitationController;
use Laravel\Jetstream\Http\Controllers\Inertia\ApiTokenController;
use Laravel\Jetstream\Http\Controllers\Inertia\UserProfileController;

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


// Route::group(['prefix' => config('sanctum.prefix', 'sanctum')], static function () {
//     Route::get('/csrf-cookie', [CsrfCookieController::class, 'show'])
//         ->middleware([
//             'web',
//             InitializeTenancyByDomain::class // Use tenancy initialization middleware of your choice
//         ])->name('sanctum.csrf-cookie');
// });

Route::get('/home',function(){
    return view('welcome');
});

Route::get('/',function(){
  return redirect('/login');
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
]
)->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // });
    // ->name('dashboard');

    Route::get('/dashboard', [HomeController::class, 'index']);
    
    Route::resource('tenants', TenantController::class);

    //  JET STREAM
    require __DIR__ . '/auth.php';
});


