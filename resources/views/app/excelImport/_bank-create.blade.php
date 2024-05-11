@extends('layouts.main')
@section('title', __('Excel Import'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('excelImport.index') }}">{{ __('Excel Import') }}</a></li>
    <li class="breadcrumb-item">{{ __('Bank Statement Import') }}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card sticky-top">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            @include('admin.excelImport.sidebar._bank-sidebar')
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="card"></div>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="card">
                            <div class="tab-pane fade show active" id="apps-setting" role="tabpanel"
                                 aria-labelledby="landing-apps-setting">
                                {!! Form::open([
                                    'route' => ['excelImport.bank.import'],
                                    'method' => 'Post',
                                    'enctype' => 'multipart/form-data',
                                ]) !!}
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-8 d-flex align-items-center">
                                            <h5 class="mb-0">{{ __('Import Using Excel') }}</h5>
                                        </div>
                                        <div class="col-lg-4 d-flex justify-content-end">
                                            <div class="form-switch custom-switch-v1 d-inline-block">
                                                <img src="{{ asset('assets/images/25.png') }}" width="40" class="img">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                {{ Form::label('file', __('Upload here'), ['class' => 'form-label']) }} *
                                                {!! Form::file('file', ['class' => 'form-control', 'id' => 'file']) !!}
                                            </div>

                                            <div class="col-12 px-3">
                                                <p class="mt-2" style="font-size: 18px;">
                                                    <a href="{{ asset('assets/exceldocument/Bank-Statement.xlsx') }}">Click here</a>
                                                    <b class="mb-0">to download import template file.</b>
                                                </p>
                                            </div>

                                            <div class="form-row mb-2">
                                                <div class="col-12">
                                                    <ul>
                                                        <li>Step 1 - Download Excel template.</li>
                                                        <li>Step 2 - Put data in template Sheet &amp; column wise.</li>
                                                        <li>Step 3 - Select template file and click on upload.</li>
                                                    </ul>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="text-end">
                                        {{ Form::button(__('Save'), ['type' => 'submit', 'id' => 'save-btn', 'class' => 'btn btn-primary']) }}
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
