<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AccountsController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DuesController;
use App\Http\Controllers\EnquiriesController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\FollowupsController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\UserAccountController;

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

// signup
Route::group(['prefix' => '/account'], function () {
    Route::get('signin', [AccountsController::class, 'index'])->name('signin.index');
    Route::post('signin', [AccountsController::class, 'signin'])->name('signin');
    Route::post('signout', [AccountsController::class, 'signout'])->name('signout')->middleware('auth');
});

// dashboard routes
Route::group(['prefix' => '/', 'middleware' => 'auth'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
});

// facebook leads retrieval route
Route::group(['prefix' => '/fb'], function () {
    Route::get('/webhook', [EnquiriesController::class, 'retrievefbleadwebhook'])->name('enquiries.getfbleadwebhook');
    Route::post('/webhook', [EnquiriesController::class, 'retrievefbleadwebhook'])->name('enquiries.postfbleadwebhook');
});

// enquiries routes
Route::group(['prefix' => '/enquiries', 'middleware' => ['auth', 'can:enquiry_access']], function () {
    Route::get('/', [EnquiriesController::class, 'index'])->name('enquiries.index');
    Route::get('/{id}/show', [EnquiriesController::class, 'show'])->name('enquiries.show')->middleware('can:enquiry_show');
    Route::get('/create', [EnquiriesController::class, 'create'])->name('enquiries.create')->middleware('can:enquiry_create');
    Route::post('/store', [EnquiriesController::class, 'store'])->name('enquiries.store')->middleware('can:enquiry_create');
    Route::get('/{id}/edit', [EnquiriesController::class, 'edit'])->name('enquiries.edit')->middleware('can:enquiry_edit');
    Route::post('/{id}/update', [EnquiriesController::class, 'update'])->name('enquiries.update')->middleware('can:enquiry_edit');
    Route::post('/{id}/update/status', [EnquiriesController::class, 'updateStatus'])->name('enquiries.updateStatus')->middleware('can:enquiry_edit');
    Route::post('/{id}/destroy', [EnquiriesController::class, 'destroy'])->name('enquiries.destroy')->middleware('can:enquiry_delete');

    Route::get('/{id}/close', [EnquiriesController::class, 'close'])->name('enquiries.close')->middleware('can:client_create');
});

// followups routes
Route::group(['prefix' => '/followups', 'middleware' => ['auth', 'can:followup_access']], function () {
    Route::post('/store', [FollowupsController::class, 'store'])->name('followups.store')->middleware('can:followup_create');
    Route::post('/{id}/update', [FollowupsController::class, 'update'])->name('followups.update')->middleware('can:followup_edit');
    Route::post('/{id}/destroy', [FollowupsController::class, 'destroy'])->name('followups.destroy')->middleware('can:followup_delete');
});

// clients routes
Route::group(['prefix' => '/clients', 'middleware' => ['auth', 'can:client_access']], function () {
    Route::get('/', [ClientsController::class, 'index'])->name('clients.index');
    Route::get('/create', [ClientsController::class, 'create'])->name('clients.create')->middleware('can:client_create');
    Route::post('/store', [ClientsController::class, 'store'])->name('clients.store')->middleware('can:client_create');
    Route::get('/{id}/edit', [ClientsController::class, 'edit'])->name('clients.edit')->middleware('can:client_edit');
    Route::post('/{id}/update', [ClientsController::class, 'update'])->name('clients.update')->middleware('can:client_edit');
    Route::get('/{id}/show', [ClientsController::class, 'show'])->name('clients.show')->middleware('can:client_show');
    Route::post('/{id}/destroy', [ClientsController::class, 'destroy'])->name('clients.destroy')->middleware('can:client_delete');
});

// projects routes
Route::group(['prefix' => '/projects', 'middleware' => ['auth', 'can:project_access']], function () {
    Route::get('/', [ProjectsController::class, 'index'])->name('projects.index');
    Route::get('/create', [ProjectsController::class, 'create'])->name('projects.create')->middleware('can:project_create');
    Route::post('/store', [ProjectsController::class, 'store'])->name('projects.store')->middleware('can:project_create');
    Route::get('/{id}/edit', [ProjectsController::class, 'edit'])->name('projects.edit')->middleware('can:project_edit');
    Route::post('/{id}/update', [ProjectsController::class, 'update'])->name('projects.update')->middleware('can:project_edit');
    Route::get('/{id}/show', [ProjectsController::class, 'show'])->name('projects.show')->middleware('can:project_show');
    Route::post('/{id}/destroy', [ProjectsController::class, 'destroy'])->name('projects.destroy')->middleware('can:project_delete');
});

