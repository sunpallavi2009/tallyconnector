@extends('layouts.tenant')
@section('title', __('Edit Company'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">{{ __('Companies') }}</a></li>
    <li class="breadcrumb-item">{{ __('Edit Company') }}</li>
@endsection
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="col-lg-6 col-md-8 col-xxl-4 m-auto">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ __('Edit Company') }}</h5>
                    </div>
                    <div class="card-body">
                        {!! Form::model($company, ['route' => ['companies.update', $company->id], 'method' => 'Put', 'data-validate']) !!}

                        <div class="form-group">
                            {{ Form::label('company_name', __('Company Name'), ['class' => 'form-label']) }}
                            {!! Form::text('company_name', null, ['class' => 'form-control', ' required', 'placeholder' => __('Enter company name')]) !!}
                        </div>

                        <div class="form-group">
                            {{ Form::label('gst_no', __('GST Number	'), ['class' => 'form-label']) }}
                            {!! Form::text('gst_no', null, ['class' => 'form-control',  ' required', 'placeholder' => __('Enter gst no.')]) !!}
                        </div>

                        <div class="form-group">
                            {{ Form::label('state', __('State'), ['class' => 'form-label']) }}
                            <select name="state" id="state" class="form-control">
                                @foreach($states as $stateCode => $stateName)
                                    <option value="{{ $stateCode }}" {{ $company->state == $stateCode ? 'selected' : '' }}>
                                        {{ $stateCode }} - {{ $stateName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        <div class="form-group">
                            {{ Form::label('gst_user_name', __('GST Username'), ['class' => 'form-label']) }}
                            {!! Form::text('gst_user_name', null, ['class' => 'form-control', 'placeholder' => __('Enter GST Username.')]) !!}
                        </div>

                        <div class="form-group">
                            {{ Form::label('tally_company_guid', __('Tally Company GUID'), ['class' => 'form-label']) }}
                            {!! Form::text('tally_company_guid', null, ['class' => 'form-control', 'placeholder' => __('Enter Tally Company GUID.')]) !!}
                        </div>

                        <div class="form-group">
                            {{ Form::label('token_id', __('Token ID'), ['class' => 'form-label']) }}
                            {!! Form::text('token_id', null, ['class' => 'form-control',  'readonly']) !!}
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="float-end">
                            <a href="{{ route('companies.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                            {{ Form::button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
