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
                <div class="mb-4">
                    <label class="form-label fw-bold text-uppercase small text-muted">Instructions / Description</label>
                    <textarea name="description" id="surveyDescription" class="form-control bg-light" rows="3" placeholder="Provide instructions for the alumni answering this survey..."></textarea>
                </div>
                
                {{-- ICT Target Audience Selection --}}
                <div class="row bg-primary bg-opacity-10 p-3 rounded border border-primary border-opacity-25 mx-0">
                    <h6 class="fw-bold text-primary mb-3"><i class="fas fa-bullseye me-2"></i> Target Audience (Optional)</h6>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-dark">Target Program/Course</label>
                        <select name="target_course" class="form-select border-primary border-opacity-50">
                            <option value="">Send to All Programs</option>
                            @if(isset($courses))
                                @foreach($courses as $c)
                                    <option value="{{ $c }}">{{ $c }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-dark">Target Batch / Graduation Year</label>
                        <select name="target_batch" class="form-select border-primary border-opacity-50">
                            <option value="">Send to All Batches</option>
                            @if(isset($batches))
                                @foreach($batches as $b)
                                    <option value="{{ $b }}">Batch {{ $b }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- QUESTIONS CONTAINER --}}
        <div id="questionsContainer"></div>

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

    // 1. Generate individual choice input HTML
    function createChoiceHTML(qIndex, value, type) {
        let iconClass = 'fa-circle'; // default radio
        if (type === 'checkbox') iconClass = 'fa-square';
        if (type === 'dropdown') iconClass = 'fa-caret-square-down';

        return `
        <div class="d-flex align-items-center gap-2 mb-2 choice-item">
            <span class="choice-icon_${qIndex} text-primary opacity-50"><i class="far ${iconClass} fa-lg"></i></span>
            <input type="text" class="form-control form-control-sm border-primary border-opacity-25" value="${value}" oninput="updateHiddenOptions(${qIndex})" placeholder="Enter option..." style="max-width: 400px;">
            <button type="button" class="btn btn-sm text-danger border-0 hover-bg-light rounded-circle" onclick="removeChoiceItem(this, ${qIndex})" title="Remove Option"><i class="fas fa-times"></i></button>
        </div>`;
    }

    // 2. Add a new Question Block
    function addQuestion(defaultText = '', defaultType = 'short_text', defaultOptions = '') {
        const container = document.getElementById('questionsContainer');
        const index = questionCount++;

        // Parse default options if they exist (for CHED template)
        const optionsArray = defaultOptions ? defaultOptions.split(',').map(s => s.trim()) : [''];
        let choicesHTML = '';
        optionsArray.forEach(opt => {
            choicesHTML += createChoiceHTML(index, opt, defaultType);
        });

        // Determine if options section should be visible
        const showOptions = ['radio', 'checkbox', 'dropdown'].includes(defaultType) ? 'block' : 'none';
        
        let optionLabel = 'Choices';
        if(defaultType === 'radio') optionLabel = 'Multiple Choice (Select One)';
        if(defaultType === 'checkbox') optionLabel = 'Checkboxes (Select Multiple)';
        if(defaultType === 'dropdown') optionLabel = 'Dropdown List Items';

        const questionHTML = `
            <div class="card border-0 shadow-sm mb-4 border-start border-warning border-4 question-block" id="question_${index}">
                <div class="card-body p-4">
                    <div class="row align-items-start g-3">
                        <div class="col-md-7">
                            <label class="form-label fw-bold text-uppercase small text-muted">Question</label>
                            <input type="text" name="questions[${index}][text]" class="form-control form-control-lg bg-light" placeholder="Type question here..." value="${defaultText}" required>
                            
                            {{-- THE NEW DYNAMIC OPTIONS BUILDER --}}
                            <div class="mt-4 p-3 bg-light rounded border options-container" id="options_${index}" style="display: ${showOptions};">
                                <label class="form-label fw-bold text-uppercase small text-primary mb-3" id="options_label_${index}"><i class="fas fa-list-ul me-1"></i> ${optionLabel}</label>
                                
                                {{-- Hidden input for the Backend database --}}
                                <input type="hidden" name="questions[${index}][options]" id="hidden_options_${index}" value="${defaultOptions}">
                                
                                {{-- Visible dynamic list --}}
                                <div id="choice_list_${index}">
                                    ${choicesHTML}
                                </div>
                                
                                <button type="button" class="btn btn-sm btn-outline-primary mt-2 rounded-pill px-3" onclick="addChoiceItem(${index})">
                                    <i class="fas fa-plus me-1"></i> Add Option
                                </button>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold text-uppercase small text-muted">Answer Type</label>
                            <select name="questions[${index}][type]" class="form-select border-warning" onchange="toggleOptions(this, ${index})">
                                <option value="short_text" ${defaultType === 'short_text' ? 'selected' : ''}>Short Answer (Text)</option>
                                <option value="radio" ${defaultType === 'radio' ? 'selected' : ''}>Multiple Choice (Radio)</option>
                                <option value="checkbox" ${defaultType === 'checkbox' ? 'selected' : ''}>Checkboxes</option>
                                <option value="dropdown" ${defaultType === 'dropdown' ? 'selected' : ''}>Dropdown</option>
                            </select>

                            <div class="mt-4 form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="questions[${index}][is_required]" value="1" id="req_${index}" checked>
                                <label class="form-check-label small fw-bold text-muted" for="req_${index}">Required</label>
                            </div>
                        </div>

                        <div class="col-md-2 text-end">
                            <button type="button" class="btn btn-light text-danger btn-sm fw-bold w-100 shadow-sm" onclick="removeQuestion(${index})">
                                <i class="fas fa-trash me-1"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', questionHTML);
    }

    // 3. Add a single choice line item
    function addChoiceItem(qIndex) {
        const typeSelect = document.querySelector(`select[name="questions[${qIndex}][type]"]`);
        const type = typeSelect ? typeSelect.value : 'radio';
        
        const container = document.getElementById(`choice_list_${qIndex}`);
        container.insertAdjacentHTML('beforeend', createChoiceHTML(qIndex, '', type));
        updateHiddenOptions(qIndex);
    }

    // 4. Remove a choice line item
    function removeChoiceItem(btn, qIndex) {
        btn.closest('.choice-item').remove();
        updateHiddenOptions(qIndex);
    }

    // 5. IMPORTANT: Combine the visual inputs into the comma-separated string for the database
    function updateHiddenOptions(qIndex) {
        const container = document.getElementById(`choice_list_${qIndex}`);
        const inputs = container.querySelectorAll('input[type="text"]');
        const values = Array.from(inputs).map(input => input.value.trim()).filter(val => val !== '');
        document.getElementById(`hidden_options_${qIndex}`).value = values.join(',');
    }

    // 6. Remove a full question
    function removeQuestion(index) {
        const el = document.getElementById(`question_${index}`);
        if (el) el.remove();
    }

    // 7. Toggle Options visibility and change icons based on selected Type
    function toggleOptions(selectElement, index) {
        const optionsContainer = document.getElementById(`options_${index}`);
        const label = document.getElementById(`options_label_${index}`);
        const selectedType = selectElement.value;
        
        if (['dropdown', 'radio', 'checkbox'].includes(selectedType)) {
            optionsContainer.style.display = 'block';
            
            // Update Label text
            if(selectedType === 'radio') label.innerHTML = '<i class="fas fa-dot-circle me-1"></i> Multiple Choice (Select One)';
            if(selectedType === 'checkbox') label.innerHTML = '<i class="fas fa-check-square me-1"></i> Checkboxes (Select Multiple)';
            if(selectedType === 'dropdown') label.innerHTML = '<i class="fas fa-caret-square-down me-1"></i> Dropdown List Items';
            
            // Update icons on existing items
            let iconClass = 'fa-circle';
            if (selectedType === 'checkbox') iconClass = 'fa-square';
            if (selectedType === 'dropdown') iconClass = 'fa-caret-square-down';
            
            const icons = document.querySelectorAll(`.choice-icon_${index}`);
            icons.forEach(span => {
                span.innerHTML = `<i class="far ${iconClass} fa-lg"></i>`;
            });

            // Ensure there is at least one option to type in
            const choiceList = document.getElementById(`choice_list_${index}`);
            if(choiceList.children.length === 0) addChoiceItem(index);

        } else {
            optionsContainer.style.display = 'none';
        }
    }

    // 8. Instantly load the CHED format
    function loadChedTemplate() {
        if(!confirm('This will clear your current questions and load the standard CHED Template. Proceed?')) return;

        document.getElementById('questionsContainer').innerHTML = '';
        questionCount = 0;

        document.getElementById('surveyTitle').value = "Standard CHED Graduate Tracer Study";
        document.getElementById('surveyDescription').value = "Please complete this survey to help us track the employability and outcomes of our graduates. Your responses are strictly confidential.";
        document.getElementById('is_ched_template').value = "1";

        // Inject Standard CHED Questions. The JS will automatically build the beautiful lists!
        addQuestion('What is your current Employment Status?', 'radio', 'Employed,Unemployed,Self-Employed,Continuing Education');
        addQuestion('If employed, what is your current Job Title?', 'short_text', '');
        addQuestion('What is the name of your current Company / Employer?', 'short_text', '');
        addQuestion('What is your current gross monthly Salary Range?', 'dropdown', 'Below Php 10k,Php 10k - 20k,Php 21k - 30k,Php 31k - 50k,Above Php 50k');
        addQuestion('Is your current job related to the course you took in college?', 'radio', 'Yes,No');
        addQuestion('What are the reasons for accepting the job? (Check all that apply)', 'checkbox', 'Salaries & Benefits,Career Challenge,Related to special skills,Proximity to residence');
    }

    window.onload = function() {
        addQuestion();
    };
</script>
@endsection