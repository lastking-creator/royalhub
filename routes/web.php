<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\WelcomeContentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MemberDashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [MemberDashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'approved'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Route Group
Route::middleware(['auth', 'superadmin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard (Metrics & Alerts)
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User / Member Management
    Route::get('/members', [AdminDashboardController::class, 'members'])->name('members');
    Route::post('/members/import', [AdminDashboardController::class, 'importMembers'])->name('members.import');
    Route::post('/members/{user}/approve', [AdminDashboardController::class, 'approveMember'])->name('members.approve');
    Route::post('/members/{user}/decline', [AdminDashboardController::class, 'declineMember'])->name('members.decline');
    Route::get('/members/{id}/print-form', [AdminDashboardController::class, 'printRegistrationForm'])->name('members.print-form');

    // Programs & Projects Routes
    Route::get('/programs', [AdminDashboardController::class, 'programs'])->name('programs');
    Route::post('/projects', [AdminDashboardController::class, 'storeProject'])->name('projects.store');
    Route::post('/programs', [AdminDashboardController::class, 'storeProgram'])->name('programs.store');

    // Events & Attendance Management
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::post('/events/{event}/rsvp', [EventController::class, 'rsvp'])->name('events.rsvp');
    Route::post('/events/{event}/checkin/{user}', [EventController::class, 'checkIn'])->name('events.checkin');
    Route::get('/events/{event}/report', [EventController::class, 'report'])->name('events.report');

    // Donations & Finance
    Route::get('/finance', [AdminDashboardController::class, 'finance'])->name('finance');
    Route::post('/donors', [AdminDashboardController::class, 'storeDonor'])->name('donors.store');
    Route::post('/donations', [AdminDashboardController::class, 'storeDonation'])->name('donations.store');
    Route::post('/expenses', [AdminDashboardController::class, 'storeExpense'])->name('expenses.store');
    Route::get('/finance/export/{type}', [AdminDashboardController::class, 'exportFinanceData'])->name('finance.export');
    Route::post('/finance/donor-donation', [AdminDashboardController::class, 'storeDonorDonation'])->name('donations.store-donor');
    Route::post('/finance/member-contribution', [AdminDashboardController::class, 'storeMemberContribution'])->name('donations.store-member');

    // Grants & Reporting
    Route::get('/grants', [AdminDashboardController::class, 'grants'])->name('grants');
    Route::post('/grants', [AdminDashboardController::class, 'storeGrant'])->name('grants.store');
    Route::post('/grants/deliverables', [AdminDashboardController::class, 'storeGrantDeliverable'])->name('grants.deliverables.store');
    Route::post('/grants/documents', [AdminDashboardController::class, 'storeGrantDocument'])->name('grants.documents.store');
    Route::get('/grants/{grant}/export-report', [AdminDashboardController::class, 'exportGrantReport'])->name('grants.export.report');

    // Communications
    Route::get('/communications', [AdminDashboardController::class, 'communications'])->name('communications');
    Route::post('/communications/send', [AdminDashboardController::class, 'sendCommunication'])->name('communications.send');

    // Documents & Compliance
    Route::get('/documents', [AdminDashboardController::class, 'documents'])->name('documents');
    Route::post('/documents', [AdminDashboardController::class, 'storeComplianceDocument'])->name('documents.store');
    
    // Reports & Analytics
    Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('reports');
    Route::get('/reports/export', [AdminDashboardController::class, 'exportReport'])->name('reports.export');

    // System Accounts & Settings
    Route::get('/accounts', [AdminDashboardController::class, 'accounts'])->name('accounts');
    Route::get('/accounts/{user}/edit', [AdminDashboardController::class, 'editAccount'])->name('accounts.edit');
    Route::put('/accounts/{user}', [AdminDashboardController::class, 'updateAccount'])->name('accounts.update');
    Route::delete('/accounts/{user}', [AdminDashboardController::class, 'deleteAccount'])->name('accounts.delete');

    // Welcome Content Editor
    Route::get('/welcome/edit', [WelcomeContentController::class, 'edit'])->name('welcome.edit');
    Route::put('/welcome/update', [WelcomeContentController::class, 'update'])->name('welcome.update');
});

require __DIR__.'/auth.php';