@extends('layouts.tenant')
@section('title', __('Credit Note'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('credit-note.index') }}">{{ __('Credit Note') }}</a></li>
    <li class="breadcrumb-item">{{ __('View Credit Note') }}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-xl-12">
                    <div class="card sticky-top">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            @include('app.credit-note.sidebar._credit-note-sidebar')
                        </div>
                    </div>
                </div>
                <!-- DataTable -->
                <div class="col-xl-12">
                    <div class="card">
                        <div class="tab-pane fade show active" id="apps-setting" role="tabpanel" aria-labelledby="landing-apps-setting">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 d-flex align-items-center">
                                        <h5 class="mb-0">{{ __('Credit Note Data') }}</h5>
                                    </div>
                                    <div class="col-lg-4 d-flex justify-content-end">
                                        <div class="form-switch custom-switch-v1 d-inline-block">
                                            <button class="btn btn-primary send-to-tally">Send to Tally</button>
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
    <script src="https://cdn.datatables.net/fixedheader/3.2.0/js/dataTables.fixedHeader.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/3.3.3/js/dataTables.fixedColumns.min.js"></script>

    <script>
        $(document).ready(function() {
            // Check/uncheck all checkboxes when "select all" checkbox is clicked
            $('#select-all-checkbox').on('change', function () {
                $('.select-row-checkbox').prop('checked', $(this).prop('checked'));
            });

            // Update the state of the "select all" checkbox based on individual checkboxes
            $('.select-row-checkbox').on('change', function () {
                if ($('.select-row-checkbox:checked').length === $('.select-row-checkbox').length) {
                    $('#select-all-checkbox').prop('checked', true);
                } else {
                    $('#select-all-checkbox').prop('checked', false);
                }
            });
        });
    </script>
    {{-- <script>
        $(document).ready(function() {
            $('.send-to-tally').on('click', function() {
                var selectedRowsData = [];


                // Loop through each checked row and retrieve its data
                $('.select-row-checkbox:checked').each(function() {
                    var rowData = [];
                    // Assuming the data you want to retrieve is in table cells (td elements)
                    $(this).closest('tr').find('td').each(function() {
                        rowData.push($(this).text());
                    });
                    selectedRowsData.push(rowData);
                });

                // Log selectedRowsData to check if it's populated correctly
                console.log(selectedRowsData);

                // Send an AJAX request to store the selected rows data
                $.ajax({
                    url: '{{ route("store.in.ecommerce.table") }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        data: selectedRowsData
                    },
                    // success: function(response) {
                    //     console.log('Data sent to eCommerce table successfully');
                    //     // You can add additional logic here if needed, such as updating the UI
                    // },
                    // error: function(xhr, status, error) {
                    //     console.error('Error sending data to eCommerce table:', error);
                    //     // Handle error responses here if needed
                    // }
                });
            });
        });
    </script> --}}
@endpush
