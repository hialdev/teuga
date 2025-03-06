<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AJAXController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\MasterAccountController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PurchaseOrderInvoiceController;
use App\Http\Controllers\PurchaseOrderPaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RequestOrderInvoiceController;
use App\Http\Controllers\RequestOrderPaymentController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionPeriodController;
use App\Http\Controllers\TransportInvoiceController;
use App\Http\Controllers\TransportPaymentController;
use App\Models\Transport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

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

// Route::get('/', function(){
//     return redirect()->route('home');
// });
Route::get('/app.css', function () {
    $theme = config('al.theme'); // Memuat konfigurasi tema
    return response()
        ->view('styles.app', ['theme' => $theme])
        ->header('Content-Type', 'text/css');
});

// -------------- Auth Routes ----------------
Route::get('/login', function () {
    Session::put('prev_url', env('APP_URL'));
    return redirect()->to(urlApp('ACC', '/login'));
})->name('login');
Route::get('/register', function () {
    Session::put('prev_url', env('APP_URL'));
    return redirect()->to(urlApp('ACC', '/register'));
})->name('register');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//Auth::routes();
// -------------- End Auth Routes ----------------

// -------------- Unauthenticated routes ------------------

Route::middleware(['auth', 'check.app.permission'])->group(function(){
    // --------------------------------
    // AJAX
    // --------------------------------
    Route::post('/ajax/showBy', [AJAXController::class, 'showBy'])->name('ajax.showBy');
    Route::post('/ajax/showRelation', [AJAXController::class, 'showRelation'])->name('ajax.showRelation');


    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
    
    Route::get('/bank',[BankController::class, 'index'])->name('bank.index');
    Route::get('/bank/add',[BankController::class, 'add'])->name('bank.add');
    Route::post('/bank/store',[BankController::class, 'store'])->name('bank.store');
    Route::get('/bank/{id}/edit',[BankController::class, 'edit'])->name('bank.edit');
    Route::post('/bank/{id}/update',[BankController::class, 'update'])->name('bank.update');
    Route::delete('/bank/{id}/destroy',[BankController::class, 'destroy'])->name('bank.destroy');

    Route::get('/request-order/{id}/setting', function($id){
            return redirect()->away(urlApp('OSN', '/request-order/'.$id.'/setting'));
        })->name('request-order.setting');

    Route::get('/purchase-order/{id}/setting', function($id){
            return redirect()->away(urlApp('OSN', '/purchase-order/'.$id.'/setting'));
        })->name('purchase-order.setting');

    Route::get('/transport/{id}/setting', function($id){
            $transport = Transport::find($id);
            return redirect()->away(urlApp('OSN', '/transport?search='.$transport->code));
        })->name('transport.setting');

    Route::get('/transport', function(Request $request){
            return redirect()->away(urlApp('OSN', '/transport?search='.$request->get('search')));
        })->name('transport.index');

    Route::get('/principal/{id}/setting', function($id){
            return redirect()->away(urlApp('OSN', '/principal/'.$id.'/setting'));
        })->name('principal.setting');
    
    Route::get('/logistic/{id}/setting', function($id){
            return redirect()->away(urlApp('OSN', '/logistic/'.$id.'/setting'));
        })->name('logistic.setting');
    
    Route::get('/client/{id}/setting', function($id){
            return redirect()->away(urlApp('OSN', '/client/'.$id.'/setting'));
        })->name('client.setting');

    Route::get('/invoice/purchase-order',[PurchaseOrderInvoiceController::class, 'index'])->name('purchase-order.invoice');
    Route::post('/invoice/purchase-order',[PurchaseOrderInvoiceController::class, 'store'])->name('purchase-order.invoice.store');
    Route::post('/invoice/purchase-order/generate',[PurchaseOrderInvoiceController::class, 'generateTrx'])->name('purchase-order.invoice.generateTrx');
    Route::get('/invoice/purchase-order/{id}',[PurchaseOrderInvoiceController::class, 'show'])->name('purchase-order.invoice.show');
    Route::delete('/invoice/purchase-order/{id}/destroy',[PurchaseOrderInvoiceController::class, 'destroy'])->name('purchase-order.invoice.destroy');
    Route::post('/invoice/purchase-order/{id}/payment/{pay_id}/generate',[PurchaseOrderPaymentController::class, 'generateTrx'])->name('purchase-order.payment.generateTrx');

    Route::get('/invoice/request-order',[RequestOrderInvoiceController::class, 'index'])->name('request-order.invoice');
    Route::post('/invoice/request-order',[RequestOrderInvoiceController::class, 'store'])->name('request-order.invoice.store');
    Route::post('/invoice/request-order/generate',[RequestOrderInvoiceController::class, 'generateTrx'])->name('request-order.invoice.generateTrx');
    Route::get('/invoice/request-order/{id}',[RequestOrderInvoiceController::class, 'show'])->name('request-order.invoice.show');
    Route::delete('/invoice/request-order/{id}/destroy',[RequestOrderInvoiceController::class, 'destroy'])->name('request-order.invoice.destroy');
    Route::post('/invoice/request-order/{id}/payment/{pay_id}/generateTrx',[RequestOrderPaymentController::class, 'generateTrx'])->name('request-order.payment.generateTrx');
    
    Route::get('/invoice/transport',[TransportInvoiceController::class, 'index'])->name('transport.invoice');
    Route::post('/invoice/transport',[TransportInvoiceController::class, 'store'])->name('transport.invoice.store');
    Route::post('/invoice/transport/generate',[TransportInvoiceController::class, 'generateTrx'])->name('transport.invoice.generateTrx');
    Route::get('/invoice/transport/{id}',[TransportInvoiceController::class, 'show'])->name('transport.invoice.show');
    Route::post('/invoice/transport/{id}/payment/{pay_id}/generateTrx',[TransportPaymentController::class, 'generateTrx'])->name('transport.payment.generateTrx');

    Route::get('/period',[TransactionPeriodController::class, 'index'])->name('period.index');
    Route::post('/period/store',[TransactionPeriodController::class, 'store'])->name('period.store');
    Route::post('/period/close/{id}',[TransactionPeriodController::class, 'close'])->name('period.close');
    Route::post('/period/{id}/update',[TransactionPeriodController::class, 'update'])->name('period.update');
    Route::delete('/period/{id}/destroy',[TransactionPeriodController::class, 'destroy'])->name('period.destroy');
    
    Route::get('/master-account',[MasterAccountController::class, 'index'])->name('master-account.index');
    Route::post('/master-account/store',[MasterAccountController::class, 'store'])->name('master-account.store');
    Route::post('/master-account/{id}/update',[MasterAccountController::class, 'update'])->name('master-account.update');
    Route::delete('/master-account/{id}/destroy',[MasterAccountController::class, 'destroy'])->name('master-account.destroy');
   
    Route::get('/account',[AccountController::class, 'index'])->name('account.index');
    Route::post('/account/store',[AccountController::class, 'store'])->name('account.store');
    Route::post('/account/{id}/update',[AccountController::class, 'update'])->name('account.update');
    Route::delete('/account/{id}/destroy',[AccountController::class, 'destroy'])->name('account.destroy');
    
    Route::get('/transaction',[TransactionController::class, 'index'])->name('transaction.index');
    Route::get('/transaction/add',[TransactionController::class, 'add'])->name('transaction.add');
    Route::get('/transaction/{id}/edit',[TransactionController::class, 'edit'])->name('transaction.edit');
    Route::post('/transaction/{id}/update',[TransactionController::class, 'update'])->name('transaction.update');
    Route::post('/transaction/store',[TransactionController::class, 'store'])->name('transaction.store');
    Route::post('/transaction/{id}/update',[TransactionController::class, 'update'])->name('transaction.update');
    Route::delete('/transaction/{id}/destroy',[TransactionController::class, 'destroy'])->name('transaction.destroy');
    
    Route::get('/asset',[AssetController::class, 'index'])->name('asset.index');
    Route::get('/asset/add',[AssetController::class, 'add'])->name('asset.add');
    Route::get('/asset/{id}/edit',[AssetController::class, 'edit'])->name('asset.edit');
    Route::post('/asset/{id}/update',[AssetController::class, 'update'])->name('asset.update');
    Route::post('/asset/store',[AssetController::class, 'store'])->name('asset.store');
    Route::post('/asset/{id}/update',[AssetController::class, 'update'])->name('asset.update');
    Route::delete('/asset/{id}/destroy',[AssetController::class, 'destroy'])->name('asset.destroy');

    Route::get('/report/balance-sheet', [ReportController::class, 'balanceSheet'])->name('report.balance-sheet');
    Route::get('/report/cash-flow', [ReportController::class, 'cashFlow'])->name('report.cash-flow');
    Route::get('/report/income-statement', [ReportController::class, 'incomeStatement'])->name('report.income-statement');
    Route::get('/report/general-ledger', [ReportController::class, 'generalLedger'])->name('report.general-ledger');
    Route::get('/report/changes-in-equity', [ReportController::class, 'changesInEquity'])->name('report.changes-in-equity');
    Route::get('/report/receivable-and-payable', [ReportController::class, 'receivableAndPayable'])->name('report.receivable-and-payable');

    Route::get('/check/pdf/{bladePath}', [PDFController::class, 'debug'])->name('pdf.debug');
    Route::post('/let-see-the/pdf', [PDFController::class, 'preview'])->name('pdf.preview');

    Route::middleware(['role:developer'])->group(function(){

    });

    Route::middleware(['role:admin|developer'])->group(function (){

    });
});