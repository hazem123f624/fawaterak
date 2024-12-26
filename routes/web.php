<?php

use App\Http\Controllers\Customers_Report;
use App\Http\Controllers\InvoiceAchiveController;
use App\Http\Controllers\InvoiceAttachmentsController;
use App\Http\Controllers\Invoices_Report;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

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
    return view('/home');
});
Auth::routes();
//Auth::routes(['register' =>false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/logout', [App\Http\Controllers\Auth\LogoutController::class, 'logout'])->name('logout');

Route::resource('invoices', InvoicesController::class);

Route::resource('sections', SectionsController::class);

Route::resource('products', ProductsController::class);

Route::resource('InvoiceAttachments', InvoiceAttachmentsController::class);

Route::get('/section/{id}', [InvoicesController::class,'getproducts']);

Route::get('/InvoicesDetails/{id}', [InvoicesDetailsController::class,'edit']);
Route::get('download/{invoice_number}/{file_name}',[InvoicesDetailsController::class,'get_file']);
Route::get('View_file/{invoice_number}/{file_name}',[InvoicesDetailsController::class,'open_file']);

Route::delete('delete_file',[ InvoicesDetailsController::class,'destroy'])->name('delete_file');
Route::get('/Status_show/{id}', [InvoicesController::class,'show'])->name('Status_show');
Route::post('/StatusUpdate/{id}',[InvoicesController::class,'Status_Update'])->name('Status_Update');
Route::get('/edit_invoice/{id}', [InvoicesController::class,'edit']);
Route::get('Print_invoice/{id}',[InvoicesController::class,'Print_invoice']);

Route::get('Invoice_Paid',[InvoicesController::class,'Invoice_Paid']);
Route::get('Invoice_UnPaid',[InvoicesController::class,'Invoice_unPaid']);
Route::get('Invoice_Partial',[InvoicesController::class,'Invoice_Partial']);
Route::resource('Archive', InvoiceAchiveController::class);

Route::get('export_invoices', [InvoicesController::class, 'export']);

Route::group(['middleware' => ['auth']], function() {

    Route::resource('roles',RoleController::class);

    Route::resource('users',UserController::class);

});
Route::get('invoices_report', [Invoices_Report::class,'index']);
Route::post('Search_invoices', [Invoices_Report::class,'Search_invoices']);
Route::get('customers_report', [Customers_Report::class,'index'])->name("customers_report");
Route::post('Search_customers', [Customers_Report::class,'Search_customers']);
Route::get('/section/all', [Customers_Report::class, 'getAllSectionsAndProducts']);
Route::get('/getAllProducts', [Customers_Report::class, 'getAllProducts'])->name('getAllProducts');
Route::get('get-products/{sectionId}', [Customers_Report::class, 'getProductsBySection']);


Route::get('MarkAsRead_all',[InvoicesController::class,'MarkAsRead_all'])->name('MarkAsRead_all');

Route::get('/MarkAsRead/{id}', function ($id) {
    $notification = auth()->user()->unreadNotifications->find($id);
    if ($notification) {
        $notification->markAsRead();
    }
    return response()->json(['success' => true]);
});


Route::get('/{page}', [AdminController::class,'index']);

