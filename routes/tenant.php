<?php

declare(strict_types=1);

use App\Models\User;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantHomeController;
use App\Http\Controllers\App\ExcelImportController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;
use Laravel\Jetstream\Http\Controllers\CurrentTeamController;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Laravel\Jetstream\Http\Controllers\Livewire\TeamController;
use Laravel\Jetstream\Http\Controllers\TeamInvitationController;
use Laravel\Jetstream\Http\Controllers\Livewire\ApiTokenController;
use Laravel\Jetstream\Http\Controllers\Livewire\UserProfileController;
use Laravel\Jetstream\Http\Controllers\Livewire\PrivacyPolicyController;
use Laravel\Jetstream\Http\Controllers\Livewire\TermsOfServiceController;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/


Route::group(['prefix' => config('sanctum.prefix', 'sanctum')], static function () {
    Route::get('/csrf-cookie', [CsrfCookieController::class, 'show'])
        ->middleware([
            'web',
            'universal',
            InitializeTenancyByDomain::class // Use tenancy initialization middleware of your choice
        ])
        ->name('sanctum.csrf-cookie');
});

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    Route::get('/', function () {
        return redirect('/login');
    });

    Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified'
    ]
    )->group(function () {

        Route::get('/dashboard', [TenantHomeController::class, 'index'])->name('dashboard');

        
        
        //excelImport
        Route::resource('excelImport', ExcelImportController::class);
        //excelImport ledgers
        Route::get('excelImport/ledgers/create', [ExcelImportController::class, 'ledgerCreate'])->name('excelImport.ledgers.create');
        Route::post('excelImport/ledgers/import', [ExcelImportController::class,'ledgerImport'])->name('excelImport.ledgers.import');
        Route::get('excelImport/ledgers/show', [ExcelImportController::class,'ledgerShow'])->name('excelImport.ledgers.show');
        Route::delete('excelImport/ledgers/destroy/{id}', [ExcelImportController::class,'ledgerDestroy'])->name('excelImport.ledgers.destroy');
        Route::post('excelImport/ledgers/{id}', [ExcelImportController::class, 'ledgerInputStore'])->name('ledgers.input.store');





        //  JET STREAM
        require __DIR__ . '/jetstream.php';
    });

    Route::get('/test',function(){
        dd('tenant');
    });
});
