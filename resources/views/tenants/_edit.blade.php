@extends('layouts.main')
@section('title', __('Edit Tenant | Preciseca'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('tenants.index') }}">{{ __('Tenants') }}</a></li>
    <li class="breadcrumb-item">{{ __('Edit Tenant') }}</li>
@endsection
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="col-lg-6 col-md-8 col-xxl-4 m-auto">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ __('Edit Tenant') }}</h5>
                    </div>
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        {!! Form::model($tenant, ['route' => ['tenants.update', $tenant->id], 'method' => 'Put', 'data-validate']) !!}

                        
                        <div class="form-group">
                            {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                            {!! Form::text('name', null, ['class' => 'form-control', ' required', 'placeholder' => __('Enter Name')]) !!}
                        </div>

                        <div class="form-group">
                            {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
                            {!! Form::email('email', null, ['class' => 'form-control',  ' required', 'placeholder' => __('Enter Email')]) !!}
                        </div>

                        {{-- <div class="form-group">
                            {{ Form::label('domain_name', __('Domain Name'), ['class' => 'form-label']) }}
                            {!! Form::text('domain_name', null, ['class' => 'form-control', ' required', 'placeholder' => __('Enter Domain Name')]) !!}
                        </div> --}}

                        <div class="form-group mb-3">
                            <label class="form-label">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control pwstrength" data-indicator="pwindicator"
                                name="password" placeholder="{{ __('Enter password') }}">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">{{ __('Password Confirmation') }}</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                                autocomplete="new-password" placeholder="{{ __('Enter password confirmation') }}">
                        </div>
                        
                        
                    </div>
                    <div class="card-footer">
                        <div class="text-end">
                            <a href="{{ route('tenants.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                            {{ Form::button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
