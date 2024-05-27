<?php

namespace App\DataTables\App;

use Carbon\Carbon;
use App\Models\Ledger;
use App\Facades\UtilityFacades;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LedgerDataTable extends DataTable
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
            ->addColumn('action', function (Ledger $ledgers) {
                return view('app.excelImport._ledger-action', compact('ledgers'));
            })
            ->editColumn('created_at', function ($request) {
                return Carbon::parse($request->created_at)->format('Y-m-d H:i:s');
            })

            ->editColumn('party_name', function (Ledger $ledger) {
                $previousPartyName = $ledger->party_name ?? 'NULL'; // Get the previous party name or an empty string if null
                $inputField = '<span class="editable-input">' . $previousPartyName . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="party_name" value="' . $previousPartyName . '" data-id="' . $ledger->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('alias', function (Ledger $ledger) {
                $previousAlias = $ledger->alias ?? 'NULL'; // Get the previous alias
                $inputField = '<span class="editable-input">' . $previousAlias . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="alias" value="' . $previousAlias . '" data-id="' . $ledger->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('group_name', function (Ledger $ledger) {
                $previousGroupName = $ledger->group_name ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousGroupName . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="group_name" value="' . $previousGroupName . '" data-id="' . $ledger->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('credit_period', function (Ledger $ledger) {
                $previousCreditPeriod = $ledger->credit_period ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousCreditPeriod . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="credit_period" value="' . $previousCreditPeriod . '" data-id="' . $ledger->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('buyer_name', function (Ledger $ledger) {
                $previousBuyerName = $ledger->buyer_name ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousBuyerName . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="buyer_name" value="' . $previousBuyerName . '" data-id="' . $ledger->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('address1', function (Ledger $ledger) {
                $previousAddress1 = $ledger->address1 ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousAddress1 . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="address1" value="' . $previousAddress1 . '" data-id="' . $ledger->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('address2', function (Ledger $ledger) {
                $previousAddress2 = $ledger->address2 ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousAddress2 . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="address2" value="' . $previousAddress2 . '" data-id="' . $ledger->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('address3', function (Ledger $ledger) {
                $previousAddress3 = $ledger->address3 ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousAddress3 . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="address3" value="' . $previousAddress3 . '" data-id="' . $ledger->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('country', function (Ledger $ledger) {
                $previousCountry = $ledger->country ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousCountry . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="country" value="' . $previousCountry . '" data-id="' . $ledger->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('state', function (Ledger $ledger) {
                $previousState = $ledger->state ?? 'NULL'; // Get the previous state or an empty string if null
                $selectOptions = '';
                foreach ($this->states as $code => $name) {
                    $selected = ($code == $previousState) ? 'selected' : ''; // Check if the code matches the previous state
                    $selectOptions .= '<option value="' . $code . '" ' . $selected . '>' . $name . '</option>';
                }
                $selectField = '<select class="edit--state-select form-control" name="state" data-id="' . $ledger->id . '">' . $selectOptions . '</select>';
                return $selectField;
            })
            
            ->editColumn('pincode', function (Ledger $ledger) {
                $previousPincode = $ledger->pincode ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousPincode . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="pincode" value="' . $previousPincode . '" data-id="' . $ledger->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('gst_in', function (Ledger $ledger) {
                $previousGstIn = $ledger->gst_in ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousGstIn . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="gst_in" value="' . $previousGstIn . '" data-id="' . $ledger->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('gst_reg_type', function (Ledger $ledger) {
                $previousGstRegType = $ledger->gst_reg_type ?? 'NULL'; // Get the previous GST registration type
                return '<select class="edit-select form-control" data-id="' . $ledger->id . '">' .
                    '<option value="Regular" ' . ($previousGstRegType === "Regular" ? "selected" : "") . '>Regular</option>' .
                    '<option value="Unregistered/Consumer" ' . ($previousGstRegType === "Unregistered/Consumer" ? "selected" : "") . '>Unregistered/Consumer</option>' .
                    '</select>';
            })
            ->editColumn('opening_balance', function (Ledger $ledger) {
                $previousOpeningBalance = $ledger->opening_balance ?? 'NULL'; // Get the previous party name
                $inputField = '<span class="editable-input">' . $previousOpeningBalance . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="opening_balance" value="' . $previousOpeningBalance . '" data-id="' . $ledger->id . '" type="text">';
                return $inputField;
            })
            ->editColumn('applicable_date', function (Ledger $ledger) {
                $previousApplicableDate = $ledger->applicable_date ?? 'NULL'; // Get the previous applicable date
                $inputField = '<span class="editable-input">' . $previousApplicableDate . '</span>' .
                    '<input class="edit-input bg-input-color d-none btn btn-outline-secondary" name="applicable_date" value="' . $previousApplicableDate . '" data-id="' . $ledger->id . '" type="date">';
                return $inputField;
            })
            

        ->rawColumns(['action','party_name','alias','group_name','credit_period','buyer_name','address1','address2','address3','country','state','pincode','gst_in','gst_reg_type','opening_balance','applicable_date']);


    }

    public function query(Ledger $model)
    {
        // return $model->newQuery()->latest();

        $query = $model->newQuery()->latest();

        if(request()->has('start_date') && request()->has('end_date')) {
            $query->whereBetween('applicable_date', [request('start_date'), request('end_date')]);
        }
        return $query;
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('ledger-table')
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
            Column::make('party_name')->title(__('Party Name')),
            Column::make('alias')->title(__('Alias')),
            Column::make('group_name')->title(__('Group Name')),
            Column::make('credit_period')->title(__('Credit Period')),
            Column::make('buyer_name')->title(__('Buyer/Mailing Name')),
            Column::make('address1')->title(__('Address 1')),
            Column::make('address2')->title(__('Address 2')),
            Column::make('address3')->title(__('Address 3')),
            Column::make('country')->title(__('Country')),
            Column::make('state')->title(__('State')),
            Column::make('pincode')->title(__('Pincode')),
            Column::make('gst_in')->title(__('GSTIN/UIN')),
            Column::make('gst_reg_type')->title(__('GST Registration Type')),
            Column::make('opening_balance')->title(__('Opening Balance DR/CR')),
            Column::make('applicable_date')->title(__('Applicable Date')),
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
