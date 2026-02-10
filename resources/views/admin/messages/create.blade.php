@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-success"><i class="fas fa-paper-plane me-2"></i>Send Email Blast</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.messages.send') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Send To:</label>
                    <select name="recipient_type" class="form-select" id="recipient_type" onchange="toggleSpecific()">
                        <option value="all">All Alumni</option>
                        <option value="individual">Specific Individual (Test)</option>
                    </select>
                </div>

                <div class="mb-3 d-none" id="specific_email_div">
                    <label class="form-label">Enter Email Address</label>
                    <input type="email" name="specific_email" class="form-control" placeholder="user@example.com">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Subject</label>
                    <input type="text" name="subject" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Message Content</label>
                    <textarea name="message" class="form-control" rows="8" required></textarea>
                </div>

                <button type="submit" class="btn btn-success px-5 fw-bold">
                    <i class="fas fa-envelope me-2"></i> Send Message
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleSpecific() {
        var type = document.getElementById('recipient_type').value;
        var div = document.getElementById('specific_email_div');
        if(type === 'individual') {
            div.classList.remove('d-none');
        } else {
            div.classList.add('d-none');
        }
    }
</script>
@endsection