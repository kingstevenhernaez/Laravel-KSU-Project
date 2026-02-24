@extends('layouts.alumni')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
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
                            @if($question->is_required)
                                <span class="text-danger ms-1" title="Required">*</span>
                            @endif
                        </h6>

                        <div class="ps-4 border-start border-2 border-light">
                            {{-- TYPE: SHORT TEXT --}}
                            @if($question->answer_type == 'short_text')
                                <input type="text" name="answers[{{ $question->id }}]" class="form-control form-control-lg bg-light" placeholder="Type your answer here..." {{ $question->is_required ? 'required' : '' }}>
                            
                            {{-- TYPE: DROPDOWN --}}
                            @elseif($question->answer_type == 'dropdown')
                                <select name="answers[{{ $question->id }}]" class="form-select form-select-lg bg-light" {{ $question->is_required ? 'required' : '' }}>
                                    <option value="">-- Please Select an Option --</option>
                                    @if(is_array($question->options))
                                        @foreach($question->options as $option)
                                            <option value="{{ $option }}">{{ $option }}</option>
                                        @endforeach
                                    @endif
                                </select>

                            {{-- TYPE: RADIO (Single Choice) --}}
                            @elseif($question->answer_type == 'radio')
                                @if(is_array($question->options))
                                    @foreach($question->options as $idx => $option)
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="q_{{ $question->id }}_{{ $idx }}" value="{{ $option }}" {{ $question->is_required ? 'required' : '' }}>
                                            <label class="form-check-label text-muted fw-bold" for="q_{{ $question->id }}_{{ $idx }}">
                                                {{ $option }}
                                            </label>
                                        </div>
                                    @endforeach
                                @endif

                            {{-- TYPE: CHECKBOX (Multiple Choice) --}}
                            @elseif($question->answer_type == 'checkbox')
                                @if(is_array($question->options))
                                    @foreach($question->options as $idx => $option)
                                        <div class="form-check mb-3">
                                            {{-- Note the [] on the name so it submits as an array --}}
                                            <input class="form-check-input" type="checkbox" name="answers[{{ $question->id }}][]" id="q_{{ $question->id }}_{{ $idx }}" value="{{ $option }}">
                                            <label class="form-check-label text-muted fw-bold" for="q_{{ $question->id }}_{{ $idx }}">
                                                {{ $option }}
                                            </label>
                                        </div>
                                    @endforeach
                                    @if($question->is_required)
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
                    <a href="{{ route('alumni.dashboard') }}" class="btn btn-link text-muted fw-bold text-decoration-none px-4">Cancel & Return</a>
                    <button type="submit" class="btn btn-success btn-lg fw-bold px-5 shadow-sm rounded-pill">
                        <i class="fas fa-paper-plane me-2"></i> Submit Answers
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection