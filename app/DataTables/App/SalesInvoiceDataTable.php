<?php

namespace App\DataTables\App;

use App\Facades\UtilityFacades;
use App\Models\SalePurchaseInvoice;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;

class SalesInvoiceDataTable extends DataTable
{
    protected $indexNames = [
        0 => 'B2B',
        1 => 'B2CS',
        2 => 'B2CL',
        3 => 'CDNR',
        4 => 'CDNUR',
        5 => 'EXP',
        6 => 'NIL'
    ];


    public function dataTable($query)
    {

        $counts = [
            'B2B' => SalePurchaseInvoice::query()
                ->whereNotNull('gst_in')
               ->when(request()->has('month') && !empty(request()->month), function ($filteredRows) {
                   $filteredRows->where('inv_date', 'LIKE', '%' . request()->month);
                })
                ->when(request()->has('year') && !empty(request()->year), function ($filteredRows) {
                    $filteredRows->where('inv_date', 'LIKE', request()->year . '-%');
                })
                ->count(),
            'B2CS' => SalePurchaseInvoice::whereNull('gst_in')->where('inv_amt', '<', 250000)->count(),
            'B2CL' => SalePurchaseInvoice::whereNull('gst_in')->where('inv_amt', '>=', 250000)->count(),
            'CDNR' => SalePurchaseInvoice::whereNotNull('gst_in')->count(),
            'CDNUR' => SalePurchaseInvoice::where('gst_reg_type', 'Unregistered/Consumer')->count(),
            'EXP' => SalePurchaseInvoice::whereNull('gst_in')->where('country', '!=', 'India')->count(),
            'NIL' => SalePurchaseInvoice::where('gst_rate', 0)->count(),
        ];

        //dd($counts);

//        $counts = [
//            'B2B' => SalePurchaseInvoice::whereNotNull('gst_in')->count(),
//            'B2CS' => SalePurchaseInvoice::whereNull('gst_in')->where('inv_amt', '<', 250000)->count(),
//            'B2CL' => SalePurchaseInvoice::whereNull('gst_in')->where('inv_amt', '>=', 250000)->count(),
//            'CDNR' => SalePurchaseInvoice::whereNotNull('gst_in')->count(),
//            'CDNUR' => SalePurchaseInvoice::where('gst_reg_type', 'Unregistered/Consumer')->count(),
//            'EXP' => SalePurchaseInvoice::whereNull('gst_in')->where('country', '!=', 'India')->count(),
//            'NIL' => SalePurchaseInvoice::where('gst_rate', 0)->count(),
//        ];

        $inv_amts = [
            'B2B' => SalePurchaseInvoice::whereNotNull('gst_in')->sum('inv_amt'),
            'B2CS' => SalePurchaseInvoice::whereNull('gst_in')->where('inv_amt', '<', 250000)->sum('inv_amt'),
            'B2CL' => SalePurchaseInvoice::whereNull('gst_in')->where('inv_amt', '>=', 250000)->sum('inv_amt'),
            'CDNR' => SalePurchaseInvoice::whereNotNull('gst_in')->sum('inv_amt'),
            'CDNUR' => SalePurchaseInvoice::where('gst_reg_type', 'Unregistered/Consumer')->sum('inv_amt'),
            'EXP' => SalePurchaseInvoice::whereNull('gst_in')->where('country', '!=', 'India')->sum('inv_amt'),
            'NIL' => SalePurchaseInvoice::where('gst_rate', 0)->sum('inv_amt'),
        ];

        $igst = [
            'B2B' => SalePurchaseInvoice::whereNotNull('gst_in')->sum('igst'),
            'B2CS' => SalePurchaseInvoice::whereNull('gst_in')->where('inv_amt', '<', 250000)->sum('igst'),
            'B2CL' => SalePurchaseInvoice::whereNull('gst_in')->where('inv_amt', '>=', 250000)->sum('igst'),
            'CDNR' => SalePurchaseInvoice::whereNotNull('gst_in')->sum('igst'),
            'CDNUR' => SalePurchaseInvoice::where('gst_reg_type', 'Unregistered/Consumer')->sum('igst'),
            'EXP' => SalePurchaseInvoice::whereNull('gst_in')->where('country', '!=', 'India')->sum('igst'),
            'NIL' => SalePurchaseInvoice::where('gst_rate', 0)->sum('igst'),
        ];

        $cgst = [
            'B2B' => SalePurchaseInvoice::whereNotNull('gst_in')->sum('cgst'),
            'B2CS' => SalePurchaseInvoice::whereNull('gst_in')->where('inv_amt', '<', 250000)->sum('cgst'),
            'B2CL' => SalePurchaseInvoice::whereNull('gst_in')->where('inv_amt', '>=', 250000)->sum('cgst'),
            'CDNR' => SalePurchaseInvoice::whereNotNull('gst_in')->sum('cgst'),
            'CDNUR' => SalePurchaseInvoice::where('gst_reg_type', 'Unregistered/Consumer')->sum('cgst'),
            'EXP' => SalePurchaseInvoice::whereNull('gst_in')->where('country', '!=', 'India')->sum('cgst'),
            'NIL' => SalePurchaseInvoice::where('gst_rate', 0)->sum('cgst'),
        ];

        $sgst = [
            'B2B' => SalePurchaseInvoice::whereNotNull('gst_in')->sum('sgst'),
            'B2CS' => SalePurchaseInvoice::whereNull('gst_in')->where('inv_amt', '<', 250000)->sum('sgst'),
            'B2CL' => SalePurchaseInvoice::whereNull('gst_in')->where('inv_amt', '>=', 250000)->sum('sgst'),
            'CDNR' => SalePurchaseInvoice::whereNotNull('gst_in')->sum('sgst'),
            'CDNUR' => SalePurchaseInvoice::where('gst_reg_type', 'Unregistered/Consumer')->sum('sgst'),
            'EXP' => SalePurchaseInvoice::whereNull('gst_in')->where('country', '!=', 'India')->sum('sgst'),
            'NIL' => SalePurchaseInvoice::where('gst_rate', 0)->sum('sgst'),
        ];

        $cess = [
            'B2B' => SalePurchaseInvoice::whereNotNull('gst_in')->sum('cess'),
            'B2CS' => SalePurchaseInvoice::whereNull('gst_in')->where('inv_amt', '<', 250000)->sum('cess'),
            'B2CL' => SalePurchaseInvoice::whereNull('gst_in')->where('inv_amt', '>=', 250000)->sum('cess'),
            'CDNR' => SalePurchaseInvoice::whereNotNull('gst_in')->sum('cess'),
            'CDNUR' => SalePurchaseInvoice::where('gst_reg_type', 'Unregistered/Consumer')->sum('cess'),
            'EXP' => SalePurchaseInvoice::whereNull('gst_in')->where('country', '!=', 'India')->sum('cess'),
            'NIL' => SalePurchaseInvoice::where('gst_rate', 0)->sum('cess'),
        ];

        $txt_amt = [
            'B2B' => SalePurchaseInvoice::whereNotNull('gst_in')->sum('taxable'),
            'B2CS' => SalePurchaseInvoice::whereNull('gst_in')->where('inv_amt', '<', 250000)->sum('taxable'),
            'B2CL' => SalePurchaseInvoice::whereNull('gst_in')->where('inv_amt', '>=', 250000)->sum('taxable'),
            'CDNR' => SalePurchaseInvoice::whereNotNull('gst_in')->sum('taxable'),
            'CDNUR' => SalePurchaseInvoice::where('gst_reg_type', 'Unregistered/Consumer')->sum('taxable'),
            'EXP' => SalePurchaseInvoice::whereNull('gst_in')->where('country', '!=', 'India')->sum('taxable'),
            'NIL' => SalePurchaseInvoice::where('gst_rate', 0)->sum('taxable'),
        ];


        // $totalCount = array_sum($counts);




        $rows = $query->get()->toArray();


        $filteredRows = [];
        foreach ($rows as $row) {
            $index = array_search($row, $rows);
            if (isset($this->indexNames[$index])) {
                $filteredRows[] = $row;
            }
        }

        return datatables()
//            ->eloquent($row)
//            ->addIndexColumn()
            ->collection($filteredRows)
//            ->collection($query->get())

//            ->filter(function ($row) {
////                $tags = request()->tags;
////                if (!empty($tags)) {
////                    $tagsArray = explode(',', $tags);
////                    foreach ($tagsArray as $tag) {
////                        $query->orWhere('tags', 'LIKE', "%$tag%");
////                    }
////                }
//                if (request()->has('month') && !empty(request()->month)) {
//                    $row->where('inv_date', 'LIKE', '%' . request()->month);
//                }
//                if (request()->has('year') && !empty(request()->year)) {
//                    $row->where('inv_date', 'LIKE', request()->year . '-%');
//                }
//            })

            ->addColumn('DT_RowIndex', function ($row) use ($rows) {

                $index = array_search($row, $rows);
                return isset($this->indexNames[$index]) ? $this->indexNames[$index] : '';
            })


            ->addColumn('count', function ($row) use ($counts, $rows) {
                $index = array_search($row, $rows);
                return $counts[$this->indexNames[$index]] ?? 0;
            })
            ->addColumn('inv_amt', function ($row) use ($inv_amts, $rows) {
                $index = array_search($row, $rows);
                return $inv_amts[$this->indexNames[$index]] ?? 0;
            })
            ->addColumn('igst', function ($row) use ($igst, $rows) {
                $index = array_search($row, $rows);
                return $igst[$this->indexNames[$index]] ?? 0;
            })
            ->addColumn('cgst', function ($row) use ($cgst, $rows) {
                $index = array_search($row, $rows);
                return $cgst[$this->indexNames[$index]] ?? 0;
            })
            ->addColumn('sgst', function ($row) use ($sgst, $rows) {
                $index = array_search($row, $rows);
                return $sgst[$this->indexNames[$index]] ?? 0;
            })
            ->addColumn('cess', function ($row) use ($cess, $rows) {
                $index = array_search($row, $rows);
                return $cess[$this->indexNames[$index]] ?? 0;
            })
            ->addColumn('txt_amt', function ($row) use ($txt_amt, $rows) {
                $index = array_search($row, $rows);
                return $txt_amt[$this->indexNames[$index]] ?? 0;
            });

            // ->editColumn('created_at', function ($request) {
            //     return Carbon::parse($request->created_at)->format('Y-m-d H:i:s');
            // });

    }




    public function query(SalePurchaseInvoice $model)
    {
        $query = $model->newQuery();

        return $query;
    }


    public function html()
    {
        return $this->builder()
            ->setTableId('sale-invoice-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->language([
                "paginate" => [
                    "next" => '',
                    "previous" => ''
                ],
                "searchPlaceholder" => __('Search...'), "search" => ""
            ])
            ->parameters([
                "dom" =>  "
                           <'dataTable-top row'<'dataTable-botton table-btn col-lg-12'B>>
                         <'dataTable-container'<'col-sm-12'tr>>
                         <'dataTable-bottom row'<'col-sm-12'p>>
                           ",
                'buttons'   => [
                ],
                "scrollX" => true,
                "paging" => false,
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
            Column::make('Type')->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('count')->title(__('Count')),
            Column::make('inv_amt')->title(__('Inv Amt')),
            Column::make('igst')->title(__('IGST')),
            Column::make('cgst')->title(__('CGST')),
            Column::make('sgst')->title(__('SGST')),
            Column::make('cess')->title(__('CESS')),
            Column::make('txt_amt')->title(__('Txt Amt')),
        ];
    }

    protected function filename(): string
    {
        return 'SalePurchase_' . date('YmdHis');
    }
}
