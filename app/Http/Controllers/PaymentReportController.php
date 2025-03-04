<?php

namespace App\Http\Controllers;

use App\Models\AdminPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentReportExport;

class PaymentReportController extends Controller
{
    public function index()
    {
        return view('payment_reports.index');
    }

    public function show(Request $request)
    {
        $query = AdminPayment::query()->with(['invoice.client.user']);

        if ($request->has('start_date') && $request->has('end_date') && $request->has('client_id')) {
            $query->whereBetween('payment_date', [$request->start_date, $request->end_date])
                  ->whereHas('invoice', function($q) use ($request) {
                      $q->where('client_id', $request->client_id);
                  });
        }

        $payments = $query->get();

        return view('payment_reports.index', compact('payments'));
    }

    public function exportPdf(Request $request)
    {
        $query = AdminPayment::query()->with(['invoice.client.user']);

        if ($request->has('start_date') && $request->has('end_date') && $request->has('client_id')) {
            $query->whereBetween('payment_date', [$request->start_date, $request->end_date])
                  ->whereHas('invoice', function($q) use ($request) {
                      $q->where('client_id', $request->client_id);
                  });
        }

        $payments = $query->get();

        $pdf = PDF::loadView('payment_reports.pdf', compact('payments'));
        return $pdf->download('payment_report.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new PaymentReportExport($request), 'payment_report.xlsx');
    }
}
