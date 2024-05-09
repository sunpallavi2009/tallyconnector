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
                                <h6 class="mb-3 text-primary"> {{ __('Admin') }} </h6>
                                <h3 class="mb-0 text-primary"> {{ $user }} </h3>
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

                @can('manage-langauge')
                    <div class="col-lg-3 col-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="theme-avtar bg-warning">
                                    <i class="ti ti-world"></i>
                                </div>
                                <p class="text-muted text-sm mt-4 mb-2"> {{ __('Total') }} </p>
                                <h6 class="mb-3 text-warning"> {{ __('Language') }} </h6>
                                <h3 class="mb-0 text-warning"> {{ $languages }} </h3>
                            </div>
                        </div>
                    </div>
                @endcan
                @if (Auth::user()->type == 'Super Admin' || Auth::user()->type == 'Admin')
                    <div class="col-lg-3 col-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="theme-avtar bg-danger">
                                    <i class="ti ti-thumb-up"></i>
                                </div>
                                <p class="text-muted text-sm mt-4 mb-2"> {{ __('Total') }} </p>
                                <h6 class="mb-3 text-danger"> {{ __('Earning') }} </h6>
                                <h3 class="mb-0 text-danger"> {{ Utility::amount_format($earning) }} </h3>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5> {{ __('Earning Report') }} </h5>
                    <div class="chartRange">
                        <i class="ti ti-calendar"></i>
                        <span></span>
                        <i class="ti ti-chevron-down"></i>
                    </div>
                </div>
                <div class="card-body">
                    <div id="earning-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-xxl-5">
            <div class="card bg-primary">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-sm-8">
                            <h2 class="text-white mb-3">{{ __('Hey Super Admin!') }}</h2>
                            <p class="text-white mb-4">
                                {{ __('Have a nice day! you can quickly add your tenants') }}
                            </p>
                            <div class="dropdown quick-add-btn">
                                <a class="btn-q-add dropdown-toggle dash-btn btn btn-default btn-light"
                                    data-bs-toggle="dropdown" href="javascript:void(0)" role="button" aria-haspopup="false"
                                    aria-expanded="false">
                                    <i class="ti ti-plus drp-icon"></i>
                                    <span class="ms-1">{{ __('Quick add') }}</span>
                                </a>
                                <div class="dropdown-menu">
                                        <a href="{{ route('tenants.index') }}" data-size="lg" data-ajax-popup="true"
                                            data-title="Add User" class="dropdown-item" data-bs-placement="top">
                                            <span> {{ __('View Tenant') }} </span>
                                        </a>
                                        <a href="{{ route('tenants.create') }}" data-size="lg" data-ajax-popup="true"
                                            data-title="Add User" class="dropdown-item" data-bs-placement="top">
                                            <span> {{ __('Add Tenant') }} </span>
                                        </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 d-none d-sm-flex">
                            <img src="{{ asset('vendor/landing-page2/image/img-auth-3.svg') }}" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card dash-supports">
                <div class="card-header">
                    <h5>{{ __('Supports') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            {{-- <tbody>
                                @forelse ($supports as $support)
                                    <tr>
                                        <td>
                                            <a href="{{ route('support-ticket.edit', $support->id) }}"
                                                class="btn btn-outline-primary">
                                                {{ __($support->ticket_id) }} </a>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ __('User Name') }}</small>
                                            <h6 class="m-0">{{ $support->name }}</h6>
                                        </td>
                                        <td>
                                            @if ($support->status == 'In Progress')
                                                <span class="badge rounded-pill bg-warning p-2 px-3">
                                                    {{ __('In Progress') }}
                                                </span>
                                            @elseif($support->status == 'Closed')
                                                <span class="badge rounded-pill bg-success p-2 px-3">
                                                    {{ __('Closed') }}
                                                </span>
                                            @else
                                                <span class="badge rounded-pill bg-danger p-2 px-3">
                                                    {{ __('On Hold') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ __('Support Subject') }}</small>
                                            <h6 class="m-0">{{ $support->subject }}</h6>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td>
                                            <div class="col-md-12 text-center">
                                                <h6 class="m-3">{{ __('No data available in table') }}</h6>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody> --}}
                        </table>
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
