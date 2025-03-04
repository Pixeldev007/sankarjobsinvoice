<?php

namespace App\Exports;

use App\Models\AdminPayment;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Http\Request;

class PaymentReportExport implements FromView
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $query = AdminPayment::with(['invoice.client.user']);

        if ($this->request->has('start_date') && $this->request->has('end_date') && $this->request->has('client_id')) {
            $query->whereBetween('payment_date', [$this->request->start_date, $this->request->end_date])
                  ->whereHas('invoice', function($q) {
                      $q->where('client_id', $this->request->client_id);
                  });
        }

        $adminPayments = $query->get();

        return view('excel.admin_payments_excel', compact('adminPayments'));
    }
}
