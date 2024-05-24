<?php

namespace App\DataTables\App;

use Carbon\Carbon;
use App\Models\Bank;
use App\Facades\UtilityFacades;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BankDataTable extends DataTable
{
    protected $transactionTypes = [
        'ATM' => 'ATM',
        'Card' => 'Card',
        'Cash' => 'Cash',
        'Cheque/DD' => 'Cheque/DD',
        'ECS' => 'ECS',
        'e-Fund Transfer' => 'e-Fund Transfer',
        'Electronic Cheque' => 'Electronic Cheque',
        'Electronic DD/PO' => 'Electronic DD/PO',
        'Others' => 'Others',
    ];

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($request) {
                return Carbon::parse($request->created_at)->format('Y-m-d H:i:s');
            })
            ->addColumn('action', function (Bank $bankReceiptPayment) {
                return view('app.excelImport._bankReceiptPayment-action', compact('bankReceiptPayment'));
            })
            ->editColumn('trans_date', function (Bank $bankReceiptPayment) {
                $previousTransDate = $bankReceiptPayment->trans_date ?? 'NULL'; // Get the previous applicable date
                $inputField = '<span class="editable-input">' . $previousTransDate . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="trans_date" value="' . $previousTransDate . '" data-id="' . $bankReceiptPayment->id . '" type="date">';
                return $inputField;
            })
            ->editColumn('voucher_no', function (Bank $bankReceiptPayment) {
                $previousVoucherNo = $bankReceiptPayment->voucher_no ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousVoucherNo . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="voucher_no" value="' . $previousVoucherNo . '" data-id="' . $bankReceiptPayment->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('cheque_no', function (Bank $bankReceiptPayment) {
                $previousChequeNo = $bankReceiptPayment->cheque_no ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousChequeNo . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="cheque_no" value="' . $previousChequeNo . '" data-id="' . $bankReceiptPayment->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('description', function (Bank $bankReceiptPayment) {
                $previousDescription = $bankReceiptPayment->description ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousDescription . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="description" value="' . $previousDescription . '" data-id="' . $bankReceiptPayment->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('debit_amt', function (Bank $bankReceiptPayment) {
                $previousDebitAmt = $bankReceiptPayment->debit_amt ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousDebitAmt . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="debit_amt" value="' . $previousDebitAmt . '" data-id="' . $bankReceiptPayment->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('credit_amt', function (Bank $bankReceiptPayment) {
                $previousCreditAmt = $bankReceiptPayment->credit_amt ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousCreditAmt . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="credit_amt" value="' . $previousCreditAmt . '" data-id="' . $bankReceiptPayment->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('voucher_type', function (Bank $bankReceiptPayment) {
                $previousVoucherType = $bankReceiptPayment->voucher_type ?? 'NULL'; 
                return '<select class="edit-vouchertype-select form-control" data-id="' . $bankReceiptPayment->id . '">' .
                    '<option value="Receipt" ' . ($previousVoucherType === "Receipt" ? "selected" : "") . '>Receipt</option>' .
                    '<option value="Payment" ' . ($previousVoucherType === "Payment" ? "selected" : "") . '>Payment</option>' .
                    '<option value="Journal" ' . ($previousVoucherType === "Journal" ? "selected" : "") . '>Journal</option>' .
                    '</select>';
            })
            ->editColumn('ledger_name', function (Bank $bankReceiptPayment) {
                $previousLedgerName = $bankReceiptPayment->ledger_name ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousLedgerName . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="ledger_name" value="' . $previousLedgerName . '" data-id="' . $bankReceiptPayment->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('bank_name', function (Bank $bankReceiptPayment) {
                $previousBankName = $bankReceiptPayment->bank_name ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousBankName . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="bank_name" value="' . $previousBankName . '" data-id="' . $bankReceiptPayment->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('instrument_date', function (Bank $bankReceiptPayment) {
                $previousInstrumentDate = $bankReceiptPayment->instrument_date ?? 'NULL'; // Get the previous applicable date
                $inputField = '<span class="editable-input">' . $previousInstrumentDate . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="instrument_date" value="' . $previousInstrumentDate . '" data-id="' . $bankReceiptPayment->id . '" type="date">';
                return $inputField;
            })
            ->editColumn('transection_type', function (Bank $bankReceiptPayment) {
                $previousTransectionType = $bankReceiptPayment->transection_type ?? 'NULL'; // Get the previous state or an empty string if null
                $selectOptions = '';
                foreach ($this->transactionTypes as $type) {
                    $selected = ($type == $previousTransectionType) ? 'selected' : ''; // Check if the code matches the previous state
                    $selectOptions .= '<option value="' . $type . '" ' . $selected . '>' . $type . '</option>';
                }
                $selectField = '<select class="edit-transectionType-select form-control" name="transection_type" data-id="' . $bankReceiptPayment->id . '">' . $selectOptions . '</select>';
                return $selectField;
            })
            ->editColumn('fav_name', function (Bank $bankReceiptPayment) {
                $previousFavName = $bankReceiptPayment->fav_name ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousFavName . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="fav_name" value="' . $previousFavName . '" data-id="' . $bankReceiptPayment->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('bank_date', function (Bank $bankReceiptPayment) {
                $previousBankDate = $bankReceiptPayment->bank_date ?? 'NULL'; // Get the previous applicable date
                $inputField = '<span class="editable-input">' . $previousBankDate . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="bank_date" value="' . $previousBankDate . '" data-id="' . $bankReceiptPayment->id . '" type="date">';
                return $inputField;
            })

            ->rawColumns(['action','trans_date','voucher_no','cheque_no','description','debit_amt','credit_amt','voucher_type','ledger_name','bank_name','instrument_date','transection_type','fav_name','bank_date']);
    }

    public function query(Bank $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('bank-table')
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
            Column::make('trans_date')->title(__('Transaction Date')),
            Column::make('voucher_no')->title(__('Voucher No')),
            Column::make('cheque_no')->title(__('Cheque No')),
            Column::make('description')->title(__('Description / Narration')),
            Column::make('debit_amt')->title(__('Debit Amount')),
            Column::make('credit_amt')->title(__('Credit Amount')),
            Column::make('voucher_type')->title(__('Voucher Type')),
            Column::make('ledger_name')->title(__('Ledger Name')),
            Column::make('bank_name')->title(__('Bank Name')),
            Column::make('instrument_date')->title(__('Instrument Date')),
            Column::make('transection_type')->title(__('Transaction Type')),
            Column::make('fav_name')->title(__('Favouring Name')),
            Column::make('bank_date')->title(__('Bank Date')),
            Column::make('tags')->title(__('Tags')),
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
