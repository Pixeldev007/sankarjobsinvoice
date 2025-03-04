<?php

namespace App\Http\Controllers;

use App\Models\payment_method;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    
    public function index(){
        return view('payment_report.payment_report');
    }
    public function edit($id)
    {
        $paymentMethod = payment_method::findOrFail($id);
        return view('payment_report.edit', compact('paymentMethod'));
    }
     public function create()
    {
        return view('payment_report.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string|max:255',
        ]);

        payment_method::create([
            'payment_method' => $request->payment_method,
        ]);

        return redirect()->route('payment_methods.index')->with('success', 'Payment method created successfully.');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required|string|max:255',
        ]);
    
        $paymentMethod = payment_method::findOrFail($id);  // Find the payment method by ID
    
        $paymentMethod->update([
            'payment_method' => $request->payment_method,
        ]);
    
        return redirect()->route('payment_methods.index')->with('success', 'Payment method updated successfully.');
    }
    public function destroy(Request $request, $id)
{
    $paymentMethod = payment_method::findOrFail($id);

    // Check if the payment method is associated with any transactions (optional)
    if ($paymentMethod->transactions()->exists()) {
        return back()->with('error', 'This payment method cannot be deleted as it is associated with transactions.');
    }

    // Soft Delete Implementation (optional, replace with actual delete if not needed)
    if (config('app.use_soft_deletes')) {
        $paymentMethod->delete();
    } else {
        $paymentMethod->forceDelete();  // Permanent delete if soft deletes are disabled
    }

    return redirect()->route('payment_methods.index')->with('success', 'Payment method deleted successfully.');
}

    // Other methods...
}

    // Other methods (index, store, update, destroy)...
