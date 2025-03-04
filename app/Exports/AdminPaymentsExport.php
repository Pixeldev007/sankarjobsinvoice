<?php

namespace App\Exports;

use App\Models\AdminPayment;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AdminPaymentsExport implements FromView
{
    protected $paymentDateFilter;
    protected $clientNameFilter;

    public function __construct($paymentDateFilter = [], $clientNameFilter = null)
    {
        $this->paymentDateFilter = $paymentDateFilter;
        $this->clientNameFilter = $clientNameFilter;
    }

    public function view(): View
    {
        // Build the query with the filters
        $query = AdminPayment::with(['invoice.client.user.media'])->select('admin_payments.*');

        if (!empty($this->paymentDateFilter) && count($this->paymentDateFilter) > 0) {
            $startDate = Carbon::parse($this->paymentDateFilter[0])->format('Y-m-d');
            $endDate = Carbon::parse($this->paymentDateFilter[1])->format('Y-m-d');
            $query->whereBetween('payment_date', [$startDate, $endDate]);
        } else {
            $defaultDate = explode(' - ', getMonthlyData());
            $query->whereBetween('payment_date', [$defaultDate[0], $defaultDate[1]]);
        }

        if (!empty($this->clientNameFilter)) {
            $query->where('invoice_id', 'LIKE', "%{$this->clientNameFilter}%");
        }

        // Get the filtered data
        $adminPayments = $query->get();

        return view('excel.admin_payments_excel', compact('adminPayments'));
    }
}
