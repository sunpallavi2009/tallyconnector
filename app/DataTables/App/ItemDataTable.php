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
            })
            ->addColumn('action', function (Item $items) {
                return view('app.excelImport._item-action', compact('items'));
            })
            ->editColumn('item_name', function (Item $item) {
                $previousItemName = $item->item_name ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousItemName . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="item_name" value="' . $previousItemName . '" data-id="' . $item->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('uom', function (Item $item) {
                $previousUom = $item->uom ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousUom . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="uom" value="' . $previousUom . '" data-id="' . $item->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('alias1', function (Item $item) {
                $previousAlias1 = $item->alias1 ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousAlias1 . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="alias1" value="' . $previousAlias1 . '" data-id="' . $item->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('alias2', function (Item $item) {
                $previousAlias2 = $item->alias2 ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousAlias2 . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="alias2" value="' . $previousAlias2 . '" data-id="' . $item->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('part_no', function (Item $item) {
                $previousPartNo = $item->part_no ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousPartNo . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="part_no" value="' . $previousPartNo . '" data-id="' . $item->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('item_desc', function (Item $item) {
                $previousItemDesc = $item->item_desc ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousItemDesc . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="item_desc" value="' . $previousItemDesc . '" data-id="' . $item->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('hsn_code', function (Item $item) {
                $previousHsnCode = $item->hsn_code ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousHsnCode . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="hsn_code" value="' . $previousHsnCode . '" data-id="' . $item->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('hsn_desc', function (Item $item) {
                $previousHsnDesc = $item->hsn_desc ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousHsnDesc . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="hsn_desc" value="' . $previousHsnDesc . '" data-id="' . $item->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('taxability', function (Item $item) {
                $previousTaxability = $item->taxability ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousTaxability . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="taxability" value="' . $previousTaxability . '" data-id="' . $item->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('gst_rate', function (Item $item) {
                $previousGstRate = $item->gst_rate ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousGstRate . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="gst_rate" value="' . $previousGstRate . '" data-id="' . $item->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('applicable_from', function (Item $item) {
                $previousApplicableFrom = $item->applicable_from ?? 'NULL'; // Get the previous applicable date
                $inputField = '<span class="editable-input">' . $previousApplicableFrom . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="applicable_from" value="' . $previousApplicableFrom . '" data-id="' . $item->id . '" type="date">';
                return $inputField;
            })
            ->editColumn('cgst_rate', function (Item $item) {
                $previousCgstRate = $item->cgst_rate ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousCgstRate . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="cgst_rate" value="' . $previousCgstRate . '" data-id="' . $item->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('sgst_rate', function (Item $item) {
                $previousSgstRate = $item->sgst_rate ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousSgstRate . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="sgst_rate" value="' . $previousSgstRate . '" data-id="' . $item->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('igst_rate', function (Item $item) {
                $previousIgstRate = $item->igst_rate ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousIgstRate . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="igst_rate" value="' . $previousIgstRate . '" data-id="' . $item->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('opening_qty', function (Item $item) {
                $previousOpeningQty = $item->opening_qty ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousOpeningQty . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="opening_qty" value="' . $previousOpeningQty . '" data-id="' . $item->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('rate', function (Item $item) {
                $previousRate = $item->rate ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousRate . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="rate" value="' . $previousRate . '" data-id="' . $item->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('amount', function (Item $item) {
                $previousAmount = $item->amount ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousAmount . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="amount" value="' . $previousAmount . '" data-id="' . $item->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('gst_type_of_supply', function (Item $item) {
                $previousGstTypeOfSupply = $item->gst_type_of_supply ?? 'NULL'; // Get the previous GST registration type
                return '<select class="edit-select form-control" data-id="' . $item->id . '">' .
                    '<option value="Goods" ' . ($previousGstTypeOfSupply === "Goods" ? "selected" : "") . '>Goods</option>' .
                    '<option value="Service" ' . ($previousGstTypeOfSupply === "Service" ? "selected" : "") . '>Service</option>' .
                    '</select>';
            })

            ->rawColumns(['action','item_name','uom','alias1','alias2','part_no','item_desc','hsn_code','hsn_desc','taxability','gst_rate','applicable_from','cgst_rate','sgst_rate','igst_rate','opening_qty','rate','amount','gst_type_of_supply']);
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
        return 'Ledger_' . date('YmdHis');
    }
}
