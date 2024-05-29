<?php

namespace App\DataTables;

use Carbon\Carbon;
use App\Models\Tenant;
use App\Facades\UtilityFacades;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Services\DataTable;

class TenantDataTable extends DataTable
{

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query->with('domains'))
            ->addIndexColumn()
            ->editColumn('created_at', function ($request) {
                return Carbon::parse($request->created_at)->format('Y-m-d H:i:s');
            })
            
            ->addColumn('impersonate', function (Tenant $tenant) {
                return view('tenants._impersonate', compact('tenant'));
            })
            ->addColumn('action', function (Tenant $tenant) {
                return view('tenants._action', compact('tenant'));
            })

            ->rawColumns(['impersonate','action']);
    }

    public function query(Tenant $model)
    {
        return $model->newQuery()
            ->leftJoin('domains', 'tenants.id', '=', 'domains.tenant_id')
            ->select('tenants.*', 'domains.domain as domain');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('tenant-table')
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
                        window.location = '" . route('tenants.create') . "';
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
            Column::make('name')->title(__('Name')),
            Column::make('email')->title(__('Email')),
            // Column::make('status')->title(__('Status')),
            Column::make('domain')->title(__('DOMAIN NAME'))->searchable(false), 
            Column::computed('action')->title(__('Action'))
            ->exportable(false)
            ->printable(false)
            ->width(60)
            ->addClass('text-center')
            ->width('20%'),
            Column::computed('impersonate')->title(__('DOMAIN LINK'))
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
