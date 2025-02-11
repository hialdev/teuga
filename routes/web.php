<?php

use App\Http\Controllers\AJAXController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\ClientAddressController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientPicController;
use App\Http\Controllers\LogisticAddressController;
use App\Http\Controllers\LogisticController;
use App\Http\Controllers\PackController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PrincipalAddressController;
use App\Http\Controllers\PrincipalController;
use App\Http\Controllers\PrincipalPicController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchaseOrderFileController;
use App\Http\Controllers\PurchaseOrderInvoiceController;
use App\Http\Controllers\PurchaseOrderPaymentController;
use App\Http\Controllers\PurchaseOrderProductController;
use App\Http\Controllers\RequestOrderController;
use App\Http\Controllers\RequestOrderFileController;
use App\Http\Controllers\RequestOrderInvoiceController;
use App\Http\Controllers\RequestOrderPaymentController;
use App\Http\Controllers\RequestOrderProductController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\TransportInvoiceController;
use App\Http\Controllers\TransportPaymentController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Log;
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

    // --------------------------------
    // Routes
    // --------------------------------
    
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
    Route::get('/profile', function(){ return redirect()->to(urlApp('ACC','/profile')); })->name('profile.index');

    Route::get('/unit',[UnitController::class, 'index'])->name('unit.index');
    Route::post('/unit/store',[UnitController::class, 'store'])->name('unit.store');
    Route::post('/unit/{id}/update',[UnitController::class, 'update'])->name('unit.update');
    Route::delete('/unit/{id}/destroy',[UnitController::class, 'destroy'])->name('unit.destroy');

    Route::get('/pack',[PackController::class, 'index'])->name('pack.index');
    Route::post('/pack/store',[PackController::class, 'store'])->name('pack.store');
    Route::post('/pack/{id}/update',[PackController::class, 'update'])->name('pack.update');
    Route::delete('/pack/{id}/destroy',[PackController::class, 'destroy'])->name('pack.destroy');

    Route::get('/product',[ProductController::class, 'index'])->name('product.index');
    Route::get('/product/add',[ProductController::class, 'add'])->name('product.add');
    Route::post('/product/store',[ProductController::class, 'store'])->name('product.store');
    Route::get('/product/{id}/edit',[ProductController::class, 'edit'])->name('product.edit');
    Route::post('/product/{id}/update',[ProductController::class, 'update'])->name('product.update');
    Route::delete('/product/{id}/destroy',[ProductController::class, 'destroy'])->name('product.destroy');

    Route::get('/principal',[PrincipalController::class, 'index'])->name('principal.index');
    Route::get('/principal/add',[PrincipalController::class, 'add'])->name('principal.add');
    Route::post('/principal/store',[PrincipalController::class, 'store'])->name('principal.store');
    Route::get('/principal/{id}/setting',[PrincipalController::class, 'setting'])->name('principal.setting');
    Route::post('/principal/{id}/update',[PrincipalController::class, 'update'])->name('principal.update');
    Route::delete('/principal/{id}/destroy',[PrincipalController::class, 'destroy'])->name('principal.destroy');
    
    Route::post('/principal/{id}/address/store',[PrincipalAddressController::class, 'store'])->name('principal.address.store');
    Route::post('/principal/{id}/address/{address_id}/update',[PrincipalAddressController::class, 'update'])->name('principal.address.update');
    Route::delete('/principal/{id}/address/{address_id}/destroy',[PrincipalAddressController::class, 'destroy'])->name('principal.address.destroy');

    Route::post('/principal/{id}/pic/store',[PrincipalPicController::class, 'store'])->name('principal.pic.store');
    Route::post('/principal/{id}/pic/{pic_id}/update',[PrincipalPicController::class, 'update'])->name('principal.pic.update');
    Route::delete('/principal/{id}/pic/{pic_id}/destroy',[PrincipalPicController::class, 'destroy'])->name('principal.pic.destroy');

    Route::get('/client',[ClientController::class, 'index'])->name('client.index');
    Route::get('/client/add',[ClientController::class, 'add'])->name('client.add');
    Route::post('/client/store',[ClientController::class, 'store'])->name('client.store');
    Route::get('/client/{id}/setting',[ClientController::class, 'setting'])->name('client.setting');
    Route::post('/client/{id}/update',[ClientController::class, 'update'])->name('client.update');
    Route::delete('/client/{id}/destroy',[ClientController::class, 'destroy'])->name('client.destroy');
    
    Route::post('/client/{id}/address/store',[ClientAddressController::class, 'store'])->name('client.address.store');
    Route::post('/client/{id}/address/{address_id}/update',[ClientAddressController::class, 'update'])->name('client.address.update');
    Route::delete('/client/{id}/address/{address_id}/destroy',[ClientAddressController::class, 'destroy'])->name('client.address.destroy');

    Route::post('/client/{id}/pic/store',[ClientPicController::class, 'store'])->name('client.pic.store');
    Route::post('/client/{id}/pic/{pic_id}/update',[ClientPicController::class, 'update'])->name('client.pic.update');
    Route::delete('/client/{id}/pic/{pic_id}/destroy',[ClientPicController::class, 'destroy'])->name('client.pic.destroy');

    Route::get('/logistic',[LogisticController::class, 'index'])->name('logistic.index');
    Route::get('/logistic/add',[LogisticController::class, 'add'])->name('logistic.add');
    Route::post('/logistic/store',[LogisticController::class, 'store'])->name('logistic.store');
    Route::get('/logistic/{id}/setting',[LogisticController::class, 'setting'])->name('logistic.setting');
    Route::post('/logistic/{id}/update',[LogisticController::class, 'update'])->name('logistic.update');
    Route::post('/logistic/{id}/cp',[LogisticController::class, 'cp'])->name('logistic.cp');
    Route::delete('/logistic/{id}/destroy',[LogisticController::class, 'destroy'])->name('logistic.destroy');
    
    Route::post('/logistic/{id}/address/store',[LogisticAddressController::class, 'store'])->name('logistic.address.store');
    Route::post('/logistic/{id}/address/{address_id}/update',[LogisticAddressController::class, 'update'])->name('logistic.address.update');
    Route::delete('/logistic/{id}/address/{address_id}/destroy',[LogisticAddressController::class, 'destroy'])->name('logistic.address.destroy');
    
    // -------------------------------------
    // Business Process
    // -------------------------------------
    Route::get('/request-order',[RequestOrderController::class, 'index'])->name('request-order.index');
    Route::get('/request-order/add',[RequestOrderController::class, 'add'])->name('request-order.add');
    Route::post('/request-order/store',[RequestOrderController::class, 'store'])->name('request-order.store');
    Route::get('/request-order/{id}/edit',[RequestOrderController::class, 'edit'])->name('request-order.edit');
    Route::get('/request-order/{id}/setting',[RequestOrderController::class, 'setting'])->name('request-order.setting');
    Route::post('/request-order/{id}/update',[RequestOrderController::class, 'update'])->name('request-order.update');
    Route::post('/request-order/{id}/addCart',[RequestOrderController::class, 'addCart'])->name('request-order.addCart');
    Route::post('/request-order/{id}/refetch',[RequestOrderController::class, 'refetch'])->name('request-order.refetch');
    Route::post('/request-order/{id}/removeCart',[RequestOrderController::class, 'removeCart'])->name('request-order.removeCart');
    Route::delete('/request-order/{id}/destroy',[RequestOrderController::class, 'destroy'])->name('request-order.destroy');

    Route::post('/request-order/{id}/invoice',[RequestOrderController::class, 'invoice'])->name('request-order.generate');

    Route::post('/request-order/{id}/product/store',[RequestOrderProductController::class, 'store'])->name('request-order.product.store');

    Route::post('/request-order/{id}/file/store',[RequestOrderFileController::class, 'store'])->name('request-order.file.store');
    Route::delete('/request-order/{id}/file/{file_id}/destroy',[RequestOrderFileController::class, 'destroy'])->name('request-order.file.destroy');

    Route::get('/invoice/request-order',[RequestOrderInvoiceController::class, 'index'])->name('request-order.invoice');
    Route::post('/invoice/request-order',[RequestOrderInvoiceController::class, 'store'])->name('request-order.invoice.store');
    Route::get('/invoice/request-order/{id}',[RequestOrderInvoiceController::class, 'show'])->name('request-order.invoice.show');
    Route::delete('/invoice/request-order/{id}/destroy',[RequestOrderInvoiceController::class, 'destroy'])->name('request-order.invoice.destroy');
    Route::post('/invoice/request-order/{id}/payment/store',[RequestOrderPaymentController::class, 'store'])->name('request-order.payment.store');
    Route::delete('/invoice/request-order/{id}/payment/{pay_id}/destroy',[RequestOrderPaymentController::class, 'destroy'])->name('request-order.payment.destroy');
    
    Route::get('/let-see-the/pdf/{id}', [PDFController::class, 'debug'])->name('pdf.debug');

    Route::get('/purchase-order',[PurchaseOrderController::class, 'index'])->name('purchase-order.index');
    Route::get('/purchase-order/add',[PurchaseOrderController::class, 'add'])->name('purchase-order.add');
    Route::post('/purchase-order/store',[PurchaseOrderController::class, 'store'])->name('purchase-order.store');
    Route::get('/purchase-order/{id}/edit',[PurchaseOrderController::class, 'edit'])->name('purchase-order.edit');
    Route::get('/purchase-order/{id}/setting',[PurchaseOrderController::class, 'setting'])->name('purchase-order.setting');
    Route::post('/purchase-order/{id}/update',[PurchaseOrderController::class, 'update'])->name('purchase-order.update');
    Route::post('/purchase-order/{id}/process',[PurchaseOrderController::class, 'process'])->name('purchase-order.process');
    Route::post('/purchase-order/{id}/addCart',[PurchaseOrderController::class, 'addCart'])->name('purchase-order.addCart');
    Route::post('/purchase-order/{id}/refetch',[PurchaseOrderController::class, 'refetch'])->name('purchase-order.refetch');
    Route::post('/purchase-order/{id}/removeCart',[PurchaseOrderController::class, 'removeCart'])->name('purchase-order.removeCart');
    Route::delete('/purchase-order/{id}/destroy',[PurchaseOrderController::class, 'destroy'])->name('purchase-order.destroy');
    
    Route::post('/purchase-order/{id}/invoice',[PurchaseOrderController::class, 'invoice'])->name('purchase-order.generate');
    Route::post('/purchase-order/{id}/invoiceClientPartial',[PurchaseOrderController::class, 'invoiceClientPartial'])->name('purchase-order.generatePartialClient');

    Route::post('/purchase-order/{id}/product/store',[PurchaseOrderProductController::class, 'store'])->name('purchase-order.product.store');

    Route::post('/purchase-order/{id}/file/store',[PurchaseOrderFileController::class, 'store'])->name('purchase-order.file.store');
    Route::delete('/purchase-order/{id}/file/{file_id}/destroy',[PurchaseOrderFileController::class, 'destroy'])->name('purchase-order.file.destroy');

    Route::get('/invoice/purchase-order',[PurchaseOrderInvoiceController::class, 'index'])->name('purchase-order.invoice');
    Route::post('/invoice/purchase-order',[PurchaseOrderInvoiceController::class, 'store'])->name('purchase-order.invoice.store');
    Route::get('/invoice/purchase-order/{id}',[PurchaseOrderInvoiceController::class, 'show'])->name('purchase-order.invoice.show');
    Route::delete('/invoice/purchase-order/{id}/destroy',[PurchaseOrderInvoiceController::class, 'destroy'])->name('purchase-order.invoice.destroy');
    Route::post('/invoice/purchase-order/{id}/payment/store',[PurchaseOrderPaymentController::class, 'store'])->name('purchase-order.payment.store');
    Route::delete('/invoice/purchase-order/{id}/payment/{pay_id}/delete',[PurchaseOrderPaymentController::class, 'destroy'])->name('purchase-order.payment.destroy');

    Route::get('/transport',[TransportController::class, 'index'])->name('transport.index');
    Route::post('/transport/store',[TransportController::class, 'store'])->name('transport.store');
    Route::post('/transport/{id}/update',[TransportController::class, 'update'])->name('transport.update');
    Route::delete('/transport/{id}/destroy',[TransportController::class, 'destroy'])->name('transport.destroy');
    
    Route::get('/invoice/transport',[TransportInvoiceController::class, 'index'])->name('transport.invoice');
    Route::post('/invoice/transport',[TransportInvoiceController::class, 'store'])->name('transport.invoice.store');
    Route::get('/invoice/transport/{id}',[TransportInvoiceController::class, 'show'])->name('transport.invoice.show');
    Route::post('/invoice/transport/{id}/payment/store',[TransportPaymentController::class, 'store'])->name('transport.payment.store');
    Route::delete('/invoice/transport/{id}/payment/{pay_id}/destroy',[TransportPaymentController::class, 'destroy'])->name('transport.payment.destroy');

    Route::post('/let-see-the/pdf', [PDFController::class, 'preview'])->name('pdf.preview');
});