@extends('layouts.app')
@push('title')
    {{ __('Alumni Report Builder') }}
@endpush

@section('content')
    <div class="p-30">
        <h4 class="fs-24 fw-500 lh-34 text-black pb-16">{{ __('Alumni Report Builder') }}</h4>

        <div class="bg-white p-20 radius-8">
            <form method="POST" action="{{ route('admin.analytics.alumni_report.print') }}" target="_blank">
                @csrf

                <div class="row rg-20">
                    <div class="col-lg-4">
                        <label class="form-label fw-500">{{ __('Graduation Year') }}</label>
                        <select class="form-control" name="graduation_year">
                            <option value="">{{ __('All') }}</option>
                            @foreach($years as $y)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label fw-500">{{ __('College') }}</label>
                        <select class="form-control" name="college_name">
                            <option value="">{{ __('All') }}</option>
                            @foreach($colleges as $c)
                                <option value="{{ $c }}">{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label fw-500">{{ __('Program') }}</label>
                        <select class="form-control" name="program_name">
                            <option value="">{{ __('All') }}</option>
                            @foreach($programs as $p)
                                <option value="{{ $p }}">{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="fw-600 mb-2">{{ __('Select columns to print') }}</div>
                    <div class="row">
                        @foreach($availableColumns as $key => $label)
                            <div class="col-lg-3 col-md-4 col-6 mb-2">
                                <label class="d-flex align-items-center cg-10">
                                    <input type="checkbox" name="columns[]" value="{{ $key }}" {{ in_array($key, $selectedColumns ?? [], true) ? 'checked' : '' }}>
                                    <span>{{ __($label) }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">
                        {{ __('Print Report') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
