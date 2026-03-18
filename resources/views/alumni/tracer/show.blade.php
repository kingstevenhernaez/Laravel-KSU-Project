@extends('layouts.alumni')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            {{-- 🟢 READ-ONLY ALERT MESSAGE --}}
            @if($hasAnswered)
                <div class="alert alert-success shadow-sm rounded-4 mb-4 border-0 border-start border-4 border-success">
                    <i class="fas fa-check-circle me-2 text-success"></i> <strong>Read-Only Mode:</strong> You have already submitted this survey. Your saved responses are shown below.
                </div>
            @endif

            {{-- Header Card --}}
            <div class="card border-0 shadow-sm mb-4 border-top border-success border-4 rounded-4">
                <div class="card-body p-5 text-center">
                    <div class="mb-3">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-clipboard-check fa-3x"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-2">{{ $survey->title }}</h3>
                    @if($survey->description)
                        <p class="text-muted mb-0">{{ $survey->description }}</p>
                    @endif
                </div>
            </div>

            {{-- The Survey Form --}}
            <form action="{{ route('alumni.tracer.store', $survey->id) }}" method="POST">
                @csrf

                @foreach($survey->questions as $index => $question)
                <div class="card border-0 shadow-sm mb-4 rounded-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-4 text-dark" style="font-size: 1.1rem;">
                            <span class="text-success me-1">{{ $index + 1 }}.</span> {{ $question->question_text }}
                            @if($question->is_required && !$hasAnswered)
                                <span class="text-danger ms-1" title="Required">*</span>
                            @endif
                        </h6>

                        <div class="ps-4 border-start border-2 border-light">
                            
                            {{-- TYPE: SHORT TEXT --}}
                            @if($question->answer_type == 'short_text')
                                <input type="text" name="answers[{{ $question->id }}]" class="form-control form-control-lg bg-light" placeholder="Type your answer here..." value="{{ $previousAnswers[$question->id] ?? '' }}" {{ $hasAnswered ? 'readonly' : '' }} {{ $question->is_required && !$hasAnswered ? 'required' : '' }}>
                            
                            {{-- TYPE: DROPDOWN --}}
                            @elseif($question->answer_type == 'dropdown')
                                <select name="answers[{{ $question->id }}]" class="form-select form-select-lg bg-light" {{ $hasAnswered ? 'disabled' : '' }} {{ $question->is_required && !$hasAnswered ? 'required' : '' }}>
                                    <option value="">-- Please Select an Option --</option>
                                    @if(is_array($question->options))
                                        @foreach($question->options as $option)
                                            <option value="{{ $option }}" {{ (isset($previousAnswers[$question->id]) && $previousAnswers[$question->id] == $option) ? 'selected' : '' }}>{{ $option }}</option>
                                        @endforeach
                                    @endif
                                </select>

                            {{-- TYPE: RADIO (Single Choice) --}}
                            @elseif($question->answer_type == 'radio')
                                @if(is_array($question->options))
                                    @foreach($question->options as $idx => $option)
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="q_{{ $question->id }}_{{ $idx }}" value="{{ $option }}" {{ (isset($previousAnswers[$question->id]) && $previousAnswers[$question->id] == $option) ? 'checked' : '' }} {{ $hasAnswered ? 'disabled' : '' }} {{ $question->is_required && !$hasAnswered ? 'required' : '' }}>
                                            <label class="form-check-label text-muted fw-bold" for="q_{{ $question->id }}_{{ $idx }}">
                                                {{ $option }}
                                            </label>
                                        </div>
                                    @endforeach
                                @endif

                            {{-- TYPE: CHECKBOX (Multiple Choice) --}}
                            @elseif($question->answer_type == 'checkbox')
                                @if(is_array($question->options))
                                    @php
                                        // Explode the saved comma-separated string back into an array to check the boxes
                                        $savedChecks = isset($previousAnswers[$question->id]) ? explode(', ', $previousAnswers[$question->id]) : [];
                                    @endphp
                                    @foreach($question->options as $idx => $option)
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="answers[{{ $question->id }}][]" id="q_{{ $question->id }}_{{ $idx }}" value="{{ $option }}" {{ in_array($option, $savedChecks) ? 'checked' : '' }} {{ $hasAnswered ? 'disabled' : '' }}>
                                            <label class="form-check-label text-muted fw-bold" for="q_{{ $question->id }}_{{ $idx }}">
                                                {{ $option }}
                                            </label>
                                        </div>
                                    @endforeach
                                    @if($question->is_required && !$hasAnswered)
                                        <small class="text-warning mt-2 d-block"><i class="fas fa-info-circle me-1"></i> Please check at least one option.</small>
                                    @endif
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach

                {{-- Action Buttons --}}
                <div class="d-flex justify-content-between align-items-center mt-4 mb-5">
                    {{-- 🟢 THE FIX: Back button always goes to the survey list! --}}
                    <a href="{{ route('tracer_surveys.index') }}" class="btn btn-link text-muted fw-bold text-decoration-none px-4">
                        <i class="fas fa-arrow-left me-2"></i> {{ $hasAnswered ? 'Back to Surveys' : 'Cancel & Return' }}
                    </a>
                    
                    {{-- 🟢 THE FIX: Hide the submit button if they already answered --}}
                    @if(!$hasAnswered)
                        <button type="submit" class="btn btn-success btn-lg fw-bold px-5 shadow-sm rounded-pill">
                            <i class="fas fa-paper-plane me-2"></i> Submit Answers
                        </button>
                    @endif
                </div>
            </form>

        </div>
    </div>
</div>
@endsection