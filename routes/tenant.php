<?php

declare(strict_types=1);

use App\Models\User;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\Gstr1Controller;
use App\Http\Controllers\TenantHomeController;
use App\Http\Controllers\App\CompanyController;
use App\Http\Controllers\App\GstAuthController;
use App\Http\Controllers\App\DebitNoteController;
use App\Http\Controllers\App\CreditNoteController;
use App\Http\Controllers\App\JsonImportController;
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
  
        Route::get('excelImport/ledgers/create', [ExcelImportController::class, 'ledgerCreate'])->name('excelImport.ledgers.create');
        Route::post('excelImport/ledgers/import', [ExcelImportController::class,'ledgerImport'])->name('excelImport.ledgers.import');
        Route::get('excelImport/ledgers/show', [ExcelImportController::class,'ledgerShow'])->name('excelImport.ledgers.show');
        Route::delete('excelImport/ledgers/destroy/{id}', [ExcelImportController::class,'ledgerDestroy'])->name('excelImport.ledgers.destroy');
        Route::post('excelImport/ledgers/{id}', [ExcelImportController::class, 'ledgerInputStore'])->name('ledgers.input.store');

        Route::get('excelImport/items/create', [ExcelImportController::class, 'itemCreate'])->name('excelImport.items.create');
        Route::post('excelImport/items/import', [ExcelImportController::class,'itemImport'])->name('excelImport.items.import');
        Route::get('excelImport/items/show', [ExcelImportController::class,'itemShow'])->name('excelImport.items.show');
        Route::delete('excelImport/items/destroy/{id}', [ExcelImportController::class,'itemDestroy'])->name('excelImport.items.destroy');
        Route::post('excelImport/items/{id}', [ExcelImportController::class, 'itemInputStore'])->name('items.input.store');
    
        Route::get('excelImport/sales/create', [ExcelImportController::class, 'saleCreate'])->name('excelImport.sales.create');
        Route::post('excelImport/sales/import', [ExcelImportController::class,'saleImport'])->name('excelImport.sales.import');
        Route::get('excelImport/sales/show', [ExcelImportController::class,'saleShow'])->name('excelImport.sales.show');

        Route::post('excelImport/salespurchase/{id}', [ExcelImportController::class, 'salespurchaseInputStore'])->name('salespurchase.input.store');
        Route::delete('excelImport/salespurchase/destroy/{id}', [ExcelImportController::class,'salespurchaseDestroy'])->name('excelImport.salespurchase.destroy');
    
        Route::get('excelImport/purchase/create', [ExcelImportController::class, 'purchaseCreate'])->name('excelImport.purchase.create');
        Route::post('excelImport/purchase/import', [ExcelImportController::class,'purchaseImport'])->name('excelImport.purchase.import');
        Route::get('excelImport/purchase/show', [ExcelImportController::class,'purchaseShow'])->name('excelImport.purchase.show');
    
        Route::get('excelImport/bank/create', [ExcelImportController::class, 'bankCreate'])->name('excelImport.bank.create');
        Route::post('excelImport/bank/import', [ExcelImportController::class,'bankImport'])->name('excelImport.bank.import');
        Route::get('excelImport/bank/show', [ExcelImportController::class,'bankShow'])->name('excelImport.bank.show');
    
        Route::get('excelImport/receipt/create', [ExcelImportController::class, 'receiptCreate'])->name('excelImport.receipt.create');
        Route::post('excelImport/receipt/import', [ExcelImportController::class,'receiptImport'])->name('excelImport.receipt.import');
        Route::get('excelImport/receipt/show', [ExcelImportController::class,'receiptShow'])->name('excelImport.receipt.show');
    
        Route::get('excelImport/payment/create', [ExcelImportController::class, 'paymentCreate'])->name('excelImport.payment.create');
        Route::post('excelImport/payment/import', [ExcelImportController::class,'paymentImport'])->name('excelImport.payment.import');
        Route::get('excelImport/payment/show', [ExcelImportController::class,'paymentShow'])->name('excelImport.payment.show');
    
        Route::post('excelImport/bankReceiptPayment/{id}', [ExcelImportController::class, 'bankReceiptPaymentInputStore'])->name('bankReceiptPayment.input.store');
        Route::delete('excelImport/bankReceiptPayment/destroy/{id}', [ExcelImportController::class,'bankReceiptPaymentDestroy'])->name('excelImport.bankReceiptPayment.destroy');
    
        Route::get('excelImport/journal/create', [ExcelImportController::class, 'journalCreate'])->name('excelImport.journal.create');
        Route::post('excelImport/journal/import', [ExcelImportController::class,'journalImport'])->name('excelImport.journal.import');
        Route::get('excelImport/journal/show', [ExcelImportController::class,'journalShow'])->name('excelImport.journal.show');
        Route::post('excelImport/journal/{id}', [ExcelImportController::class, 'journalInputStore'])->name('journal.input.store');
       
        //jsonImport
        Route::resource('jsonImport', JsonImportController::class);
        Route::get('jsonImport/items/show', [JsonImportController::class,'itemShow'])->name('jsonImport.items.show');
        Route::get('jsonImport/sales/show', [JsonImportController::class,'saleShow'])->name('jsonImport.sales.show');
        Route::get('jsonImport/purchase/show', [JsonImportController::class,'purchaseShow'])->name('jsonImport.purchase.show');
        Route::get('jsonImport/bank/show', [JsonImportController::class,'bankShow'])->name('jsonImport.bank.show');
        Route::get('jsonImport/receipt/show', [JsonImportController::class,'receiptShow'])->name('jsonImport.receipt.show');
        Route::get('jsonImport/payment/show', [JsonImportController::class,'paymentShow'])->name('jsonImport.payment.show');
        Route::get('jsonImport/journal/show', [JsonImportController::class,'journalShow'])->name('jsonImport.journal.show');
    
        Route::resource('companies', CompanyController::class);

        //gstr1
        Route::resource('gstr1', Gstr1Controller::class);
        Route::get('gstr1/b2b/show', [Gstr1Controller::class, 'b2bData'])->name('gstr1.b2b');
        Route::get('gstr1/b2cl/show', [Gstr1Controller::class, 'b2clData'])->name('gstr1.b2cl');
        Route::get('gstr1/b2cs/show', [Gstr1Controller::class, 'b2csData'])->name('gstr1.b2cs');
        Route::get('gstr1/cdnr/show', [Gstr1Controller::class, 'cdnrData'])->name('gstr1.cdnr');
        Route::get('gstr1/cdnur/show', [Gstr1Controller::class, 'cdnurData'])->name('gstr1.cdnur');
        Route::get('gstr1/exp/show', [Gstr1Controller::class, 'expData'])->name('gstr1.exp');
        Route::get('gstr1/nil/show', [Gstr1Controller::class, 'nilData'])->name('gstr1.nil');

        Route::get('gstAuthData', [Gstr1Controller::class, 'gstAuthData'])->name('gstAuthData');
        Route::post('gstr1/connectToGST/getData', [Gstr1Controller::class, 'connectToGSTgetData'])->name('gstr1.connectToGST.getData');
        Route::post('gstr1/connectToGST/otpRequest', [Gstr1Controller::class, 'otpRequest'])->name('gstr1.connectToGST.otpRequest');
        Route::post('gstr1/connectToGST/otpVerify', [Gstr1Controller::class, 'otpVerify'])->name('gstr1.connectToGST.otpVerify');


        //note
        Route::resource('credit-note', CreditNoteController::class);
        Route::post('credit-note/import', [CreditNoteController::class, 'creditNoteImport'])->name('credit-note.import');
        Route::get('credit-note/data/show', [CreditNoteController::class, 'creditNoteShow'])->name('credit-note.show');
        Route::delete('credit-note/destroy/{id}', [CreditNoteController::class,'creditNoteDestroy'])->name('credit-note.destroy');
        Route::resource('debit-note', DebitNoteController::class);
        Route::post('debit-note/import', [DebitNoteController::class, 'debitNoteImport'])->name('debit-note.import');
        Route::get('debit-note/data/show', [DebitNoteController::class, 'debitNoteShow'])->name('debit-note.show');
        Route::delete('debit-note/destroy/{id}', [DebitNoteController::class,'debitNoteDestroy'])->name('debit-note.destroy');
        

        Route::resource('gstAuth', GstAuthController::class);
        Route::post('gstAuth/connectToGST/getData', [GstAuthController::class, 'connectToGSTgetData'])->name('gstAuth.connectToGST.getData');
        Route::post('gstAuth/connectToGST/otpRequest', [GstAuthController::class, 'otpRequest'])->name('gstAuth.connectToGST.otpRequest');
        Route::post('gstAuth/connectToGST/otpVerify', [GstAuthController::class, 'otpVerify'])->name('gstAuth.connectToGST.otpVerify');

        Route::get('gstAuth/b2b', [Gstr1Controller::class, 'gstAuthb2bData'])->name('gstAuth.B2B');


        //  JET STREAM
        require __DIR__ . '/jetstream.php';
    });

    Route::get('/test',function(){
        dd('tenant');
    });
});
