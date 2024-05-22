<?php

namespace App\Http\Controllers\App;

use App\Models\Company;
use Illuminate\Http\Request;
use App\DataTables\App\B2BDataTable;
use App\DataTables\App\EXPDataTable;
use App\DataTables\App\NILDataTable;
use App\Http\Controllers\Controller;
use App\DataTables\App\B2CLDataTable;
use App\DataTables\App\B2CSDataTable;
use App\DataTables\App\CDNRDataTable;
use App\DataTables\App\CDNURDataTable;
use App\DataTables\App\SalesInvoiceDataTable;

class Gstr1Controller extends Controller
{
    public function index(Request $request, SalesInvoiceDataTable $dataTable)
    {
        $companies = Company::get();
        return $dataTable->render('app.gstr1.index', compact('companies'));
    }
    public function b2bData(Request $request, B2BDataTable $dataTable)
    {
        $tags = $request->tags;
        return $dataTable->with('tags', $tags)->render('app.gstr1._b2b');
    }
    public function b2clData(B2CLDataTable $dataTable)
    {
        return $dataTable->render('app.gstr1._b2cl');
    }
    public function b2csData(B2CSDataTable $dataTable)
    {
        return $dataTable->render('app.gstr1._b2cs');
    }
    public function cdnrData(CDNRDataTable $dataTable)
    {
        return $dataTable->render('app.gstr1._cdnr');
    }
    public function cdnurData(CDNURDataTable $dataTable)
    {
        return $dataTable->render('app.gstr1._cdnur');
    }
    public function expData(EXPDataTable $dataTable)
    {
        return $dataTable->render('app.gstr1._exp');
    }
    public function nilData(NILDataTable $dataTable)
    {
        return $dataTable->render('app.gstr1._nil');
    }
}
