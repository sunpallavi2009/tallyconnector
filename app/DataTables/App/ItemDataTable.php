<?php

namespace App\DataTables\App;

use Carbon\Carbon;
use App\Models\Item;
use App\Facades\UtilityFacades;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ItemDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($request) {
                return Carbon::parse($request->created_at)->format('Y-m-d H:i:s');
            });
    }

    public function query(Item $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('item-table')
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
            Column::make('item_name')->title(__('Item Name')),
            Column::make('uom')->title(__('UOM')),
            Column::make('alias1')->title(__('Item Code / Alias 1')),
            Column::make('alias2')->title(__('Item Code / Alias 2')),
            Column::make('part_no')->title(__('Part No')),
            Column::make('item_desc')->title(__('Item Description')),
            Column::make('hsn_code')->title(__('HSN Code')),
            Column::make('hsn_desc')->title(__('HSN Discription')),
            Column::make('taxability')->title(__('Taxability')),
            Column::make('gst_rate')->title(__('GST Rate')),
            Column::make('applicable_from')->title(__('Applicable From')),
            Column::make('cgst_rate')->title(__('CGST Rate')),
            Column::make('sgst_rate')->title(__('SGST Rate')),
            Column::make('igst_rate')->title(__('IGST Rate')),
            Column::make('opening_qty')->title(__('Opening QTY')),
            Column::make('rate')->title(__('Rate')),
            Column::make('amount')->title(__('Amount')),
            Column::make('gst_type_of_supply')->title(__('GST TYPE OF SUPPLY')),
            Column::make('tags')->title(__('Tags')),
            Column::make('created_at')->title(__('Created At')),
        ];
    }

    protected function filename(): string
    {
        return 'Ledger_' . date('YmdHis');
    }
}
