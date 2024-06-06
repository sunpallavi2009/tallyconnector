@extends('layouts.tenant')
@section('title', __('GST Authentication'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('GST Authentication') }}</li>
@endsection
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="col-lg-6 col-md-8 col-xxl-4 m-auto">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ __('GST Authentication') }}</h5>
                    </div>
                    <div class="card-body">
                        {!! Form::open([
                            'route' => 'gstAuth.connectToGST.otpRequest',
                            'method' => 'Post',
                            'id' => 'otp-request-form',
                            'data-validate',
                        ]) !!}
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" class="form-control">
                        
                        <div class="form-group">
                            {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
                            <input type="email" name="email" value="{{ Auth::user()->email }}" class="form-control" readonly>
                        </div>
        
                        <div class="form-group">
                            {{ Form::label('company', __('Company'), ['class' => 'form-label']) }}
                            <select name="company" id="company" class="form-control">
                                <option value="">Select Company</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
        
                        <div id="companyDetails" style="display: none;">
                            {{-- Company details will be shown here --}}
                        </div>
                        
                        <div class="card-footer">
                            <div class="text-end">
                                {{ Form::button(__('Request OTP'), ['type' => 'button', 'id' => 'request-otp-btn', 'class' => 'btn btn-primary']) }}
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div id="otp-request-fields" style="display: none;">
                            <div class="form-group" id="otp-input-container">
                                {{ Form::label('otp', __('OTP Verify'), ['class' => 'form-label']) }}
                                <input type="text" name="otp" id="otpInput" class="form-control" placeholder="Enter OTP">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-end">
                            {{ Form::button(__('Verify OTP'), ['type' => 'button', 'id' => 'verify-otp-btn', 'class' => 'btn btn-primary', 'style' => 'display: none;']) }}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css">
@endpush

@push('javascript')
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js"></script>

<script>
    $(document).ready(function() {
        let txn = ''; // Variable to hold txn value

        $('#request-otp-btn').click(function(event) {
            event.preventDefault(); // Prevent default form submission

            var form = $('#otp-request-form');
            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    console.log('OTP request success response:', response); // Log the response for debugging
                    txn = response.txn; // Store txn value
                    // Parse the response if it is a JSON string
                    try {
                        response = JSON.parse(response.data);
                    } catch (e) {
                        console.error('Failed to parse response:', e);
                    }
                    if (response.status_cd === "1") {
                        $('#otp-request-fields').show(); // Show OTP input field
                        $('#request-otp-btn').prop('disabled', true); // Disable the "Request OTP" button
                        $('#verify-otp-btn').show(); // Show the "Verify OTP" button
                    } else {
                        // Handle error response
                        alert(response.status_desc || 'OTP request failed.');
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error('OTP request error:', error);
                    alert('An error occurred while requesting OTP.');
                }
            });
        });

        $('#verify-otp-btn').click(function(event) {
            event.preventDefault(); // Prevent default form submission

            var otp = $('#otpInput').val();
            $.ajax({
                url: '{{ route('gstAuth.connectToGST.otpVerify') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    otp: otp,
                    txn: txn // Include txn value in the request
                },
                success: function(response) {
                    console.log('OTP verify success response:', response); // Log the response for debugging
                    alert('OTP verified successfully.');
                    // Optionally redirect or perform other actions as needed
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error('OTP verify error:', error);
                    alert('An error occurred while verifying OTP.');
                }
            });
        });

        $('#company').change(function() {
            var companyId = $(this).val();
            if (companyId) {
                $.ajax({
                    url: '{{ route('gstAuth.connectToGST.getData') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        company_id: companyId
                    },
                    success: function(data) {
                        console.log('Company details success response:', data); // Log the response for debugging
                        if (data.success) {
                            // Display company details
                            var detailsHtml = '<p><strong>GST Number:</strong> ' + data.gst_no + '</p>' +
                                '<p><strong>GST Username:</strong> ' + data.gst_username + '</p>' +
                                '<p><strong>State:</strong> ' + data.state + '</p>' +
                                '<p><strong>Token ID:</strong> ' + data.token_id + '-' + companyId + '</p>';

                            $('#companyDetails').html(detailsHtml).show();
                            $('#request-otp-btn').prop('disabled', false);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error('Company details error:', error);
                        alert('An error occurred while fetching company details.');
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
@endpush
