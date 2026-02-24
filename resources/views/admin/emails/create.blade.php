@extends('layouts.admin')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="fas fa-paper-plane text-success me-2"></i> Compose Email Blast</h5>
            <a href="{{ route('admin.emails.index') }}" class="btn btn-sm btn-light border">
                <i class="fas fa-arrow-left me-1"></i> Back to History
            </a>
        </div>
        
        <div class="card-body p-4">
            {{-- 🟢 ADDED: enctype="multipart/form-data" to allow file uploads --}}
            <form action="{{ route('admin.emails.send') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                {{-- 1. Recipient Group --}}
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small">RECIPIENT GROUP</label>
                    <select class="form-select" name="recipient_type" id="recipientType" onchange="toggleSpecificEmail()">
                        <option value="all">Send to All Verified Alumni</option>
                        <option value="specific">Send to Specific Alumni</option>
                    </select>
                </div>

                {{-- 2. Specific Email Dropdown --}}
                <div class="mb-4" id="specificEmailDiv" style="display: none;">
                    <label class="form-label fw-bold text-muted small">SELECT ALUMNI</label>
                    <select class="form-select" name="specific_email" id="specificEmail">
                        <option value="">-- Choose an Alumni --</option>
                        @foreach($alumni as $alum)
                            <option value="{{ $alum->email }}">{{ $alum->first_name }} {{ $alum->last_name }} ({{ $alum->email }})</option>
                        @endforeach
                    </select>
                </div>

                {{-- 3. Subject --}}
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small">EMAIL SUBJECT</label>
                    <input type="text" name="subject" class="form-control" placeholder="Enter subject line" required>
                </div>

                {{-- 4. Message (WYSIWYG) --}}
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small">MESSAGE BODY</label>
                    {{-- 🟢 ADDED: id="summernote" to connect to the text editor --}}
                    <textarea name="message" id="summernote" class="form-control" required></textarea>
                </div>

                {{-- 5. Attachment --}}
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small"><i class="fas fa-paperclip me-1"></i> FILE ATTACHMENT (OPTIONAL)</label>
                    <input type="file" name="attachment" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.zip,.jpg,.png">
                    <small class="text-muted mt-1 d-block">Max file size: 5MB.</small>
                </div>

                <button type="submit" class="btn btn-success w-100 fw-bold py-3" style="background-color: #198754;">
                    <i class="fas fa-paper-plane me-2"></i> Send Blast Now
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Initialize the WYSIWYG Editor
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 300,
            placeholder: 'Write your beautifully formatted message here...',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'hr']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
    });

    // Toggle specific email dropdown
    function toggleSpecificEmail() {
        var type = document.getElementById('recipientType').value;
        var specificDiv = document.getElementById('specificEmailDiv');
        var specificInput = document.getElementById('specificEmail');
        
        if (type === 'specific') {
            specificDiv.style.display = 'block';
            specificInput.setAttribute('required', 'required');
        } else {
            specificDiv.style.display = 'none';
            specificInput.removeAttribute('required');
            specificInput.value = ''; 
        }
    }
</script>
@endsection