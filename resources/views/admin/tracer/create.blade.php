@extends('layouts.app')

@push('title')
    {{ __('Create Tracer Survey') }}
@endpush

@section('content')
    <div class="p-30">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-20">
            <h4 class="fs-20 fw-600">{{ __('Create Tracer Survey') }}</h4>
            <a href="{{ route('admin.tracer_surveys.index') }}" class="btn btn-outline-secondary">{{ __('Back') }}</a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">{{ __('Please fix the errors below.') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.tracer_surveys.store') }}">
                    @csrf
                    @include('admin.tracer_surveys.partials.form', ['survey' => null])
                    <button class="btn btn-primary" type="submit">{{ __('Create') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
