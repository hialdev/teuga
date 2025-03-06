<?php

use App\Http\Controllers\AccessController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomOrderController;
use App\Http\Controllers\CustomOrderPaymentController;
use App\Http\Controllers\DesainController;
use App\Http\Controllers\FlyerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseRawMaterialController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\RequestProductionController;
use App\Http\Controllers\ReturController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
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
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
    
    Route::middleware(['role:developer'])->group(function(){
        Route::delete('/setting/{id}/force',[SettingController::class, 'force'])->name('setting.force');
        Route::delete('/access/{id}/destroy',[AccessController::class, 'destroy'])->name('access.destroy');
    });

    Route::middleware(['role:admin|developer'])->group(function (){
        Route::get('/whatsapp', function () {
            return view('whatsapp.index');
        })->name('whatsapp.index');
        Route::get('/access',[AccessController::class, 'index'])->name('access.index');
        Route::post('/access/store',[AccessController::class, 'store'])->name('access.store');
        Route::post('/access/{id}/update',[AccessController::class, 'update'])->name('access.update');

        Route::get('/application',[ApplicationController::class, 'index'])->name('application.index');
        Route::get('/application/add',[ApplicationController::class, 'add'])->name('application.add');
        Route::post('/application/store',[ApplicationController::class, 'store'])->name('application.store');
        Route::get('/application/{id}/edit',[ApplicationController::class, 'edit'])->name('application.edit');
        Route::post('/application/{id}/update',[ApplicationController::class, 'update'])->name('application.update');
        Route::delete('/application/{id}/destroy',[ApplicationController::class, 'destroy'])->name('application.destroy');

        Route::get('/user',[UserController::class, 'index'])->name('user.index');
        Route::get('/user/add',[UserController::class, 'add'])->name('user.add');
        Route::post('/user/store',[UserController::class, 'store'])->name('user.store');
        Route::get('/user/{id}/edit',[UserController::class, 'edit'])->name('user.edit');
        Route::post('/user/{id}/update',[UserController::class, 'update'])->name('user.update');
        Route::delete('/user/{id}/destroy',[UserController::class, 'destroy'])->name('user.destroy');
        
        Route::get('/setting',[SettingController::class, 'index'])->name('setting.index');
        Route::post('/setting',[SettingController::class, 'store'])->name('setting.store');
        Route::post('/setting/{id}',[SettingController::class, 'update'])->name('setting.update');
        Route::post('/setting/{id}/clear',[SettingController::class, 'clear'])->name('setting.clear');
        Route::delete('/setting/{id}',[SettingController::class, 'destroy'])->name('setting.destroy');
    });
});