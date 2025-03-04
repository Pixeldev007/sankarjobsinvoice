<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AdminInvoicesExport implements FromView
{
    protected $invoices;

    public function __construct($invoices)
    {
        $this->invoices = $invoices;
    }

    public function view(): View
    {
        // Pass the invoices to the view
        return view('excel.admin_invoices_excel', [
            'invoices' => $this->invoices
        ]);
    }
}
