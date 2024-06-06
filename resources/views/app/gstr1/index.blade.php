@extends('layouts.tenant')
@section('title', __('Sales Invoice Summary'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Sales Invoice Summary') }}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card sticky-top">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            {{-- Sidebar content here --}}
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="card">
                        <div class="tab-pane fade show active" id="apps-setting" role="tabpanel" aria-labelledby="landing-apps-setting">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 d-flex align-items-center">
                                        <h5 class="mb-0">{{ __('Sales Invoice Summary Data') }}</h5>
                                    </div>
                                    <div class="col-lg-8 d-flex align-items-center">
                                        @if(isset($txnId))
                                            <h5 class="mb-0">Transaction ID: </h5> <p>{{ $txnId }}</p>
                                        @endif
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 d-flex justify-content-end">
                                        <div class="row">
                                            <div class="col-xl-4">
                                                <select id="year-filter" class="form-select">
                                                    <option value="">All Years</option>
                                                    @php
                                                        $currentYear = date('Y');
                                                    @endphp
                                                    @for ($year = $currentYear; $year >= 2021; $year--)
                                                        <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>{{ $year }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="col-xl-2">
                                                <select id="month-filter">
                                                    <option value="">All Months</option>
                                                    <option value="01">January</option>
                                                    <option value="02">February</option>
                                                    <option value="03">March</option>
                                                    <option value="04">April</option>
                                                    <option value="05">May</option>
                                                    <option value="06">June</option>
                                                    <option value="07">July</option>
                                                    <option value="08">August</option>
                                                    <option value="09">September</option>
                                                    <option value="10">October</option>
                                                    <option value="11">November</option>
                                                    <option value="12">December</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card-body table-border-style">
                                    <div class="table-responsive">
                                        {{ $dataTable->table(['width' => '100%']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card-body table-border-style">
                                    <div class="table-responsive" id="your-table-container">
                                        {{-- @include('app.gstr1.gstAuth.index') --}}
                                    </div>
                                </div>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css">
@endpush
@push('javascript')
    @include('layouts.includes.datatable-js')
    {{ $dataTable->scripts() }}
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js"></script>

    <script>
        $(document).ready(function () {
            var ipAddress = '{{ $ipAddress }}';
            var txnId = '{{ $txnId }}';
    
            $('#month-filter').selectpicker({
                multiple: false,
                liveSearch: true,
                title: 'Select month'
            });
    
            $('#year-filter, #month-filter').change(function () {
                var month = $('#month-filter').val();
                var year = $('#year-filter').val();
    
                if (month && year) {
                    var retperiod = month.padStart(2, '0') + year;
    
                    $.ajax({
                        url: '{{ route("gstAuthData") }}',
                        method: 'GET',
                        data: { retperiod: retperiod },
                        success: function (response) {
                            console.log(response);
                            // Update the page content with the new data
                            $('#your-table-container').html(response);
                        },
                        error: function (xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>
    

    <script>
        $(document).ready(function() {
            $('#sale-invoice-table tbody').on('click', 'td', function () {
                var dataTable = $('#sale-invoice-table').DataTable();
                var cell = dataTable.cell(this);
                var colIdx = cell.index().column;

                if (colIdx === 0) {
                    var type = cell.data().trim();
                    var route = ''; 

                    switch (type) {
                        case 'B2B':
                            route = '{{ route("gstr1.b2b") }}';
                            break;
                        case 'B2CS':
                            route = '{{ route("gstr1.b2cs") }}';
                            break;
                        case 'B2CL':
                            route = '{{ route("gstr1.b2cl") }}';
                            break;
                        case 'CDNR':
                            route = '{{ route("gstr1.cdnr") }}';
                            break;
                        case 'CDNUR':
                            route = '{{ route("gstr1.cdnur") }}';
                            break;
                        case 'EXP':
                            route = '{{ route("gstr1.exp") }}';
                            break;
                            case 'NIL':
                            route = '{{ route("gstr1.nil") }}';
                            break;
                    }

                    // Navigate to the specified route
                    if (route !== '') {
                        window.location.href = route;
                    }
                }
            });
        });
    </script>
@endpush
