@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="fas fa-paper-plane text-success me-2"></i> Compose Email Blast</h5>
            <a href="{{ route('admin.emails.index') }}" class="btn btn-sm btn-light border">
                <i class="fas fa-arrow-left me-1"></i> Back to History
            </a>
        </div>
        
        <div class="card-body p-4">
            <form action="{{ route('admin.emails.send') }}" method="POST">
                @csrf
                
                {{-- 1. Recipient Group --}}
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small">RECIPIENT GROUP</label>
                    <select class="form-select" name="recipient_type" id="recipientType" onchange="toggleSpecificEmail()">
                        <option value="all">Send to All Verified Alumni</option>
                        <option value="specific">Send to Specific Alumni</option>
                    </select>
                </div>

                {{-- 2. Specific Email Dropdown (Hidden by Default) --}}
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

                {{-- 4. Message --}}
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small">MESSAGE BODY</label>
                    <textarea name="message" class="form-control" rows="8" placeholder="Write your message here..." required></textarea>
                    <small class="text-muted mt-2 d-block">Note: Plain text is supported. Use HTML if you want to include links or images.</small>
                </div>

                <button type="submit" class="btn btn-success w-100 fw-bold py-3" style="background-color: #198754;">
                    <i class="fas fa-paper-plane me-2"></i> Send Blast Now
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Dynamic Toggle Script --}}
<script>
    function toggleSpecificEmail() {
        var type = document.getElementById('recipientType').value;
        var specificDiv = document.getElementById('specificEmailDiv');
        var specificInput = document.getElementById('specificEmail');
        
        if (type === 'specific') {
            specificDiv.style.display = 'block';
            specificInput.setAttribute('required', 'required'); // Force them to pick someone
        } else {
            specificDiv.style.display = 'none';
            specificInput.removeAttribute('required');
            specificInput.value = ''; // Clear out the value just in case
        }
    }
</script>
@endsection