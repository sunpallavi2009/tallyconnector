<?php

namespace App\DataTables\Admin;

use App\Models\Ledger;
use App\Facades\UtilityFacades;
use Yajra\DataTables\Html\Column;
use App\Models\SalePurchaseInvoice;
use Yajra\DataTables\Services\DataTable;
use function Symfony\Component\Translation\t;

class CreditNoteDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('checkbox', function () {
                return null; // Set checkbox column data to null for all rows
            })
            ->addColumn('action', function (SalePurchaseInvoice $credits) {
                return view('admin.credit-note._action', compact('credits'));
            })
            ->editColumn('created_at', function ($request) {
                return UtilityFacades::date_time_format($request->created_at);
            })

            ->rawColumns(['action']);
    }

    public function query(SalePurchaseInvoice $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('credit-note-table')
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
//                    ['extend' => 'create', 'className' => 'btn btn-light-primary no-corner me-1 add_module', 'action' => " function ( e, dt, node, config ) {
//                        window.location = '" . route('faqs.create') . "';
//                   }"],
//                    [
//                        'extend' => 'collection', 'className' => 'btn btn-light-secondary me-1 dropdown-toggle', 'text' => '<i class="ti ti-download"></i> Export', "buttons" => [
//                        ["extend" => "print", "text" => '<i class="fas fa-print"></i> Print', "className" => "btn btn-light text-primary dropdown-item", "exportOptions" => ["columns" => [0, 1, 3]]],
//                        ["extend" => "csv", "text" => '<i class="fas fa-file-csv"></i> CSV', "className" => "btn btn-light text-primary dropdown-item", "exportOptions" => ["columns" => [0, 1, 3]]],
//                        ["extend" => "excel", "text" => '<i class="fas fa-file-excel"></i> Excel', "className" => "btn btn-light text-primary dropdown-item", "exportOptions" => ["columns" => [0, 1, 3]]],
//                        ["extend" => "pdf", "text" => '<i class="fas fa-file-pdf"></i> PDF', "className" => "btn btn-light text-primary dropdown-item", "exportOptions" => ["columns" => [0, 1, 3]]],
//                    ],
//                    ],
//                    ['extend' => 'reset', 'className' => 'btn btn-light-danger me-1'],
//                    ['extend' => 'reload', 'className' => 'btn btn-light-warning'],
                ],
                "fixedHeader" => true,
                "fixedColumn" => true,
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
//                    'create' => __('Create'),
//                    'export' => __('Export'),
//                    'print' => __('Print'),
//                    'reset' => __('Reset'),
//                    'reload' => __('Reload'),
//                    'excel' => __('Excel'),
//                    'csv' => __('CSV'),
                ]
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('checkbox')
                ->name('checkbox')
                ->title('<input type="checkbox" id="select-all-checkbox">')
                ->width(10)
                ->addClass('text-center')
                ->orderable(false)
                ->searchable(false)
                ->printable(false)
                ->exportable(false)
                ->footer('')
                ->render('function() {
        return \'<input type="checkbox" class="select-row-checkbox">\';
    }'),
            Column::make('No')->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('inv_date')->title(__('Invoice Date'))->addClass('fixed-column')->titleClass('fixed-column'),
            Column::make('inv_no')->title(__('Invoice No'))->addClass('fixed-second-column')->titleClass('fixed-second-column'),
            Column::make('bill_ref_no')->title(__('Bill Ref No')),
            Column::make('voucher_type')->title(__('Voucher Type')),
            Column::make('party_name')->title(__('Party Name')),
            Column::make('buyer_name')->title(__('Buyer/Mailing Name')),
            Column::make('address1')->title(__('Address 1')),
            Column::make('address2')->title(__('Address 2')),
            Column::make('state')->title(__('State')),
            Column::make('country')->title(__('Country')),
            Column::make('gst_in')->title(__('GSTIN/UIN')),
            Column::make('gst_reg_type')->title(__('GST Registration Type')),
            Column::make('place_of_supply')->title(__('Place of Supply')),
            Column::make('company_reg_type')->title(__('Company State/ Registration Type')),
            Column::make('item_name')->title(__('Item Name')),
            Column::make('item_desc')->title(__('Item Description1')),
            Column::make('qty')->title(__('QTY')),
            Column::make('uom')->title(__('UOM')),
            Column::make('item_rate')->title(__('Item Rate')),
            Column::make('gst_rate')->title(__('GST Rate')),
            Column::make('taxable')->title(__('TAXABLE')),
            Column::make('narration')->title(__('Narration')),
            Column::make('sgst')->title(__('sgst')),
            Column::make('cgst')->title(__('cgst')),
            Column::make('igst')->title(__('igst')),
            Column::make('cess')->title(__('cess')),
            Column::make('discount')->title(__('discount')),
            Column::make('inv_amt')->title(__('invoice amount')),
            Column::make('original_invoice_no')->title(__('Original Invoice No')),
            Column::make('original_invoice_date')->title(__('Original Invoice Date')),
            Column::make('reason_code')->title(__('Reason Code')),
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
        return 'SalePurchase_' . date('YmdHis');
    }
}
