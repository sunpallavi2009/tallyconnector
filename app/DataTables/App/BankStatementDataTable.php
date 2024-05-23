<?php

namespace App\DataTables\App;

use Carbon\Carbon;
use App\Models\Bank;
use App\Facades\UtilityFacades;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BankStatementDataTable extends DataTable
{
    public function dataTable($query)
    {
        $dataTable = datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($request) {
                return Carbon::parse($request->created_at)->format('Y-m-d H:i:s');
            })
            ->filter(function ($query) {
                if (request()->has('voucher_type') && !empty(request()->voucher_type)) {
                    $query->where('voucher_type', request()->voucher_type);
                }
            })
            ->addColumn('action', function (Bank $banks) {
                return view('admin.bank-statement._action', compact('banks'));
            })

            ->editColumn('voucher_no', function (Bank $bank) {
                $previousVoucherNo = $bank->voucher_no ?? 'NULL'; // Get the previous alias
                $inputField = '<span class="editable-input">' . $previousVoucherNo . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="voucher_no" value="' . $previousVoucherNo . '" data-id="' . $bank->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('cheque_no', function (Bank $bank) {
                $previousChequeNo = $bank->cheque_no ?? 'NULL'; // Get the previous alias
                $inputField = '<span class="editable-input">' . $previousChequeNo . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="cheque_no" value="' . $previousChequeNo . '" data-id="' . $bank->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('trans_date', function (Bank $bank) {
                $previousTransDate = $bank->trans_date ?? 'NULL'; // Get the previous applicable date
                $inputField = '<span class="editable-input">' . $previousTransDate . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="trans_date" value="' . $previousTransDate . '" data-id="' . $bank->id . '" type="date">';
                return $inputField;
            })
            ->editColumn('description', function (Bank $bank) {
                $previousDescription = $bank->description ?? 'NULL'; // Get the previous alias
                $inputField = '<span class="editable-input">' . $previousDescription . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="description" value="' . $previousDescription . '" data-id="' . $bank->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('debit_amt', function (Bank $bank) {
                $previousDebitAmt = $bank->debit_amt ?? 'NULL'; // Get the previous alias
                $inputField = '<span class="editable-input">' . $previousDebitAmt . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="debit_amt" value="' . $previousDebitAmt . '" data-id="' . $bank->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('credit_amt', function (Bank $bank) {
                $previousCreditAmt = $bank->credit_amt ?? 'NULL'; // Get the previous alias
                $inputField = '<span class="editable-input">' . $previousCreditAmt . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="credit_amt" value="' . $previousCreditAmt . '" data-id="' . $bank->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('voucher_type', function (Bank $bank) {
                $previousVoucherType = $bank->voucher_type ?? 'NULL'; // Get the previous GST registration type
                return '<select class="edit-select form-control" data-id="' . $bank->id . '">' .
                    '<option value="Receipt" ' . ($previousVoucherType === "Receipt" ? "selected" : "") . '>Receipt</option>' .
                    '<option value="Payment" ' . ($previousVoucherType === "Payment" ? "selected" : "") . '>Payment</option>' .
                    '</select>';
            })
            ->editColumn('ledger_name', function (Bank $bank) {
                $previousLedgerName = $bank->ledger_name ?? 'NULL'; // Get the previous alias
                $inputField = '<span class="editable-input">' . $previousLedgerName . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="ledger_name" value="' . $previousLedgerName . '" data-id="' . $bank->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('bank_name', function (Bank $bank) {
                $previousBankName = $bank->bank_name ?? 'NULL'; // Get the previous alias
                $inputField = '<span class="editable-input">' . $previousBankName . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="bank_name" value="' . $previousBankName . '" data-id="' . $bank->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('instrument_date', function (Bank $bank) {
                $previousInstrumentDate = $bank->instrument_date ?? 'NULL'; // Get the previous applicable date
                $inputField = '<span class="editable-input">' . $previousInstrumentDate . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="instrument_date" value="' . $previousInstrumentDate . '" data-id="' . $bank->id . '" type="date">';
                return $inputField;
            })
            ->editColumn('transection_type', function (Bank $bank) {
                $previousTransectionType = $bank->transection_type ?? 'NULL'; // Get the previous GST registration type
                return '<select class="edit-select form-control" data-id="' . $bank->id . '">' .
                    '<option value="Others" ' . ($previousTransectionType === "Others" ? "selected" : "") . '>Others</option>' .
                    // '<option value="Payment" ' . ($previousTransectionType === "Payment" ? "selected" : "") . '>Payment</option>' .
                    '</select>';
            })
            ->editColumn('fav_name', function (Bank $bank) {
                $previousFavName = $bank->fav_name ?? 'NULL'; // Get the previous alias
                $inputField = '<span class="editable-input">' . $previousFavName . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="fav_name" value="' . $previousFavName . '" data-id="' . $bank->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('bank_date', function (Bank $bank) {
                $previousBankDate = $bank->bank_date ?? 'NULL'; // Get the previous applicable date
                $inputField = '<span class="editable-input">' . $previousBankDate . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="bank_date" value="' . $previousBankDate . '" data-id="' . $bank->id . '" type="date">';
                return $inputField;
            })

            ->rawColumns(['action','voucher_no','cheque_no','trans_date','description','debit_amt','credit_amt','ledger_name','voucher_type','bank_name','instrument_date','transection_type','fav_name','bank_date']);

        return $dataTable;
    }

    public function query(Bank $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('bank-statement-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->language([
                "paginate" => [
                    "next" => '<i class="ti ti-chevron-right"></i>',
                    "previous" => '<i class="ti ti-chevron-left"></i>'
                ],
                'lengthMenu' => __('_MENU_ entries per page'),
                "searchPlaceholder" => __('Search...'), "search" => ""
            ])
            ->initComplete('function() {
                var table = this;
                var searchInput = $(\'#\'+table.api().table().container().id+\' label input[type="search"]\');
                searchInput.removeClass(\'form-control form-control-sm\');
                searchInput.addClass(\'dataTable-input\');
                var select = $(table.api().table().container()).find(".dataTables_length select").removeClass(\'custom-select custom-select-sm form-control form-control-sm\').addClass(\'dataTable-selector\');
            }')
            ->parameters([
                "dom" =>  "
                               <'dataTable-top row'<'dataTable-dropdown page-dropdown col-lg-2 col-sm-12'l><'dataTable-botton table-btn col-lg-6 col-sm-12'B><'dataTable-search tb-search col-lg-3 col-sm-12'f>>
                             <'dataTable-container'<'col-sm-12'tr>>
                             <'dataTable-bottom row'<'col-sm-5'i><'col-sm-7'p>>
                               ",
                'buttons'   => [
                ],
                "scrollX" => true,
                "drawCallback" => 'function( settings ) {
                    var tooltipTriggerList = [].slice.call(
                        document.querySelectorAll("[data-bs-toggle=tooltip]")
                      );
                      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                      });
                      var popoverTriggerList = [].slice.call(
                        document.querySelectorAll("[data-bs-toggle=popover]")
                      );
                      var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                        return new bootstrap.Popover(popoverTriggerEl);
                      });
                      var toastElList = [].slice.call(document.querySelectorAll(".toast"));
                      var toastList = toastElList.map(function (toastEl) {
                        return new bootstrap.Toast(toastEl);
                      });
                }'
            ])->language([
                'buttons' => [
                ]
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::make('No')->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('trans_date')->title(__('Transaction Date'))->addClass('fixed-column')->titleClass('fixed-column'),
            Column::make('voucher_no')->title(__('Voucher No'))->addClass('fixed-second-column')->titleClass('fixed-second-column'),
            Column::make('cheque_no')->title(__('Cheque No')),
            Column::make('description')->title(__('Description / Narration'))->editable(),
            Column::make('debit_amt')->title(__('Debit Amount')),
            Column::make('credit_amt')->title(__('Credit Amount')),
            Column::make('voucher_type')->title(__('Voucher Type')),
            Column::make('ledger_name')->title(__('Ledger Name')),
            Column::make('bank_name')->title(__('Bank Name')),
            Column::make('instrument_date')->title(__('Instrument Date')),
            Column::make('transection_type')->title(__('Transaction Type')),
            Column::make('fav_name')->title(__('Favouring Name')),
            Column::make('bank_date')->title(__('Bank Date')),
            Column::make('created_at')->title(__('Created At')),
            Column::computed('action')->title(__('Action'))
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center')
                ->width('20%'),
        ];
    }

    protected function filename(): string
    {
        return 'bank_' . date('YmdHis');
    }
}
