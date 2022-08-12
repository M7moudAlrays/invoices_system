<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\InvoiceAttachmentsController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\invoicesReportController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use Symfony\Component\Routing\Router;

Route::get('/', function () {
    return view('Auth.login');
});

Auth::routes();
// Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function() 
{
    Route::resource('roles',RoleController::class);
    Route::resource('users', UserController::class);
});

Route::resource('invoices', InvoicesController::class); 
Route::resource('sections', SectionsController::class);
Route::resource('products', ProductsController::class);
Route::resource('invoice_attchment',InvoiceAttachmentsController::class) ;
Route::resource('archive', ArchiveController::class) ;

Route::get('invoices_report',[invoicesReportController::class , 'index']) ;
Route::post('Search_invoices', [invoicesReportController::class ,'searchinvoices']) ;

Route::get('/section/{id}' , [InvoicesController::class, 'getproducts']) ;
Route::post('status_update/{id}' , [InvoicesController::class, 'status_update'])->name('StS_update') ;
Route::get('invoices_paid' , [InvoicesController::class , 'invoices_paid']) ;
Route::get('invoices_unpaid' , [InvoicesController::class , 'invoices_unpaid']) ;
Route::get('invoices_Partial' , [InvoicesController::class , 'invoices_Partial']) ;
Route::get('print_invoice/{id}' , [InvoicesController::class ,'Print_invoice'])->name('print_invoice') ;
Route::get('export_invoices' ,[InvoicesController::class ,'export'])->name('Exp_Inv') ;

Route::get('/invoices_details/{id}' ,[InvoicesDetailsController::class,'get_invoice_details']) ;

Route::get('/View_file/{invoice_number}/{file_name}',[InvoicesDetailsController::class,'show_file']) ;

Route::get('/save_file/{invoice_number}/{file_name}',[InvoicesDetailsController::class,'downlaod_file']) ;
// Making Delete For File (Attachemt)
Route::post('delete_file',[InvoicesDetailsController::class,'delete_file'])->name('delete');

// Route::get('/section/{id}', 'App\Http\Controllers\InvoicesController@getproducts');
Route::get('/{page}',[AdminController::class ,'index']);








