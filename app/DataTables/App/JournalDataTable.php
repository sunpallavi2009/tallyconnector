<?php

namespace App\DataTables\App;

use Carbon\Carbon;
use App\Models\Bank;
use App\Facades\UtilityFacades;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class JournalDataTable extends DataTable
{
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
            ->editColumn('trans_date', function (Bank $journal) {
                $previousTransDate = $journal->trans_date ?? 'NULL'; // Get the previous applicable date
                $inputField = '<span class="editable-input">' . $previousTransDate . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="trans_date" value="' . $previousTransDate . '" data-id="' . $journal->id . '" type="date">';
                return $inputField;
            })
            ->editColumn('voucher_no', function (Bank $journal) {
                $previousVoucherNo = $journal->voucher_no ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousVoucherNo . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="voucher_no" value="' . $previousVoucherNo . '" data-id="' . $journal->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('voucher_type', function (Bank $journal) {
                $previousVoucherType = $journal->voucher_type ?? 'NULL'; 
                return '<select class="edit-vouchertype-select form-control" data-id="' . $journal->id . '">' .
                    '<option value="Receipt" ' . ($previousVoucherType === "Receipt" ? "selected" : "") . '>Receipt</option>' .
                    '<option value="Payment" ' . ($previousVoucherType === "Payment" ? "selected" : "") . '>Payment</option>' .
                    '<option value="Journal" ' . ($previousVoucherType === "Journal" ? "selected" : "") . '>Journal</option>' .
                    '</select>';
            })
            ->editColumn('debit_amt', function (Bank $journal) {
                $previousDebitAmt = $journal->debit_amt ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousDebitAmt . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="debit_amt" value="' . $previousDebitAmt . '" data-id="' . $journal->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('credit_amt', function (Bank $journal) {
                $previousCreditAmt = $journal->credit_amt ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousCreditAmt . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="credit_amt" value="' . $previousCreditAmt . '" data-id="' . $journal->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('credit_ledgers', function (Bank $journal) {
                $previousCreditLedgers = $journal->credit_ledgers ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousCreditLedgers . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="credit_ledgers" value="' . $previousCreditLedgers . '" data-id="' . $journal->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('debit_ledgers', function (Bank $journal) {
                $previousDebitLedgers = $journal->debit_ledgers ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousDebitLedgers . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="debit_ledgers" value="' . $previousDebitLedgers . '" data-id="' . $journal->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('narration', function (Bank $journal) {
                $previousNarration = $journal->narration ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousNarration . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="narration" value="' . $previousNarration . '" data-id="' . $journal->id . '" type="text">';
                return $inputField;
            })

            ->rawColumns(['action','trans_date','voucher_no','voucher_type','debit_amt','credit_amt','credit_ledgers','debit_ledgers','narration']);
    }

    public function query(Bank $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('journal-table')
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
            Column::make('trans_date')->title(__('Date')),
            Column::make('voucher_no')->title(__('Voucher Number')),
            Column::make('voucher_type')->title(__('Voucher Type')),
            Column::make('debit_amt')->title(__('Debit Amount')),
            Column::make('credit_amt')->title(__('Credit Amount')),
            Column::make('credit_ledgers')->title(__('Credit Ledgers')),
            Column::make('debit_ledgers')->title(__('Debit Ledgers (Party Ledger)')),
            Column::make('narration')->title(__('Narration')),
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
        return 'journal_' . date('YmdHis');
    }
}
