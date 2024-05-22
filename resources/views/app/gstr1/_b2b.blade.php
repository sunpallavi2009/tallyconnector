<style>
    /* Add this CSS to your stylesheet */
    .dropdown.bootstrap-select.show-tick.form-select{
        padding: 0;
    }

    .dropdown.bootstrap-select.show-tick.form-select .bs-searchbox input[type="search"].form-control {
        padding: 0.375rem;
    }

</style>
@extends('layouts.tenant')
@section('title', __('Sales Invoice Summary'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('gstr1.index') }}">{{ __('Sales Invoice Summary') }}</a></li>
    <li class="breadcrumb-item">{{ __('B2B') }}</li>
@endsection
@section('action-btn')
    <div class="row">

        <div class="col-xl-2">
        <select id="month-filter" class="form-select" multiple>
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

        <div class="col-xl-2">
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

        <div class="col-xl-8">
            <div class="row">
                <div class="col-lg-3">
{{--                    <div class="form-switch custom-switch-v1 d-inline-block">--}}
                        <div class="form-check">
                            <input class="form-check-input" name="excel" type="checkbox" id="excel-checkbox">
                            <label for="cust-theme-bg" class="form-check-label f-w-600 pl-1 me-2">Excel</label>
                        </div>
{{--                    </div>--}}
                </div>

                <div class="col-lg-3">
{{--                    <div class="form-switch custom-switch-v1 d-inline-block">--}}
                        <div class="form-check">
                            <input class="form-check-input" name="tally" type="checkbox" id="tally-checkbox">
                            <label for="cust-theme-bg" class="form-check-label f-w-600 pl-1 me-2">Tally</label>
                        </div>
{{--                    </div>--}}
                </div>

                <div class="col-lg-3">
{{--                    <div class="form-switch custom-switch-v1 d-inline-block">--}}
                        <div class="form-check">
                            <input class="form-check-input" name="gst" type="checkbox" id="gst-checkbox">
                            <label for="cust-theme-bg" class="form-check-label f-w-600 pl-1 me-2">Gst</label>
                        </div>
{{--                    </div>--}}
                </div>
            </div>
        </div>

    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card sticky-top">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            @include('app.gstr1.sidebar._gstr1-sidebar')
                        </div>
                    </div>
                </div>

                <div class="col-xl-12">
                    <div class="card">
                        <div class="tab-pane fade show active" id="apps-setting" role="tabpanel"
                             aria-labelledby="landing-apps-setting">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-3 d-flex align-items-center">
                                        <h5 class="mb-0">{{ __('B2B Data') }}</h5>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css">
@endpush

@push('javascript')
    @include('layouts.includes.datatable-js')
    {{ $dataTable->scripts() }}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js"></script>

    <script>
        $(document).ready(function () {
            function updateDataTable() {
                var excelChecked = $('#excel-checkbox').prop('checked');
                var tallyChecked = $('#tally-checkbox').prop('checked');
                var gstChecked = $('#gst-checkbox').prop('checked');

                // Initialize filter arrays
                var tags = [];

                // Push tags based on checked checkboxes
                if (excelChecked) {
                    tags.push('excel');
                }
                if (tallyChecked) {
                    tags.push('tally');
                }
                if (gstChecked) {
                    tags.push('gst');
                }

                // Combine the checked values into a single string
                var combinedTags = tags.join(',');

                // Filter the DataTable based on the combined tags
                window.LaravelDataTables["b2b-table"].column('tags:name').search(combinedTags).draw();

                // Send the combined tags to the server
                window.LaravelDataTables["b2b-table"].ajax.reload();
            }

            // Event listener for checkbox changes
            $('#excel-checkbox, #tally-checkbox, #gst-checkbox').change(function () {
                updateDataTable();
            });





        $(document).ready(function () {

            $('#month-filter').selectpicker({
                multiple: true,
                liveSearch: true,
                title: 'Select months',
                selectedTextFormat: 'count > 3'
            });

            $('#month-filter, #year-filter').change(function () {
                var months = $('#month-filter').val();
                var year = $('#year-filter').val();


                var formattedMonths = [];
                if (months && year) {
                    months.forEach(function (month) {
                        formattedMonths.push(year + '-' + month.padStart(2, '0') + '-');
                    });
                }


                var regexPattern = formattedMonths.join('|');


                window.LaravelDataTables["b2b-table"].column('inv_date:name').search(regexPattern, true, false).draw();
            });
        });


    </script>
@endpush
