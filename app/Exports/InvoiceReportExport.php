<?php

namespace App\Exports;

use App\Models\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Http\Request;

class InvoiceReportExport implements FromView
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $query = Invoice::with(['client', 'payments']);

        if ($this->request->has('start_date') && $this->request->has('end_date') && $this->request->has('client_id')) {
            $query->whereBetween('invoice_date', [$this->request->start_date, $this->request->end_date])
                  ->where('client_id', $this->request->client_id);
        }

        $invoices = $query->get();

        return view('excel.admin_invoices_excel', compact('invoices'));
    }
}
