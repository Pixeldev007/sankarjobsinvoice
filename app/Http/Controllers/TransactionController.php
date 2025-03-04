<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF; // Assuming use of barryvdh/laravel-dompdf

class TransactionController extends Controller
{
    public function exportTransactionsToPdf(Request $request)
{
    // Get filter parameters from request
    $dateRange = $request->input('date_range', '');

    // Parse date range
    $dates = explode(',', $dateRange);
    $startDate = isset($dates[0]) ? $dates[0] : null;
    $endDate = isset($dates[1]) ? $dates[1] : null;

    // Build the query to get data from the transactions table
    $payments = DB::table('transactions')
        ->select('id', 'created_at', 'amount')
        ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
            return $q->whereBetween('created_at', [$startDate, $endDate]);
        })
        ->orderBy('created_at', 'desc')
        ->get();

    // Load view and pass data
    $pdf = PDF::loadView('transactions.export_transactions_pdf', ['payments' => $payments]);

return $payments;
    // Return PDF download
    // return $pdf->stream('transactions.pdf');
}

}
