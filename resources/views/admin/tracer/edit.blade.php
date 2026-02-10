@extends('layouts.app')

@push('title')
    {{ __('Edit Tracer Survey') }}
@endpush

@section('content')
    <div class="p-30">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-20">
            <div>
                <h4 class="fs-20 fw-600 mb-2">{{ __('Edit Tracer Survey') }}</h4>
                <div class="text-muted">{{ $survey->title }}</div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.tracer_surveys.index') }}" class="btn btn-outline-secondary">{{ __('Back') }}</a>
                @if($survey->is_published)
                    <form method="POST" action="{{ route('admin.tracer_surveys.unpublish', $survey->id) }}" class="d-inline">
                        @csrf
                        <button class="btn btn-warning" type="submit">{{ __('Unpublish') }}</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.tracer_surveys.publish', $survey->id) }}" class="d-inline">
                        @csrf
                        <button class="btn btn-success" type="submit">{{ __('Publish') }}</button>
                    </form>
                @endif
                <form method="POST" action="{{ route('admin.tracer_surveys.delete', $survey->id) }}" class="d-inline" onsubmit="return confirm('{{ __('Delete this survey?') }}')">
                    @csrf
                    <button class="btn btn-danger" type="submit">{{ __('Delete') }}</button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card mb-3">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.tracer_surveys.update', $survey->id) }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Title') }}</label>
                            <input name="title" value="{{ old('title', $survey->title) }}" class="form-control @error('title') is-invalid @enderror" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Target Colleges (comma or new line)') }}</label>
                            <input name="target_colleges" value="{{ old('target_colleges', implode(', ', (array)($survey->target_rules['colleges'] ?? []))) }}" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Target Graduation Years') }}</label>
                            <select name="target_passing_year_ids[]" class="form-control" multiple>
                                @php($selectedPY = (array) old('target_passing_year_ids', (array)($survey->target_rules['passing_year_ids'] ?? [])))
                                @foreach($passingYears as $py)
                                    <option value="{{ $py->id }}" {{ in_array($py->id, $selectedPY) ? 'selected' : '' }}>{{ $py->name }}</option>
                                @endforeach
                            </select>
                            <div class="text-muted small">{{ __('Leave empty to target all years.') }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Target Programs') }}</label>
                            <select name="target_department_ids[]" class="form-control" multiple>
                                @php($selectedDept = (array) old('target_department_ids', (array)($survey->target_rules['department_ids'] ?? [])))
                                @foreach($departments as $d)
                                    <option value="{{ $d->id }}" {{ in_array($d->id, $selectedDept) ? 'selected' : '' }}>{{ $d->name }}</option>
                                @endforeach
                            </select>
                            <div class="text-muted small">{{ __('Leave empty to target all programs.') }}</div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">{{ __('Description') }}</label>
                            <textarea name="description" rows="3" class="form-control">{{ old('description', $survey->description) }}</textarea>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">{{ __('Questions') }}</h5>

                <form method="POST" action="{{ route('admin.tracer_surveys.questions.store', $survey->id) }}" class="border rounded p-3 mb-4">
                    @csrf
                    <div class="row align-items-end">
                        <div class="col-md-7 mb-2">
                            <label class="form-label">{{ __('Question') }}</label>
                            <input name="question_text" class="form-control" required>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">{{ __('Type') }}</label>
                            <select name="question_type" class="form-control" required>
                                <option value="text">{{ __('Text') }}</option>
                                <option value="textarea">{{ __('Long Text') }}</option>
                                <option value="radio">{{ __('Single Choice') }}</option>
                                <option value="checkbox">{{ __('Multiple Choice') }}</option>
                                <option value="select">{{ __('Dropdown') }}</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="is_required" value="1" id="isReq">
                                <label class="form-check-label" for="isReq">{{ __('Required') }}</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-outline-primary" type="submit">{{ __('Add Question') }}</button>
                        </div>
                    </div>
                </form>

                @if($survey->questions->isEmpty())
                    <div class="text-muted">{{ __('No questions yet.') }}</div>
                @else
                    @foreach($survey->questions->sortBy('sort_order') as $q)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-600">{{ $q->question_text }}</div>
                                    <div class="text-muted small">{{ strtoupper($q->question_type) }}{{ $q->is_required ? ' • '.__('Required') : '' }}</div>
                                </div>
                                <form method="POST" action="{{ route('admin.tracer_surveys.questions.delete', [$survey->id, $q->id]) }}" onsubmit="return confirm('{{ __('Delete this question?') }}')">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger" type="submit">{{ __('Delete') }}</button>
                                </form>
                            </div>

                            @if(in_array($q->question_type, ['radio','checkbox','select']))
                                <div class="mt-3">
                                    <div class="fw-500 mb-2">{{ __('Options') }}</div>
                                    <form method="POST" action="{{ route('admin.tracer_surveys.options.store', [$survey->id, $q->id]) }}" class="d-flex gap-2 mb-2">
                                        @csrf
                                        <input name="option_text" class="form-control" placeholder="{{ __('Option text') }}" required>
                                        <button class="btn btn-sm btn-outline-primary" type="submit">{{ __('Add') }}</button>
                                    </form>
                                    @if($q->options->isNotEmpty())
                                        <ul class="mb-0">
                                            @foreach($q->options->sortBy('sort_order') as $opt)
                                                <li class="d-flex justify-content-between align-items-center">
                                                    <span>{{ $opt->option_text }}</span>
                                                    <form method="POST" action="{{ route('admin.tracer_surveys.options.delete', [$survey->id, $q->id, $opt->id]) }}" onsubmit="return confirm('{{ __('Delete this option?') }}')">
                                                        @csrf
                                                        <button class="btn btn-sm btn-link text-danger" type="submit">{{ __('Delete') }}</button>
                                                    </form>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="text-muted small">{{ __('No options yet.') }}</div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
