<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\App\ItemDataTable;
use App\DataTables\App\LedgerDataTable;

class JsonImportController extends Controller
{ 
    public function index(LedgerDataTable $dataTable)
    {
        return $dataTable->render('app.jsonImport.index');
    }

    public function itemShow(ItemDataTable $dataTable)
    {
        return $dataTable->render('app.jsonImport._item');
    }
}
