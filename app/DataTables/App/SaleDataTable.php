<?php

namespace App\DataTables\App;

use Carbon\Carbon;
use App\Facades\UtilityFacades;
use Yajra\DataTables\Html\Column;
use App\Models\SalePurchaseInvoice;
use Yajra\DataTables\Services\DataTable;

class SaleDataTable extends DataTable
{
    public function dataTable($query)
    {
        $dataTable = datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($request) {
                return Carbon::parse($request->created_at)->format('Y-m-d H:i:s');
            });

//        $dataTable->filter(function ($query) {
//            if (request()->has('voucher_type') && !empty(request()->voucher_type)) {
//                $query->where('voucher_type', request()->voucher_type);
//            }
//        });

        return $dataTable;
    }

    public function query(SalePurchaseInvoice $model)
    {
        $query = $model->newQuery()->where('voucher_type', 'Sales');

        if(request()->has('start_date') && request()->has('end_date')) {
            $query->whereBetween('inv_date', [request('start_date'), request('end_date')]);
        }
        return $query;

    }

    public function html()
    {
        return $this->builder()
            ->setTableId('sale-table')
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
                    // Buttons configuration...
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
                    // Button language...
                ]
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::make('No')->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('inv_date')->title(__('Invoice Date')),
            Column::make('inv_no')->title(__('Invoice No')),
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
            Column::make('tags')->title(__('Tags')),
            Column::make('created_at')->title(__('Created At')),
        ];
    }

    protected function filename(): string
    {
        return 'SalePurchase_' . date('YmdHis');
    }
}
