<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use Inertia\Inertia;
class IncomeController extends Controller
{
    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        
        Income::create($validated);

        return redirect()->back()->with('success', 'Income added successfully!');
    }
}
