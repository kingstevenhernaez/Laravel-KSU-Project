@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="fw-bold mb-0"><i class="fas fa-paper-plane text-success me-2"></i> Compose Email Blast</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.emails.send') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Recipient Type</label>
                            <select name="recipient_type" id="recipient_type" class="form-select rounded-3">
                                <option value="all">All Alumni</option>
                                <option value="specific">Specific Alumni</option>
                            </select>
                        </div>

                        <div class="mb-3 d-none" id="specific_email_group">
                            <label class="form-label fw-bold">Specific Email Address</label>
                            <input type="email" name="specific_email" class="form-control rounded-3" placeholder="Enter alumni email">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Subject</label>
                            <input type="text" name="subject" class="form-control rounded-3" required placeholder="Enter email subject">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Message Content</label>
                            <textarea name="message" class="form-control rounded-3" rows="10" required placeholder="Type your message here..."></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.emails.index') }}" class="btn btn-light rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-success rounded-pill px-5 fw-bold shadow-sm">Send Blast</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle the specific email input based on selection
    document.getElementById('recipient_type').addEventListener('change', function() {
        const specificGroup = document.getElementById('specific_email_group');
        specificGroup.classList.toggle('d-none', this.value !== 'specific');
    });
</script>
@endsection