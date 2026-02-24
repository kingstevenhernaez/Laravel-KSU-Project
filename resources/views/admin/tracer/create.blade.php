@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    
    <form action="{{ route('admin.tracer.store') }}" method="POST" id="surveyBuilderForm">
        @csrf
        <input type="hidden" name="is_ched_template" id="is_ched_template" value="0">

        {{-- PAGE HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Create Tracer Study</h2>
                <p class="text-muted">Design a custom survey or load the standard CHED template.</p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-success fw-bold" onclick="loadChedTemplate()">
                    <i class="fas fa-file-import me-2"></i> Load CHED Template
                </button>
                <button type="submit" class="btn btn-primary fw-bold px-4">
                    <i class="fas fa-paper-plane me-2"></i> Publish Survey
                </button>
            </div>
        </div>

        {{-- Error Handling --}}
        @if($errors->any())
            <div class="alert alert-danger shadow-sm">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- BASIC INFO CARD --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="mb-4">
                    <label class="form-label fw-bold text-uppercase small text-muted">Survey Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="surveyTitle" class="form-control form-control-lg bg-light" placeholder="e.g., Graduate Tracer Study 2026" required>
                </div>
                <div>
                    <label class="form-label fw-bold text-uppercase small text-muted">Instructions / Description</label>
                    <textarea name="description" id="surveyDescription" class="form-control bg-light" rows="3" placeholder="Provide instructions for the alumni answering this survey..."></textarea>
                </div>
            </div>
        </div>

        {{-- QUESTIONS CONTAINER --}}
        <div id="questionsContainer">
            </div>

        {{-- ADD QUESTION BUTTON --}}
        <div class="card border-0 border-primary border-opacity-25 border-dashed bg-transparent" style="border-style: dashed !important; border-width: 2px !important;">
            <div class="card-body text-center py-4">
                <button type="button" class="btn btn-primary rounded-circle shadow-sm" style="width: 40px; height: 40px;" onclick="addQuestion()">
                    <i class="fas fa-plus"></i>
                </button>
                <p class="mt-2 mb-0 fw-bold text-muted">Add Custom Question</p>
            </div>
        </div>

    </form>
</div>

{{-- JAVASCRIPT FOR DYNAMIC BUILDER --}}
<script>
    let questionCount = 0;

    // 1. Function to add a new question block
    function addQuestion(defaultText = '', defaultType = 'short_text', defaultOptions = '') {
        const container = document.getElementById('questionsContainer');
        const index = questionCount++;

        const questionHTML = `
            <div class="card border-0 shadow-sm mb-4 border-start border-warning border-4 question-block" id="question_${index}">
                <div class="card-body p-4">
                    <div class="row align-items-center g-3">
                        <div class="col-md-7">
                            <label class="form-label fw-bold text-uppercase small text-muted">Question</label>
                            <input type="text" name="questions[${index}][text]" class="form-control" placeholder="Type question here..." value="${defaultText}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-uppercase small text-muted">Answer Type</label>
                            <select name="questions[${index}][type]" class="form-select" onchange="toggleOptions(this, ${index})">
                                <option value="short_text" ${defaultType === 'short_text' ? 'selected' : ''}>Short Text</option>
                                <option value="radio" ${defaultType === 'radio' ? 'selected' : ''}>Single Choice (Radio)</option>
                                <option value="checkbox" ${defaultType === 'checkbox' ? 'selected' : ''}>Multiple Choice (Checkbox)</option>
                                <option value="dropdown" ${defaultType === 'dropdown' ? 'selected' : ''}>Dropdown</option>
                            </select>
                        </div>
                        <div class="col-md-2 text-end mt-auto">
                            <button type="button" class="btn btn-danger btn-sm fw-bold w-100" onclick="removeQuestion(${index})">
                                <i class="fas fa-trash me-1"></i> Remove
                            </button>
                        </div>
                    </div>
                    
                    {{-- Hidden options input (shows only for dropdown/radio/checkbox) --}}
                    <div class="row mt-3 options-container" id="options_${index}" style="display: ${['radio', 'checkbox', 'dropdown'].includes(defaultType) ? 'flex' : 'none'};">
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-uppercase small text-muted text-primary"><i class="fas fa-list-ul me-1"></i> Options (Comma Separated)</label>
                            <input type="text" name="questions[${index}][options]" class="form-control border-primary border-opacity-50" placeholder="e.g., Option 1, Option 2, Option 3" value="${defaultOptions}">
                            <small class="text-muted">Separate each choice with a comma.</small>
                        </div>
                    </div>
                    
                    <div class="mt-3 form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="questions[${index}][is_required]" value="1" id="req_${index}" checked>
                        <label class="form-check-label small text-muted" for="req_${index}">Required Question</label>
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', questionHTML);
    }

    // 2. Function to remove a question
    function removeQuestion(index) {
        const el = document.getElementById(`question_${index}`);
        if (el) el.remove();
    }

    // 3. Function to toggle the "Options" input field based on Answer Type
    function toggleOptions(selectElement, index) {
        const optionsContainer = document.getElementById(`options_${index}`);
        const selectedType = selectElement.value;
        
        if (['dropdown', 'radio', 'checkbox'].includes(selectedType)) {
            optionsContainer.style.display = 'flex';
        } else {
            optionsContainer.style.display = 'none';
        }
    }

    // 4. Function to instantly load the CHED format
    function loadChedTemplate() {
        if(!confirm('This will clear your current questions and load the standard CHED Template. Proceed?')) return;

        // Clear existing
        document.getElementById('questionsContainer').innerHTML = '';
        questionCount = 0;

        // Set Headers
        document.getElementById('surveyTitle').value = "Standard CHED Graduate Tracer Study";
        document.getElementById('surveyDescription').value = "Please complete this survey to help us track the employability and outcomes of our graduates. Your responses are strictly confidential.";
        document.getElementById('is_ched_template').value = "1";

        // Inject Standard CHED Questions
        addQuestion('What is your current Employment Status?', 'radio', 'Employed, Unemployed, Self-Employed, Continuing Education');
        addQuestion('If employed, what is your current Job Title?', 'short_text', '');
        addQuestion('What is the name of your current Company / Employer?', 'short_text', '');
        addQuestion('What is your current gross monthly Salary Range?', 'dropdown', 'Below Php 10k, Php 10k - 20k, Php 21k - 30k, Php 31k - 50k, Above Php 50k');
        addQuestion('Is your current job related to the course you took in college?', 'radio', 'Yes, No');
        addQuestion('What are the reasons for accepting the job? (Check all that apply)', 'checkbox', 'Salaries & Benefits, Career Challenge, Related to special skills, Proximity to residence');
    }

    // Add one blank question by default when page loads
    window.onload = function() {
        addQuestion();
    };
</script>
@endsection