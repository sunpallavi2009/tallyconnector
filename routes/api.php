<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\JsonImportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/ledgers/{token_id}/{company_id}', [JsonImportController::class, 'ledgerJsonImport'])
    ->name('jsonImport.ledgers.import')
    ->middleware('tenant');


// Route::post('/ledgers/{token_id}/{company_id}', [JsonImportController::class, 'ledgerJsonImport'])->name('jsonImport.ledgers.import');
Route::post('/items', [JsonImportController::class, 'itemJsonImport'])->name('jsonImport.items.import');
Route::post('/sales', [JsonImportController::class, 'saleJsonImport'])->name('jsonImport.sales.import');
Route::post('/purchase', [JsonImportController::class, 'purchaseJsonImport'])->name('jsonImport.purchase.import');
Route::post('/bank', [JsonImportController::class, 'bankJsonImport'])->name('jsonImport.bank.import');
Route::post('/receipt', [JsonImportController::class, 'receiptJsonImport'])->name('jsonImport.receipt.import');
Route::post('/payment', [JsonImportController::class, 'paymentJsonImport'])->name('jsonImport.payment.import');
Route::post('/journal', [JsonImportController::class, 'journalJsonImport'])->name('jsonImport.journal.import');