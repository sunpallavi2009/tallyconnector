@extends('layouts.tenant')
@section('title', __('Excel Import | Preciseca'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('excelImport.index') }}">{{ __('Excel Import') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('excelImport.bank.create') }}">{{ __('Bank Statement Import') }}</a></li>
    <li class="breadcrumb-item">{{ __('View Bank Statement') }}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">

                <div class="col-xl-12">
                    <div class="card sticky-top">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            @include('app.excelImport.sidebar._bank-sidebar')
                        </div>
                    </div>
                </div>

                <div class="col-xl-12">
                    <div class="card">
                        <div class="tab-pane fade show active" id="apps-setting" role="tabpanel"
                             aria-labelledby="landing-apps-setting">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 d-flex align-items-center">
                                        <h5 class="mb-0">{{ __('Bank Statement Data') }}</h5>
                                    </div>
                                    <div class="col-lg-4 d-flex justify-content-end">
                                        <div class="form-switch custom-switch-v1 d-inline-block">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                {{ $dataTable->table(['width' => '100%']) }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('css')
    @include('layouts.includes.datatable-css')
@endpush
@push('javascript')
    @include('layouts.includes.datatable-js')
    {{ $dataTable->scripts() }}
@endpush
