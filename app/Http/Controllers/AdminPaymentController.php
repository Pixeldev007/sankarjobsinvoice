<?php

namespace App\Http\Controllers;

use App\Exports\AdminPaymentsExport;
use App\Http\Requests\CreateAdminPaymentRequest;
use App\Models\AdminPayment;
use App\Models\Invoice;
use App\Models\Payment;
use App\Repositories\AdminPaymentRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminPaymentController extends AppBaseController
{
    /** @var AdminPaymentRepository */
    public $adminPaymentRepository;

    public function __construct(AdminPaymentRepository $adminPaymentRepo)
    {
        $this->adminPaymentRepository = $adminPaymentRepo;
    }

    public function index(): View|Factory|Application
{
    // Fetch all invoices that are not PAID or DRAFT and include the client relationship
    $invoices = Invoice::whereNotIn('status', [Invoice::PAID, Invoice::DRAFT])
        ->with('client') // Ensure the client relationship is loaded
        ->orderBy('created_at', 'desc')
        ->get();

    return view('payments.index', compact('invoices'));
}


    public function store(CreateAdminPaymentRequest $request)
    {
        $input = $request->all();

        try {
            $payment = $this->adminPaymentRepository->store($input);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage());
        }

        return $this->sendResponse($payment, __('messages.flash.payment_saved_successfully'));
    }

    public function edit(AdminPayment $payment): JsonResponse
    {
        $invoiceId = $payment->invoice->id;
        $payment['currencyCode'] = getInvoiceCurrencyIcon($payment->invoice->currency_id);
        $payment['invoice'] = $payment->invoice->invoice_id;
        $payment['DueAmount'] = $this->getInvoiceDueAmount($invoiceId);

        return $this->sendResponse($payment, 'payment retrieved successfully.');
    }

    public function update(CreateAdminPaymentRequest $request): JsonResponse
    {
        $input = $request->all();
        $this->adminPaymentRepository->updatePayment($input);

        return $this->sendSuccess(__('messages.flash.payment_updated_successfully'));
    }

    public function destroy(AdminPayment $payment): JsonResponse
    {
        $this->adminPaymentRepository->adminDeletePayment($payment);

        return $this->sendSuccess(__('messages.flash.payment_deleted_successfully'));
    }

    public function getInvoiceDueAmount($id): mixed
    {
        $data = [];
        /** @var Invoice $invoice */
        $invoice = Invoice::whereId($id)->with('payments')->firstOrFail();

        $paidAmount = $invoice->payments()->where('is_approved', Payment::APPROVED)->sum('amount');
        $dueAmount = $invoice->final_amount - $paidAmount;

        $data['currencyCode'] = getInvoiceCurrencyIcon($invoice->currency_id);
        $data['totalDueAmount'] = $dueAmount;
        $data['totalPaidAmount'] = $paidAmount;

        return $this->sendResponse($data, __('messages.flash.invoice_due_amount_retrieve_successfully'));
    }

 public function exportAdminPaymentsPDF(Request $request)
{
    // Get the filters from the request
    $paymentDateFilter = $request->input('paymentDateFilter', []);
    $clientNameFilter = $request->input('clientNameFilter', '');
    $exportType = $request->input('exportType', ''); // Add export type parameter

    // Build the query with the filters
    $query = AdminPayment::with(['invoice.client.user.media'])->select('admin_payments.*');

    if (!empty($paymentDateFilter) && count($paymentDateFilter) > 0) {
        $startDate = Carbon::parse($paymentDateFilter[0])->format('Y-m-d');
        $endDate = Carbon::parse($paymentDateFilter[1])->format('Y-m-d');
        $query->whereBetween('payment_date', [$startDate, $endDate]);
    } else {
        $defaultDate = explode(' - ', getMonthlyData());
        $query->whereBetween('payment_date', [$defaultDate[0], $defaultDate[1]]);
    }

     if (!empty($clientNameFilter)) {
        $query->whereHas('invoice', function ($q) use ($clientNameFilter) {
            $q->where('client_id', 'LIKE', "%{$clientNameFilter}%");
        });
    }
    // Get the filtered data
    $adminPayments = $query->get();

    // Generate PDF and stream it
    $pdf = Pdf::loadView('payments.export_pdf', compact('adminPayments'));
    return $pdf->stream('admin_payments.pdf');
}

    
        public function exportAdminPaymentsExcel(Request $request)
    {
        $paymentDateFilter = $request->input('paymentDateFilter', []);
        $clientNameFilter = $request->input('clientNameFilter', '');

        return Excel::download(new AdminPaymentsExport($paymentDateFilter, $clientNameFilter), 'admin_payments.xlsx');
     }

    public function getCurrentDateFormat()
    {
        return currentDateFormat();
    }
}
