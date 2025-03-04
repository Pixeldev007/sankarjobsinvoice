<?php

namespace App\Livewire;

use App\Models\payment_method;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Components\TableComponent;
use Livewire\WithPagination;

class PaymentMethodTable extends LivewireTableComponent
{
    use WithPagination;

    protected $model = payment_method::class;
    protected string $tableName = 'payment_methods';

    public $showButtonOnHeader = true;
    public $buttonComponent = 'payment_report.components.add-button';

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('created_at', 'desc');
        $this->setQueryStringStatus(false);

        $this->setThAttributes(function (Column $column) {
            if ($column->getField() == 'id') {
                return ['style' => 'width:9%;text-align:center'];
            }
            return [];
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($column->getField() === 'payment_method') {
                return ['class' => 'w-50'];
            }
            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable()->searchable(),
            Column::make('Payment Method', 'payment_method')->sortable()->searchable(),
            Column::make('Actions', 'id')->format(function ($value, $row, Column $column) {
                return view('payment_report.modal-action-button')
                    ->with(['dataId' => $row->id, 'editClass' => 'qrcode-edit-btn']);
            }),
        ];
    }

    public function builder(): Builder
    {
        return payment_method::query()->select('payment_methods.*');
    }

    public function resetPageTable()
    {
        $this->customResetPage('payment_methodsPage');
    }

    public function edit($id)
    {
        return redirect()->route('payment_methods.edit', $id);
    }

    public function destroy($id)
    {
        return redirect()->route('payment_methods.destroy', $id);
    }
    // public function delete($id)
    // {
    //     payment_method::destroy($id);
    //     $this->resetPageTable();
    // }
}