// dues routes
Route::group(['prefix' => '/dues', 'middleware' => ['auth', 'can:payment_access']], function () {
    Route::get('/', [DuesController::class, 'index'])->name('dues.index');
    Route::get('/create', [DuesController::class, 'create'])->name('dues.create')->middleware('can:payment_create');
    Route::post('/store', [DuesController::class, 'store'])->name('dues.store')->middleware('can:payment_create');
    Route::get('/{id}/edit', [DuesController::class, 'edit'])->name('dues.edit')->middleware('can:payment_edit');
    Route::post('/{id}/update', [DuesController::class, 'update'])->name('dues.update')->middleware('can:payment_edit');
    Route::post('/{id}/pay', [DuesController::class, 'pay'])->name('dues.pay')->middleware('can:payment_edit');
    Route::post('/{id}/destroy', [DuesController::class, 'destroy'])->name('dues.destroy')->middleware('can:payment_delete');
});

// bankaccount routes
Route::group(['prefix' => '/payments', 'middleware' => ['auth', 'can:payment_access']], function () {
    Route::get('/', [PaymentsController::class, 'index'])->name('payments.index');
    Route::get('/create', [PaymentsController::class, 'create'])->name('payments.create')->middleware('can:payment_create');
    Route::post('/store', [PaymentsController::class, 'store'])->name('payments.store')->middleware('can:payment_create');
    Route::get('/{id}/edit', [PaymentsController::class, 'edit'])->name('payments.edit')->middleware('can:payment_edit');
    Route::post('/{id}/update', [PaymentsController::class, 'update'])->name('payments.update')->middleware('can:payment_edit');
    Route::post('/{id}/destroy', [PaymentsController::class, 'destroy'])->name('payments.destroy')->middleware('can:payment_delete');
});

// expenses routes
Route::group(['prefix' => '/expenses', 'middleware' => ['auth', 'can:payment_access']], function () {
    Route::get('/', [ExpensesController::class, 'index'])->name('expenses.index');
    Route::get('/create', [ExpensesController::class, 'create'])->name('expenses.create')->middleware('can:payment_create');
    Route::post('/store', [ExpensesController::class, 'store'])->name('expenses.store')->middleware('can:payment_create');
    Route::get('/{id}/edit', [ExpensesController::class, 'edit'])->name('expenses.edit')->middleware('can:payment_edit');
    Route::post('/{id}/update', [ExpensesController::class, 'update'])->name('expenses.update')->middleware('can:payment_edit');
    Route::post('/{id}/destroy', [ExpensesController::class, 'destroy'])->name('expenses.destroy')->middleware('can:payment_delete');
});

// reports routes
Route::group(['prefix' => '/reports', 'middleware' => ['auth', 'can:report_access']], function () {
    Route::get('/', [ReportsController::class, 'index'])->name('reports.index');
});

// myaccount routes
Route::group(['prefix' => '/myaccount', 'middleware' => 'auth'], function () {
    Route::get('/', [AccountsController::class, 'myAccount'])->name('myaccount.index');
    Route::post('/{id}/update', [AccountsController::class, 'updateMyPersonalDetails'])->name('myaccount.update');
    Route::post('/{id}/password/change', [AccountsController::class, 'changeMyPassword'])->name('myaccount.changepassword');
});

// useraccount routes
Route::group(['prefix' => '/useraccount', 'middleware' => ['auth', 'can:user_management_access']], function () {
    Route::get('/', [UserAccountController::class, 'index'])->name('useraccounts.index');
    Route::get('/{id}/show', [UserAccountController::class, 'show'])->name('useraccounts.show');
    Route::get('/create', [UserAccountController::class, 'create'])->name('useraccounts.create');
    Route::post('/store', [UserAccountController::class, 'store'])->name('useraccounts.store');
    Route::get('/{id}/edit', [UserAccountController::class, 'edit'])->name('useraccounts.edit');
    Route::post('/{id}/update', [UserAccountController::class, 'update'])->name('useraccounts.update');
    Route::post('/{id}/destroy', [UserAccountController::class, 'destroy'])->name('useraccounts.destroy');
});

// transactions route
Route::group(['prefix' => '/transactions', 'middleware' => ['auth', 'can:transaction_access']], function () {
    Route::get('/', [TransactionsController::class, 'index'])->name('transactions.index');
});
