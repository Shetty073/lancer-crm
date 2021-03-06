<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AccountsController;
use App\Http\Controllers\BankAccountsController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DuesController;
use App\Http\Controllers\EnquiriesController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\FollowupsController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ServicesController;
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
    Route::get('signup', [AccountsController::class, 'signup'])->name('signup.index')->middleware('auth');
    Route::post('signin', [AccountsController::class, 'signin'])->name('signin');
    Route::post('signup', [AccountsController::class, 'signup'])->name('signup')->middleware('auth');
    Route::post('signout', [AccountsController::class, 'signout'])->name('signout')->middleware('auth');
});

// dashboard routes
Route::group(['prefix' => '/', 'middleware' => 'auth'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
});

// enquiries routes
Route::group(['prefix' => '/enquiries', 'middleware' => 'auth'], function () {
    Route::get('/', [EnquiriesController::class, 'index'])->name('enquiries.index');
    Route::get('/{id}/show', [EnquiriesController::class, 'show'])->name('enquiries.show');
    Route::get('/create', [EnquiriesController::class, 'create'])->name('enquiries.create');
    Route::get('/{id}/edit', [EnquiriesController::class, 'edit'])->name('enquiries.edit');
    Route::post('/store', [EnquiriesController::class, 'store'])->name('enquiries.store');
    Route::post('/{id}/update', [EnquiriesController::class, 'update'])->name('enquiries.update');
    Route::post('/{id}/update/status', [EnquiriesController::class, 'updateStatus'])->name('enquiries.updateStatus');
    Route::post('/{id}/destroy', [EnquiriesController::class, 'destroy'])->name('enquiries.destroy');

    Route::post('/{id}/transfer', [EnquiriesController::class, 'transfer'])->name('enquiries.transfer');
});

// followups routes
Route::group(['prefix' => '/followups', 'middleware' => 'auth'], function () {
    Route::post('/store', [FollowupsController::class, 'store'])->name('followups.store');
    Route::post('/{id}/update', [FollowupsController::class, 'update'])->name('followups.update');
    Route::post('/{id}/destroy', [FollowupsController::class, 'destroy'])->name('followups.destroy');
});

// clients routes
Route::group(['prefix' => '/clients', 'middleware' => 'auth'], function () {
    Route::get('/', [ClientsController::class, 'index'])->name('clients.index');
    Route::get('/create', [ClientsController::class, 'create'])->name('clients.create');
    Route::post('/store', [ClientsController::class, 'store'])->name('clients.store');
    Route::get('/{id}/edit', [ClientsController::class, 'edit'])->name('clients.edit');
    Route::post('/{id}/update', [ClientsController::class, 'update'])->name('clients.update');
    Route::get('/{id}/show', [ClientsController::class, 'show'])->name('clients.show');
    Route::post('/{id}/destroy', [ClientsController::class, 'destroy'])->name('clients.destroy');
});

// projects routes
Route::group(['prefix' => '/projects', 'middleware' => 'auth'], function () {
    Route::get('/', [ProjectsController::class, 'index'])->name('projects.index');
    Route::get('/create', [ProjectsController::class, 'create'])->name('projects.create');
    Route::post('/store', [ProjectsController::class, 'store'])->name('projects.store');
    Route::get('/{id}/edit', [ProjectsController::class, 'edit'])->name('projects.edit');
    Route::post('/{id}/update', [ProjectsController::class, 'update'])->name('projects.update');
    Route::get('/{id}/show', [ProjectsController::class, 'show'])->name('projects.show');
    Route::post('/{id}/destroy', [ProjectsController::class, 'destroy'])->name('projects.destroy');
});

// dues routes
Route::group(['prefix' => '/dues', 'middleware' => 'auth'], function () {
    Route::get('/', [DuesController::class, 'index'])->name('dues.index');
});

// bankaccount routes
Route::group(['prefix' => '/payments', 'middleware' => 'auth'], function () {
    Route::get('/', [PaymentsController::class, 'index'])->name('payments.index');
});

// expenses routes
Route::group(['prefix' => '/expenses', 'middleware' => 'auth'], function () {
    Route::get('/', [ExpensesController::class, 'index'])->name('expenses.index');
});

// reports routes
Route::group(['prefix' => '/reports', 'middleware' => 'auth'], function () {
    Route::get('/', [ReportsController::class, 'index'])->name('reports.index');
});

// useraccount routes
Route::group(['prefix' => '/useraccount', 'middleware' => 'auth'], function () {
    Route::get('/', [UserAccountController::class, 'index'])->name('useraccount.index');
});

// bankaccount routes
Route::group(['prefix' => '/bankaccounts', 'middleware' => 'auth'], function () {
    Route::get('/', [BankAccountsController::class, 'index'])->name('bankaccounts.index');
});
