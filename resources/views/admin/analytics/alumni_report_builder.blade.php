@extends('layouts.app')

@section('content')
<div class="p-30">
    <div class="d-flex justify-content-between align-items-center mb-20">
        <h4 class="mb-0">{{ __('Alumni Report Builder') }}</h4>
        <a href="{{ route('admin.analytics.index') }}" class="zBtn zBtn-secondary">{{ __('Back to Analytics') }}</a>
    </div>

    <div class="bg-white p-20 border radius-10">
        <form method="POST" action="{{ route('admin.analytics.alumni_report.print') }}" target="_blank">
            @csrf

            <div class="row rg-20">
                <div class="col-md-3">
                    <label class="mb-5 fw-600">{{ __('Graduation Year') }}</label>
                    <select name="graduation_year" class="form-control">
                        <option value="">{{ __('All') }}</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="mb-5 fw-600">{{ __('Program') }}</label>
                    <select name="program_name" class="form-control">
                        <option value="">{{ __('All') }}</option>
                        @foreach($programs as $p)
                            <option value="{{ $p }}">{{ $p }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button class="zBtn zBtn-primary w-100" type="submit">{{ __('Print Report') }}</button>
                </div>
            </div>

            <hr class="my-20">

            <div class="mb-10 fw-600">{{ __('Select Columns') }}</div>
            <div class="row rg-10">
                @php
                    $cols = [
                        'student_number' => 'Student No.',
                        'first_name' => 'First Name',
                        'middle_name' => 'Middle Name',
                        'last_name' => 'Last Name',
                        'program_code' => 'Program Code',
                        'program_name' => 'Program',
                        'graduation_year' => 'Grad Year',
                    ];
                    $default = ['student_number','first_name','last_name','program_name','graduation_year'];
                @endphp

                @foreach($cols as $key => $label)
                    <div class="col-md-3">
                        <label class="d-flex align-items-center cg-10">
                            <input type="checkbox" name="columns[]" value="{{ $key }}" {{ in_array($key, $default) ? 'checked' : '' }}>
                            <span>{{ $label }}</span>
                        </label>
                    </div>
                @endforeach
            </div>
        </form>
    </div>
</div>
@endsection
