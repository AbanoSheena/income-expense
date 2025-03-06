<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use Inertia\Inertia;
use App\Models\Expense;
class IncomeController extends Controller
{
    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date_received' => 'required|date',
        ]);
    
        $income = Income::create($validated);
    
        return response()->json([
            'success' => true,
            'message' => 'Income added successfully!',
            'income' => $income
        ]);
    }
    public function index()
    {
        // $incomes = Income::all();
        // $expenses = Expense::all();
        // return Inertia::render('Dashboard', [
        //     'incomes' => $incomes,
        //     'expenses' => $expenses,
        // ]);
        return Inertia::render('Dashboard', [
            'incomes' => Income::all(),
        ]);
    }
    public function destroy(Income $income)
    {
        $income->delete();
        return response()->json([
            'success' => true,
            'message' => 'Income deleted successfully!',
        ]);
    }
    public function update(Request $request, Income $income)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date_received' => 'required|date',
        ]);

        $income->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Income updated successfully!',
            'income' => $income
        ]);
    }
}
