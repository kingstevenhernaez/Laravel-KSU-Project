{{-- resources/views/admin/tracer_surveys/partials/form.blade.php --}}

@csrf

<div class="row rg-20">

    <div class="col-md-6">
        <label class="form-label fw-600">{{ __('Title') }} <span class="text-danger">*</span></label>
        <input
            type="text"
            name="title"
            class="form-control @error('title') is-invalid @enderror"
            value="{{ old('title', $tracerSurvey->title ?? '') }}"
            placeholder="e.g. Tracer Survey 2026"
            required
        >
        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label fw-600">{{ __('Year') }}</label>
        <input
            type="number"
            name="year"
            class="form-control @error('year') is-invalid @enderror"
            value="{{ old('year', $tracerSurvey->year ?? date('Y')) }}"
            min="2000"
            max="2100"
        >
        @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label fw-600">{{ __('Status') }}</label>
        <select name="status" class="form-select @error('status') is-invalid @enderror">
            <option value="0" {{ (string)old('status', $tracerSurvey->status ?? 0) === '0' ? 'selected' : '' }}>{{ __('Draft') }}</option>
            <option value="1" {{ (string)old('status', $tracerSurvey->status ?? 0) === '1' ? 'selected' : '' }}>{{ __('Published') }}</option>
        </select>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <label class="form-label fw-600">{{ __('Description / Notes') }}</label>
        <textarea
            name="description"
            class="form-control @error('description') is-invalid @enderror"
            rows="4"
            placeholder="Optional notes for this survey"
        >{{ old('description', $tracerSurvey->description ?? '') }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

</div>

<div class="d-flex justify-content-end mt-4">
    <button type="submit" class="btn btn-primary">
        {{ isset($tracerSurvey) && !empty($tracerSurvey->id) ? __('Update') : __('Create') }}
    </button>
</div>
