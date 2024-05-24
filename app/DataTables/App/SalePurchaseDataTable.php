<?php

namespace App\DataTables\App;

use Carbon\Carbon;
use App\Facades\UtilityFacades;
use Yajra\DataTables\Html\Column;
use App\Models\SalePurchaseInvoice;
use Yajra\DataTables\Services\DataTable;

class SalePurchaseDataTable extends DataTable
{
    protected $states = [
        '01' => 'JAMMU AND KASHMIR',
        '02' => 'HIMACHAL PRADESH',
        '03' => 'PUNJAB',
        '04' => 'CHANDIGARH',
        '05' => 'UTTARAKHAND',
        '06' => 'HARYANA',
        '07' => 'DELHI',
        '08' => 'RAJASTHAN',
        '09' => 'UTTAR PRADESH',
        '10' => 'BIHAR',
        '11' => 'SIKKIM',
        '12' => 'ARUNACHAL PRADESH',
        '13' => 'NAGALAND',
        '14' => 'MANIPUR',
        '15' => 'MIZORAM',
        '16' => 'TRIPURA',
        '17' => 'MEGHALAYA',
        '18' => 'ASSAM',
        '19' => 'WEST BENGAL',
        '20' => 'JHARKHAND',
        '21' => 'ODISHA',
        '22' => 'CHATTISGARH',
        '23' => 'MADHYA PRADESH',
        '24' => 'GUJARAT',
        '25' => 'CHATTISGARH',
        '26' => 'DADRA AND NAGAR HAVELI AND DAMAN AND DIU (NEWLY MERGED UT)',
        '27' => 'MAHARASHTRA',
        '28' => 'ANDHRA PRADESH(BEFORE DIVISION)',
        '29' => 'KARNATAKA',
        '30' => 'GOA',
        '31' => 'LAKSHADWEEP',
        '32' => 'KERALA',
        '33' => 'TAMIL NADU',
        '34' => 'PUDUCHERRY',
        '35' => 'ANDAMAN AND NICOBAR ISLANDS',
        '36' => 'TELANGANA',
        '37' => 'ANDHRA PRADESH (NEWLY ADDED)',
        '97' => 'OTHER TERRITORY',
        '99' => 'CENTRE JURISDICTION',
    ];

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($request) {
                return Carbon::parse($request->created_at)->format('Y-m-d H:i:s');
            })
            ->addColumn('action', function (SalePurchaseInvoice $salespurchase) {
                return view('app.excelImport._salespurchase-action', compact('salespurchase'));
            })
            ->editColumn('inv_date', function (SalePurchaseInvoice $salespurchase) {
                $previousInvDate = $salespurchase->inv_date ?? 'NULL'; // Get the previous applicable date
                $inputField = '<span class="editable-input">' . $previousInvDate . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="inv_date" value="' . $previousInvDate . '" data-id="' . $salespurchase->id . '" type="date">';
                return $inputField;
            })
            ->editColumn('inv_no', function (SalePurchaseInvoice $salespurchase) {
                $previousInvNo = $salespurchase->inv_no ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousInvNo . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="inv_no" value="' . $previousInvNo . '" data-id="' . $salespurchase->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('bill_ref_no', function (SalePurchaseInvoice $salespurchase) {
                $previousBillRefNo = $salespurchase->bill_ref_no ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousBillRefNo . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="bill_ref_no" value="' . $previousBillRefNo . '" data-id="' . $salespurchase->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('voucher_type', function (SalePurchaseInvoice $salespurchase) {
                $previousVoucherType = $salespurchase->voucher_type ?? 'NULL'; 
                return '<select class="edit-vouchertype-select form-control" data-id="' . $salespurchase->id . '">' .
                    '<option value="Sales" ' . ($previousVoucherType === "Sales" ? "selected" : "") . '>Sales</option>' .
                    '<option value="Purchase" ' . ($previousVoucherType === "Purchase" ? "selected" : "") . '>Purchase</option>' .
                    '</select>';
            })
            ->editColumn('party_name', function (SalePurchaseInvoice $salespurchase) {
                $previousPartyName = $salespurchase->party_name ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousPartyName . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="party_name" value="' . $previousPartyName . '" data-id="' . $salespurchase->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('address1', function (SalePurchaseInvoice $salespurchase) {
                $previousAddress1 = $salespurchase->address1 ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousAddress1 . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="address1" value="' . $previousAddress1 . '" data-id="' . $salespurchase->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('address2', function (SalePurchaseInvoice $salespurchase) {
                $previousAddress2 = $salespurchase->address2 ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousAddress2 . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="address2" value="' . $previousAddress2 . '" data-id="' . $salespurchase->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('state', function (SalePurchaseInvoice $salespurchase) {
                $previousState = $salespurchase->state ?? 'NULL'; // Get the previous state or an empty string if null
                $selectOptions = '';
                foreach ($this->states as $code => $name) {
                    $selected = ($code == $previousState) ? 'selected' : ''; // Check if the code matches the previous state
                    $selectOptions .= '<option value="' . $code . '" ' . $selected . '>' . $name . '</option>';
                }
                $selectField = '<select class="edit--state-select form-control" name="state" data-id="' . $salespurchase->id . '">' . $selectOptions . '</select>';
                return $selectField;
            })
            ->editColumn('country', function (SalePurchaseInvoice $salespurchase) {
                $previousCountry = $salespurchase->country ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousCountry . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="country" value="' . $previousCountry . '" data-id="' . $salespurchase->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('gst_in', function (SalePurchaseInvoice $salespurchase) {
                $previousGstIn = $salespurchase->gst_in ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousGstIn . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="gst_in" value="' . $previousGstIn . '" data-id="' . $salespurchase->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('gst_reg_type', function (SalePurchaseInvoice $salespurchase) {
                $previousGstRegType = $salespurchase->gst_reg_type ?? 'NULL'; // Get the previous GST registration type
                return '<select class="edit-select form-control" data-id="' . $salespurchase->id . '">' .
                    '<option value="Regular" ' . ($previousGstRegType === "Regular" ? "selected" : "") . '>Regular</option>' .
                    '<option value="Unregistered/Consumer" ' . ($previousGstRegType === "Unregistered/Consumer" ? "selected" : "") . '>Unregistered/Consumer</option>' .
                    '</select>';
            })
            ->editColumn('place_of_supply', function (SalePurchaseInvoice $salespurchase) {
                $previousPlaceOfSupply = $salespurchase->place_of_supply ?? 'NULL'; // Get the previous state or an empty string if null
                $selectOptions = '';
                foreach ($this->states as $code => $name) {
                    $selected = ($code == $previousPlaceOfSupply) ? 'selected' : ''; // Check if the code matches the previous state
                    $selectOptions .= '<option value="' . $code . '" ' . $selected . '>' . $name . '</option>';
                }
                $selectField = '<select class="edit--placeofsupply-select form-control" name="state" data-id="' . $salespurchase->id . '">' . $selectOptions . '</select>';
                return $selectField;
            })
            ->editColumn('company_reg_type', function (SalePurchaseInvoice $salespurchase) {
                $previousCompanyRegType = $salespurchase->company_reg_type ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousCompanyRegType . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="company_reg_type" value="' . $previousCompanyRegType . '" data-id="' . $salespurchase->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('item_name', function (SalePurchaseInvoice $salespurchase) {
                $previousItemName = $salespurchase->item_name ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousItemName . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="item_name" value="' . $previousItemName . '" data-id="' . $salespurchase->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('item_desc', function (SalePurchaseInvoice $salespurchase) {
                $previousItemDesc = $salespurchase->item_desc ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousItemDesc . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="item_desc" value="' . $previousItemDesc . '" data-id="' . $salespurchase->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('qty', function (SalePurchaseInvoice $salespurchase) {
                $previousQty = $salespurchase->qty ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousQty . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="qty" value="' . $previousQty . '" data-id="' . $salespurchase->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('uom', function (SalePurchaseInvoice $salespurchase) {
                $previousUom = $salespurchase->uom ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousUom . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="uom" value="' . $previousUom . '" data-id="' . $salespurchase->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('item_rate', function (SalePurchaseInvoice $salespurchase) {
                $previousItemRate = $salespurchase->item_rate ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousItemRate . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="item_rate" value="' . $previousItemRate . '" data-id="' . $salespurchase->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('gst_rate', function (SalePurchaseInvoice $salespurchase) {
                $previousGstName = $salespurchase->gst_rate ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousGstName . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="gst_rate" value="' . $previousGstName . '" data-id="' . $salespurchase->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('taxable', function (SalePurchaseInvoice $salespurchase) {
                $previousTaxable = $salespurchase->taxable ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousTaxable . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="taxable" value="' . $previousTaxable . '" data-id="' . $salespurchase->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('sgst', function (SalePurchaseInvoice $salespurchase) {
                $previousSgst = $salespurchase->sgst ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousSgst . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="sgst" value="' . $previousSgst . '" data-id="' . $salespurchase->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('cgst', function (SalePurchaseInvoice $salespurchase) {
                $previousCgst = $salespurchase->cgst ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousCgst . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="cgst" value="' . $previousCgst . '" data-id="' . $salespurchase->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('igst', function (SalePurchaseInvoice $salespurchase) {
                $previousIgst = $salespurchase->igst ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousIgst . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="igst" value="' . $previousIgst . '" data-id="' . $salespurchase->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('cess', function (SalePurchaseInvoice $salespurchase) {
                $previousCess = $salespurchase->cess ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousCess . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="cess" value="' . $previousCess . '" data-id="' . $salespurchase->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('discount', function (SalePurchaseInvoice $salespurchase) {
                $previousDiscount = $salespurchase->discount ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousDiscount . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="discount" value="' . $previousDiscount . '" data-id="' . $salespurchase->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('inv_amt', function (SalePurchaseInvoice $salespurchase) {
                $previousInvAmt = $salespurchase->inv_amt ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousInvAmt . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="inv_amt" value="' . $previousInvAmt . '" data-id="' . $salespurchase->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('narration', function (SalePurchaseInvoice $salespurchase) {
                $previousNarration = $salespurchase->narration ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousNarration . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="narration" value="' . $previousNarration . '" data-id="' . $salespurchase->id . '" type="text">';
                return $inputField;
            })

            ->rawColumns(['action','inv_date','inv_no','bill_ref_no','voucher_type','party_name','address1','address2','state','country','gst_in','gst_reg_type','place_of_supply','company_reg_type','item_name','item_desc','qty','uom','item_rate','gst_rate','taxable','sgst','cgst','igst','cess','discount','inv_amt','narration']);
    }

    public function query(SalePurchaseInvoice $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('salePurchase-table')
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
