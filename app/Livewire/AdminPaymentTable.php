<?php

namespace App\Livewire;

use App\Models\AdminPayment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AdminPaymentTable extends LivewireTableComponent
{
    protected $model = AdminPayment::class;
    protected string $tableName = 'admin_payments';

    public $showButtonOnHeader = true;
    public $buttonComponent = 'payments.components.add-button';

    protected $listeners = ['resetPageTable', 'dateFilter', 'clientNameFilter'];

    public $paymentDateFilter = [];
    public $clientNameFilter = '';

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('created_at', 'desc');
        $this->setQueryStringStatus(false);
        $this->setThAttributes(function (Column $column) {
            return ($column->isField('amount')) ? ['class' => 'd-flex justify-content-end'] : ['class' => 'text-center'];
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($columnIndex == '6') {
                return ['class' => 'text-center'];
            }
            if ($column->getField() === 'amount') {
                return ['class' => 'text-end'];
            }
            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.invoices'), 'invoice_id')
                ->sortable()
                ->searchable()
                ->view('payments.components.client-name'),
            Column::make('First Name', 'invoice.client.user.first_name')
                ->sortable()
                ->searchable()
                ->hideIf(1),
            Column::make('First Name', 'invoice.invoice_id')
                ->sortable()
                ->searchable()
                ->hideIf(1),
            Column::make('Last Name', 'invoice.client.user.last_name')
                ->sortable()
                ->searchable()
                ->hideIf(1),
            Column::make(__('messages.payment.payment_date'), 'payment_date')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row, Column $column) {
                    return view('transactions.components.invoice-id-payment-date')
                        ->withValue(['payment-date' => $row->payment_date]);
                }),
            Column::make(__('messages.invoice.amount'), 'amount')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row, Column $column) {
                    return getInvoiceCurrencyAmount($row->amount, $row->invoice->currency_id, true);
                }),
           Column::make(__('messages.invoice.payment_method'), 'payment_mode')
            ->searchable()
            ->view('transactions.components.payment-mode'),
            Column::make(__('messages.common.action'), 'id')
                ->format(function ($value, $row, Column $column) {
                    return view('livewire.modal-action-button')
                        ->with([
                            'dataId' => $row->id,
                            'editClass' => 'payment-edit-btn',
                            'deleteClass' => 'payment-delete-btn',
                        ]);
                }),
        ];
    }

    public function builder(): Builder
    {
        $query = AdminPayment::with(['invoice.client.user.media'])->select('admin_payments.*');

        if (!empty($this->paymentDateFilter) && count($this->paymentDateFilter) === 2) {
            $startDate = Carbon::parse($this->paymentDateFilter[0])->startOfDay();
            $endDate = Carbon::parse($this->paymentDateFilter[1])->endOfDay();
            $query->whereBetween('payment_date', [$startDate, $endDate]);
        }
        
        if (!empty($this->clientNameFilter)) {
            $query->whereHas('invoice.client.user', function ($q) {
                $q->where('full_name', 'like', '%' . $this->clientNameFilter . '%');
            });
        }

        return $query;
    }

    public function dateFilter($startDate = null, $endDate = null)
    {
        if (!empty($startDate) && !empty($endDate)) {
            $this->paymentDateFilter = [$startDate, $endDate];
        } else {
            $this->paymentDateFilter = [];
        }
        
        $this->setBuilder($this->builder());
        $this->resetPagination();
    }

    public function clientNameFilter($clientName)
    {
        $this->clientNameFilter = $clientName;
        $this->setBuilder($this->builder());
        $this->resetPagination();
    }

    public function resetPageTable()
    {
        $this->customResetPage('admin_paymentsPage');
    }

    public function resetPagination()
    {
        $this->resetPage('admin_paymentsPage');
    }
}