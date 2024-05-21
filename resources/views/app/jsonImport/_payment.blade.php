@extends('layouts.tenant')
@section('title', __('Json Data | Preciseca'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('jsonImport.index') }}">{{ __('Json Data') }}</a></li>
    <li class="breadcrumb-item">{{ __('Payment Voucher') }}</li>
@endsection

@section('content')
   
    <div class="row">
        <div class="col-sm-12">
            <div class="row">

                <div class="col-xl-12">
                    <div class="card sticky-top">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            @include('app.jsonImport._sidebar')
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
                                            <h5 class="mb-0">{{ __('Payment Voucher Data') }}</h5>
                                        </div>
                                        <div class="col-lg-4 d-flex justify-content-end">
                                            <div class="d-inline-block">
                                                @include('app.jsonImport._switch')   
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script>
        $(document).ready(function () {
            var dataTable = $('#bank-statement-table').DataTable();

            // Function to filter DataTable based on selected tags
            function filterData() {
                var selectedTags = [];
                $('input[type=checkbox]:checked').each(function () {
                    selectedTags.push($(this).val());
                });
                dataTable.columns('tags:name').search(selectedTags.join('|'), true, false).draw();
            }

            // Handle checkbox changes
            $('input[type=checkbox]').on('change', function () {
                filterData();
            });
        });
    </script>
    {{-- <script>
        $(document).on('change', '.edit--state-select', function() {
            var newValue = $(this).val();
            var id = $(this).data('id');
            var url = '{{ route("ledgers.input.store", ":id") }}';

            // Send the AJAX request
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    field: 'state',
                    value: newValue
                },
                success: function(response) {
                    toastr.success(response.message);
                    $('#ledger-table').DataTable().ajax.reload(); // Reload DataTable after successful update
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseJSON.message;
                    toastr.error(errorMessage);
                }
            });
        });

    </script>
    <script>
        $(document).on('change', '.edit-select', function() {
            var newValue = $(this).val();
            var id = $(this).data('id');
            var url = '{{ route("ledgers.input.store", ":id") }}';

            // Send the AJAX request
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    field: 'gst_reg_type',
                    value: newValue
                },
                success: function(response) {
                    toastr.success(response.message);
                    $('#ledger-table').DataTable().ajax.reload(); // Reload DataTable after successful update
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseJSON.message;
                    toastr.error(errorMessage);
                }
            });
        });

    </script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.editable-input', function() {
                // Hide the span (text) and show the input (editable field)
                $(this).addClass('d-none');
                $(this).siblings('.edit-input').removeClass('d-none').focus();
            });

            $(document).on('focusout', '.edit-input', function() {
                // Hide the input (editable field) and show the span (text)
                $(this).addClass('d-none');
                $(this).siblings('.editable-input').removeClass('d-none');
            });

            // Handle AJAX request when input value changes
            $(document).on('change', '.edit-input', function() {
                var fieldName = $(this).attr('name'); // Get the name of the field being edited
                var fieldValue = $(this).val();
                var id = $(this).data('id');
                var url = '{{ route("ledgers.input.store", ":id") }}';

                // Send the AJAX request
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        field: fieldName, // Send the field name being edited
                        value: fieldValue // Send the new field value
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        $('#ledger-table').DataTable().ajax.reload(); // Reload DataTable after successful update
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = xhr.responseJSON.message;
                        toastr.error(errorMessage);
                    }
                });
            });
        });
    </script> --}}
@endpush

