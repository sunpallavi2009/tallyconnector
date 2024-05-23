<style>
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
    <li class="breadcrumb-item">{{ __('Sales Invoice Summary') }}</li>
@endsection
@section('action-btn')
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">

                <div class="col-xl-12">
                    <div class="card sticky-top">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
{{--                            @include('admin.gstr1.sidebar._gstr1-sidebar')--}}
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
                                        <h5 class="mb-0">{{ __('Sales Invoice Summary Data') }}</h5>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 d-flex justify-content-end">
                                        <button type="button" class="btn btn-primary mx-1" data-bs-toggle="modal" data-bs-target="#connect_to_GST">
                                            Connect To GST
                                        </button>
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
                                    <div class="table-responsive">
                                        @include('app.gstr1._gstAuth')
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

            </div>
        </div>
    </div>
    @include('app.gstr1.connectToGST._create');
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
        $(document).ready(function() {
            $('#request-otp-btn').click(function() {
                $('#otp-request-fields').show(); // Show OTP input field
                $(this).prop('disabled', true); // Disable the "Request OTP" button
                $('#verify-otp-btn').show();
                $('#request-otp-btn').hide();
                // Enable the "Verify OTP" button
            });
            $('#verify-otp-btn').click(function() {
                var otp = $('#otpInput').val();
                // Make an AJAX request to verify the OTP
                $.ajax({
                    url: '{{ route('gstr1.connectToGST.otpVerify') }}',
                    method: 'POST',
                    data: { otp: otp },
                    success: function(response) {
                        // Handle success response
                        console.log(response);
                        // Redirect or perform other actions as needed
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(error);
                        // Optionally display an error message to the user
                    }
                });
            });
            $('#company').change(function() {
                var companyId = $(this).val();
                if (companyId) {
                    $.ajax({
                        url: '{{ route('gstr1.connectToGST.getData') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            company_id: companyId
                        },
                        success: function(data) {
                            if (data.success) {
                                // Display company details
                                var detailsHtml = '<p><strong>GST Number:</strong> ' + data.gst_no + '</p>' +
                                    '<p><strong>GST Username:</strong> ' + data.gst_username + '</p>' +
                                    '<p><strong>State:</strong> ' + data.state + '</p>' +
                                    '<p><strong>Token ID:</strong> ' + data.token_id + '-' + companyId + '</p>';

                                $('#companyDetails').html(detailsHtml).show();
                                $('#request-otp-btn').prop('disabled', false);
                            }
                        }
                    });
                } else {
                    // Hide company details and OTP request fields if no company selected
                    $('#companyDetails').hide();
                    $('#otp-request-fields').hide();
                    $('#request-otp-btn').prop('disabled', true);
                }
            });
        });
    </script>

    <script>
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


                window.LaravelDataTables["sale-invoice-table"].column('inv_date:name').search(regexPattern, true, false).draw();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Attach click event to the DataTables table body
            $('#sale-invoice-table tbody').on('click', 'td', function () {
                // Retrieve the correct instance of DataTable
                var dataTable = $('#sale-invoice-table').DataTable();

                var cell = dataTable.cell(this);
                var colIdx = cell.index().column;

                // Check if the clicked cell is in the first column (Type column)
                if (colIdx === 0) {
                    var type = cell.data().trim();
                    var route = ''; // Default route

                    // Determine the route based on the cell content
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
