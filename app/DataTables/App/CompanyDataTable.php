<?php

namespace App\DataTables\App;

use Carbon\Carbon;
use App\Models\Company;
use App\Facades\UtilityFacades;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CompanyDataTable extends DataTable
{
    protected $states = [
        'JAMMU AND KASHMIR' => '01',
        'HIMACHAL PRADESH' => '02',
        'PUNJAB' => '03',
        'CHANDIGARH' => '04',
        'UTTARAKHAND' => '05',
        'HARYANA' => '06',
        'DELHI' => '07',
        'RAJASTHAN' => '08',
        'UTTAR PRADESH' => '09',
        'BIHAR' => '10',
        'SIKKIM' => '11',
        'ARUNACHAL PRADESH' => '12',
        'NAGALAND' => '13',
        'MANIPUR' => '14',
        'MIZORAM' => '15',
        'TRIPURA' => '16',
        'MEGHALAYA' => '17',
        'ASSAM' => '18',
        'WEST BENGAL' => '19',
        'JHARKHAND' => '20',
        'ODISHA' => '21',
        'CHATTISGARH' => '22',
        'MADHYA PRADESH' => '23',
        'GUJARAT' => '24',
        'DADRA AND NAGAR HAVELI AND DAMAN AND DIU (NEWLY MERGED UT)' => '26',
        'MAHARASHTRA' => '27',
        'ANDHRA PRADESH(BEFORE DIVISION)' => '28',
        'KARNATAKA' => '29',
        'GOA' => '30',
        'LAKSHADWEEP' => '31',
        'KERALA' => '32',
        'TAMIL NADU' => '33',
        'PUDUCHERRY' => '34',
        'ANDAMAN AND NICOBAR ISLANDS' => '35',
        'TELANGANA' => '36',
        'ANDHRA PRADESH (NEWLY ADDED)' => '37',
        'OTHER TERRITORY' => '97',
        'CENTRE JURISDICTION' => '99',
    ];

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($request) {
                return Carbon::parse($request->created_at)->format('Y-m-d H:i:s');
            })
            ->addColumn('action', function (Company $companies) {
                return view('app.company._action', compact('companies'));
            })
            ->addColumn('state', function (Company $companies) {
                return array_search($companies->state, $this->states);
            })

            ->rawColumns(['action','state']);
    }

    public function query(Company $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('company-table')
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
                    ['extend' => 'create', 'className' => 'btn btn-light-primary no-corner me-1 add_module', 'action' => " function ( e, dt, node, config ) {
                        window.location = '" . route('companies.create') . "';
                   }"],
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
            Column::make('No')->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
//            Column::make('name')->title(__('User Name')),
            Column::make('company_name')->title(__('Company Name')),
            Column::make('gst_no')->title(__('GST Number')),
            Column::make('state')->title(__('State')),
            Column::make('gst_user_name')->title(__('GST User Name')),
            Column::make('tally_company_guid')->title(__('Tally Id')),
            Column::make('token_id')->title(__('Token Id')),
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
        return 'Faq_' . date('YmdHis');
    }
}
