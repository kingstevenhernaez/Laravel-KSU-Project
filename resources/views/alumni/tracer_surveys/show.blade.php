@extends('layouts.app')

@push('title')
    {{ __('Tracer Survey') }}
@endpush

@section('content')
    <div class="p-30">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <div>
                <h4 class="fs-20 fw-600 lh-24 text-1b1c17 mb-1">{{ $survey->title }}</h4>
                @if($survey->description)
                    <div class="text-muted">{{ $survey->description }}</div>
                @endif
            </div>
            <div>
                <a href="{{ route('tracer_surveys.index') }}" class="btn btn-outline-secondary">{{ __('Back') }}</a>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="bg-white bd-one bd-c-black-10 bd-ra-10 p-20">
            @if($existing)
                <div class="alert alert-success mb-3">
                    {{ __('You have already submitted this survey.') }}
                </div>
            @endif

            <form method="POST" action="{{ route('tracer_surveys.submit', $survey->id) }}">
                @csrf

                @foreach($survey->questions as $q)
                    @php($name = 'q_' . $q->id)
                    <div class="mb-4">
                        <label class="form-label fw-600">
                            {{ $q->question_text }}
                            @if($q->is_required)
                                <span class="text-danger">*</span>
                            @endif
                        </label>

                        @if(in_array($q->question_type, ['text','textarea'], true))
                            @if($q->question_type === 'textarea')
                                <textarea class="form-control" name="{{ $name }}" rows="3" @disabled($existing)>{{ old($name) }}</textarea>
                            @else
                                <input type="text" class="form-control" name="{{ $name }}" value="{{ old($name) }}" @disabled($existing)>
                            @endif
                        @elseif(in_array($q->question_type, ['radio','select'], true))
                            @if($q->question_type === 'select')
                                <select class="form-select" name="{{ $name }}" @disabled($existing)>
                                    <option value="">{{ __('Select...') }}</option>
                                    @foreach($q->options as $opt)
                                        <option value="{{ $opt->option_text }}" @selected(old($name) === $opt->option_text)>
                                            {{ $opt->option_text }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <div class="d-flex flex-column gap-2">
                                    @foreach($q->options as $opt)
                                        <label class="d-flex align-items-center gap-2">
                                            <input type="radio" name="{{ $name }}" value="{{ $opt->option_text }}" @checked(old($name) === $opt->option_text) @disabled($existing)>
                                            <span>{{ $opt->option_text }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        @elseif($q->question_type === 'checkbox')
                            <div class="d-flex flex-column gap-2">
                                @foreach($q->options as $opt)
                                    @php($arr = (array) old($name, []))
                                    <label class="d-flex align-items-center gap-2">
                                        <input type="checkbox" name="{{ $name }}[]" value="{{ $opt->option_text }}" @checked(in_array($opt->option_text, $arr, true)) @disabled($existing)>
                                        <span>{{ $opt->option_text }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <input type="text" class="form-control" name="{{ $name }}" value="{{ old($name) }}" @disabled($existing)>
                        @endif

                        @error($name)
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach

                @if(!$existing)
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">{{ __('Submit Survey') }}</button>
                    </div>
                @endif
            </form>
        </div>
    </div>
@endsection
