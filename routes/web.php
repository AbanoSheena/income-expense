<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;
use App\Models\Income;
use App\Models\Expense;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'incomes' => Income::select('id', 'description', 'category', 'amount', 'date_received')->get(),
        'expenses' => Expense::select('id', 'description', 'category', 'amount', 'date_received')->get(),
    ]);
})->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/income', [IncomeController::class, 'store'])->name('income.store');
// Route::get('/income', [IncomeController::class, 'index'])->name('income.index');

Route::delete('/income/{income}', [IncomeController::class, 'destroy'])->name('income.destroy');
Route::put('/income/{income}', [IncomeController::class, 'update'])->name('income.update');

Route::post('/expense', [ExpenseController::class, 'store'])->name('expense.store');
// Route::get('/expense', [ExpenseController::class, 'index'])->name('expense.index');
Route::delete('/expense/{expense}', [ExpenseController::class, 'destroy'])->name('expense.destroy');
Route::put('/expense/{expense}', [ExpenseController::class, 'update'])->name('expense.update');

require __DIR__.'/auth.php';
