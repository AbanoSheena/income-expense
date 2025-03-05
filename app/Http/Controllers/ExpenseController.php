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

        
        Expense::create($validated);

        return redirect()->back()->with('success', 'Expense added successfully!');
    }
}
