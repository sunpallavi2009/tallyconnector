<div class="modal fade" id="connect_to_GST" tabindex="-1" aria-labelledby="connect_to_GSTLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="connect_to_GSTLabel">Connect To GST</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {!! Form::open([
                'route' => 'gstr1.connectToGST.otpRequest',
                'method' => 'Post',
                'id' => 'otp-request-form',
                'data-validate',
            ]) !!}
            <div class="modal-body">
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
            </div>
            <div class="modal-footer">
                {{ Form::button(__('Request OTP'), ['type' => 'button', 'id' => 'request-otp-btn', 'class' => 'btn btn-primary']) }}
            </div>
            
            
            <div class="modal-body">
                <div id="otp-request-fields" style="display: none;">
                    <div class="form-group" id="otp-input-container">
                        {{ Form::label('otp', __('OTP Verify'), ['class' => 'form-label']) }}
                        <input type="text" name="otp" id="otpInput" class="form-control" placeholder="Enter OTP">
                    </div>
                </div>
            </div>
                
            {!! Form::close() !!}
            <div class="modal-footer" style="display: none;" id="verify-otp-footer">
                {{ Form::button(__('Verify OTP'), ['type' => 'button', 'id' => 'verify-otp-btn', 'class' => 'btn btn-primary']) }}
            </div>
        </div>
    </div>
</div>