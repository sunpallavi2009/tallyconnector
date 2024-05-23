@extends('layouts.tenant')
@section('title', __('Excel Import | Preciseca'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('excelImport.index') }}">{{ __('Excel Import') }}</a></li>
    <li class="breadcrumb-item">{{ __('Receipt Voucher Import') }}</li>
@endsection
@section('content')
    <!-- Add this HTML code to your blade template -->
    @if(Session::has('error'))
    <div class="modal fade modal-animate anim-blur show" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-modal="true" style="display: block;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="errorModalLabel">Error</h5>
                    {{-- <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <div class="body">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    {{ Session::get('error') }}
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="text-end">
                                <button type="button" class="btn btn-secondary" onclick="$('#errorModal').modal('hide');" aria-label="Close">Close</button>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card sticky-top">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            @include('app.excelImport.sidebar._receipt-sidebar')
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
                                    'route' => ['excelImport.receipt.import'],
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
                                                <img src="{{ url('assets/images/25.png') }}" width="40" class="img">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                {{ Form::label('file', __('Upload here'), ['class' => 'form-label asterisk']) }}
                                                {!! Form::file('file', ['class' => 'form-control', 'id' => 'file']) !!}
                                            </div>

                                            <div class="col-12 px-3">
                                                <p class="mt-2" style="font-size: 18px;">
                                                    <a href="{{ url('assets/exceldocument/Bank-Statement.xlsx') }}">Click here</a>
                                                    <b class="mb-0">to download import template file.</b>
                                                </p>
                                            </div>

                                            <div class="form-row mb-2 mt-4 ml-8">
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
@push('javascript')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    
<script>
    $(document).ready(function() {
        $('#errorModal').modal('show');
    });
</script>
@endpush