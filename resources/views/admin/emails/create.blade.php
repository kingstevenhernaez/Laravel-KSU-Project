@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="fas fa-paper-plane text-success me-2"></i> Compose Email Blast
                    </h5>
                    <a href="{{ route('admin.emails.index') }}" class="btn btn-sm btn-light rounded-pill px-3">
                        <i class="fas fa-arrow-left me-1"></i> Back to History
                    </a>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('admin.emails.send') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Recipient Group</label>
                                <select name="recipient_type" id="recipient_type" class="form-select rounded-3 border-secondary-subtle">
                                    <option value="all">Send to All Alumni</option>
                                    <option value="specific">Send to Specific Email</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 d-none" id="specific_email_container">
                                <label class="form-label fw-bold small text-uppercase text-muted">Target Email</label>
                                <input type="email" name="specific_email" class="form-control rounded-3 border-secondary-subtle" placeholder="Enter email address...">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Email Subject</label>
                            <input type="text" name="subject" class="form-control rounded-3 border-secondary-subtle" placeholder="Enter subject line" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Message Body</label>
                            <textarea name="message" class="form-control rounded-3 border-secondary-subtle" rows="12" placeholder="Write your message here..." required></textarea>
                            <div class="form-text text-muted">Note: Plain text is supported. Use HTML if you want to include links or images.</div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success fw-bold rounded-pill py-2 shadow-sm">
                                <i class="fas fa-paper-plane me-2"></i> Send Blast Now
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle Specific Email Input
    document.getElementById('recipient_type').addEventListener('change', function() {
        const emailContainer = document.getElementById('specific_email_container');
        if (this.value === 'specific') {
            emailContainer.classList.remove('d-none');
            emailContainer.querySelector('input').setAttribute('required', 'required');
        } else {
            emailContainer.classList.add('d-none');
            emailContainer.querySelector('input').removeAttribute('required');
        }
    });
</script>
@endsection