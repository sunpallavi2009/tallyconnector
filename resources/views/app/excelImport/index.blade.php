@extends('layouts.tenant')
@section('title', __('Excel Import | Preciseca'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Excel Import') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="theme-avtar bg-info">
                        <i class="ti ti-businessplan"></i>
                    </div>
                    <p class="text-muted text-sm mt-4 mb-2"> </p>
                    <a class="text-muted text-sm mt-4 mb-2" href="{{ route('excelImport.ledgers.create') }}"><h6 class="mb-3 text-info"> {{ __('Ledger Master') }} </h6></a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="theme-avtar bg-info">
                        <i class="ti ti-sitemap"></i>
                    </div>
                    <p class="text-muted text-sm mt-4 mb-2"> </p>
                    <a class="text-muted text-sm mt-4 mb-2" href="{{ route('excelImport.items.create') }}"><h6 class="mb-3 text-info"> {{ __('Item Master') }} </h6></a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="theme-avtar bg-info">
                        <i class="ti ti-file-invoice"></i>
                    </div>
                    <p class="text-muted text-sm mt-4 mb-2"> </p>
                    <a class="text-muted text-sm mt-4 mb-2" href="{{ route('excelImport.sales.create') }}"><h6 class="mb-3 text-info"> {{ __('Sales Invoices') }} </h6></a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="theme-avtar bg-info">
                        <i class="ti ti-report-money"></i>
                    </div>
                    <p class="text-muted text-sm mt-4 mb-2"> </p>
                    <a class="text-muted text-sm mt-4 mb-2" href="{{ route('excelImport.purchase.create') }}"><h6 class="mb-3 text-info"> {{ __('Purchase Invoices') }} </h6></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="theme-avtar bg-info">
                        <i class="ti ti-building-bank"></i>
                    </div>
                    <p class="text-muted text-sm mt-4 mb-2"> </p>
                    <a class="text-muted text-sm mt-4 mb-2" href="{{ route('excelImport.bank.create') }}"><h6 class="mb-3 text-info"> {{ __('Bank Statement') }} </h6></a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="theme-avtar bg-info">
                        <i class="ti ti-notebook"></i>
                    </div>
                    <p class="text-muted text-sm mt-4 mb-2"> </p>
                    <a class="text-muted text-sm mt-4 mb-2" href="{{ route('excelImport.receipt.create') }}"><h6 class="mb-3 text-info"> {{ __('Receipt Voucher') }} </h6></a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="theme-avtar bg-info">
                        <i class="ti ti-zoom-money"></i>
                    </div>
                    <p class="text-muted text-sm mt-4 mb-2"> </p>
                    <a class="text-muted text-sm mt-4 mb-2" href="{{ route('excelImport.payment.create') }}"><h6 class="mb-3 text-info"> {{ __('Payment Voucher') }} </h6></a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="theme-avtar bg-info">
                        <i class="ti ti-switch"></i>
                    </div>
                    <p class="text-muted text-sm mt-4 mb-2"> </p>
                    <a class="text-muted text-sm mt-4 mb-2" href="{{ route('excelImport.journal.create') }}"><h6 class="mb-3 text-info"> {{ __('Journal Voucher') }} </h6></a>
                </div>
            </div>
        </div>
    </div>

@endsection
