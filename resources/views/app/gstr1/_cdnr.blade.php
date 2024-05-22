@extends('layouts.tenant')
@section('title', __('Sales Invoice Summary'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('gstr1.index') }}">{{ __('Sales Invoice Summary') }}</a></li>
    <li class="breadcrumb-item">{{ __('CDNR') }}</li>
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
                                    <div class="col-lg-8 d-flex align-items-center">
                                        <h5 class="mb-0">{{ __('CDNR Data') }}</h5>
                                    </div>
                                    <div class="col-lg-4 d-flex justify-content-end">
                                        <div class="form-switch custom-switch-v1 d-inline-block">

                                            <div class="col-lg-4 d-flex justify-content-end">
                                                <div class="form-check form-switch custom-switch-v1 d-inline-block">
                                                    <input class="form-check-input" type="checkbox" id="excelCheckbox" value="excel">
                                                    <label class="form-check-label" for="excelCheckbox">Excel</label>
                                                </div>
                                                <div class="form-check form-switch custom-switch-v1 d-inline-block">
                                                    <input class="form-check-input" type="checkbox" id="tallyCheckbox" value="tally">
                                                    <label class="form-check-label" for="tallyCheckbox">Tally</label>
                                                </div>
                                                <div class="form-check form-switch custom-switch-v1 d-inline-block">
                                                    <input class="form-check-input" type="checkbox" id="gstCheckbox" value="gst">
                                                    <label class="form-check-label" for="gstCheckbox">GST</label>
                                                </div>
                                            </div>



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

    <script>
        $(document).ready(function () {
            var dataTable = $('#cdnr-table').DataTable();

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
@endpush
