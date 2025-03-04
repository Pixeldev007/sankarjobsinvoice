<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoiceReportExport;

class InvoiceReportController extends Controller
{
    public function index()
    {
        return view('invoice_reports.index');
    }

    public function show(Request $request)
    {
        $query = Invoice::query()->with(['client', 'payments']);

        if ($request->has('start_date') && $request->has('end_date') && $request->has('client_id')) {
            $query->whereBetween('invoice_date', [$request->start_date, $request->end_date])
                  ->where('client_id', $request->client_id);
        }

        $invoices = $query->get();

        return view('invoice_reports.index', compact('invoices'));
    }
    


    public function exportPdf(Request $request)
    {
        $query = Invoice::query()->with(['client', 'payments']);

        if ($request->has('start_date') && $request->has('end_date') && $request->has('client_id')) {
            $query->whereBetween('invoice_date', [$request->start_date, $request->end_date])
                  ->where('client_id', $request->client_id);
        }

        $invoices = $query->get();

        $pdf = PDF::loadView('invoice_reports.pdf', compact('invoices'));
        return $pdf->download('invoice_report.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new InvoiceReportExport($request), 'invoice_report.xlsx');
    }
}
