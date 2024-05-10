<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExcelImportController extends Controller
{
    public function index()
    {
        return view('app.excelImport.index');
    }

    public function ledgerCreate()
    {
        return view('app.excelImport._ledger-create');
    }
}
