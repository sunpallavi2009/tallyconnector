@extends('layouts.main')
@section('title', __('Dashboard'))
@section('content')
    <div class="row">
        <div class="col-xxl-7">
            <div class="row">
               
                    <div class="col-lg-3 col-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="theme-avtar bg-primary">
                                    <i class="ti ti-users"></i>
                                </div>
                                <p class="text-muted text-sm mt-4 mb-2"> {{ __('Total') }} </p>
                                <h6 class="mb-3 text-primary"> {{ __('Tenant') }} </h6>
                                <h3 class="mb-0 text-primary"> {{ $tenant }} </h3>
                            </div>
                        </div>
                    </div>
              
                    <div class="col-lg-3 col-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="theme-avtar bg-info">
                                    <i class="ti ti-businessplan"></i>
                                </div>
                                <p class="text-muted text-sm mt-4 mb-2"> {{ __('Total') }} </p>
                                <h6 class="mb-3 text-info"> {{ __('Plan') }} </h6>
                                <h3 class="mb-0 text-info"> {{ 2 }} </h3>
                            </div>
                        </div>
                    </div>

            </div>
        </div>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/daterangepicker/daterangepicker.css') }}">
@endpush
@push('javascript')
    <script src="{{ asset('vendor/modules/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    <script src="{{ asset('vendor/daterangepicker/daterangepicker.min.js') }}"></script>
@endpush
