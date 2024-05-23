<?php

namespace App\DataTables\App;

use Carbon\Carbon;
use App\Models\Ecommerce;
use App\Facades\UtilityFacades;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class EcommerceDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('checkbox', function () {
                return null; // Set checkbox column data to null for all rows
            })
            ->editColumn('created_at', function ($request) {
                return Carbon::parse($request->created_at)->format('Y-m-d H:i:s');
            });

//        $dataTable->filter(function ($query) {
//            if (request()->has('voucher_type') && !empty(request()->voucher_type)) {
//                $query->where('voucher_type', request()->voucher_type);
//            }
//        });

        // return $dataTable;
    }

    public function query(Ecommerce $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('ecommerce-table')
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
                'buttons' => [
                    [
                        'extend' => 'collection',
                        'className' => 'btn btn-light-secondary me-1 dropdown-toggle',
                        'text' => '<i class="ti ti-download"></i> Export',
                        'buttons' => [
                            [
                                'extend' => 'excel',
                                'text' => '<i class="fas fa-file-excel"></i> Excel',
                                'className' => 'btn btn-light text-primary dropdown-item',
                                'exportOptions' => [
                                    'modifier' => [
                                        'selected' // Export only selected rows
                                    ],
                                    'columns' => [1, 2, 3] // Adjust column indices as needed
                                ]
                            ],
                            [
                                'extend' => '',
                                'text' => '<i class="fas fa-file-medical"></i> send to tally',
                                'className' => 'btn btn-light text-primary dropdown-item send-to-tally', // Added class 'send-to-tally'
                                'exportOptions' => [
                                    'modifier' => [
                                        'selected' // Export only selected rows
                                    ],
                                    'columns' => [1, 2, 3] // Adjust column indices as needed
                                ]
                            ],
                        ]
                    ]
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
                    'create' => __('Create'),
                    'export' => __('Export'),
                    'print' => __('Print'),
                    'reset' => __('Reset'),
                    'reload' => __('Reload'),
                    'excel' => __('Excel'),
                    'csv' => __('CSV'),
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
            Column::make('seller_gst_in')->title(__('Seller Gstin')),
            Column::make('inv_no')->title(__('Invoice No')),
            Column::make('inv_date')->title(__('Invoice Date')),
            Column::make('item_name')->title(__('Asin')),
            Column::make('quantity')->title(__('Quantity')),
            Column::make('hsn_code')->title(__('Hsn/sac')),
            Column::make('item_alias')->title(__('Sku')),
            Column::make('inv_amount')->title(__('Invoice Amount')),
            Column::make('taxable_amount')->title(__('Total Tax Amount')),
            Column::make('cgst')->title(__('Cgst Tax')),
            Column::make('sgst')->title(__('Sgst Tax')),
            Column::make('igst')->title(__('Igst Tax')),
            Column::make('utgst')->title(__('Utgst Tax')),
            Column::make('cess')->title(__('Compensatory Cess Tax')),
            Column::make('created_at')->title(__('Created At')),
        ];
    }

    protected function filename(): string
    {
        return 'bank_' . date('YmdHis');
    }
}
