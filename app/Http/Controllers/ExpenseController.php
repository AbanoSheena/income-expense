<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use Inertia\Inertia;

class ExpenseController extends Controller
{
    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date_received' => 'required|date',
        ]);
    
        $expense = Expense::create($validated);
    
        return response()->json([
            'success' => true,
            'message' => 'Expense added successfully!',
            'expense' => $expense
        ]);
    }
    public function index()
    {
        return Inertia::render('Dashboard', [
            'expenses' => Expense::all(),
            'income' => Income::all(),
            'categories' => ['Income', 'Expense']
        ]);
    }
    public function destroy(Expense $expense)
    {
        $expense->delete();
        return response()->json([
            'success' => true,
            'message' => 'Expense deleted successfully!',
        ]);
    }
    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date_received' => 'required|date',
        ]);

        $expense->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Expense updated successfully!',
            'expense' => $expense
        ]);
    }
    
}
