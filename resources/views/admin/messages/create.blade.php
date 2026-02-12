@extends('layouts.admin')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="d-flex justify-content-between align-items-center mt-4">
    <h2>Alumni Communication Center</h2>
    <a href="{{ route('admin.messages.sent') }}" class="btn btn-secondary">
        <i class="fas fa-history"></i> View Sent Box
    </a>
</div>

<div class="container-fluid px-4">
    <h2 class="mt-4">Alumni Communication Center</h2>
    <p>Send individual messages or a mass email blast to all registered alumni.</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.emails.send') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">Compose Email</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Subject</label>
                            <input type="text" name="subject" class="form-control" placeholder="Enter email subject" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Message Content</label>
                            <textarea name="message" class="form-control" rows="10" placeholder="Type your message here..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-paper-plane"></i> Send Emails
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white d-flex justify-content-between">
                        <span>Recipients</span>
                        <div>
                            <input type="checkbox" id="selectAll"> <small>Select All</small>
                        </div>
                    </div>
                    <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                        @php
                            // Pulling only users with the 'alumni' role
                            $alumni = \App\Models\User::where('role', 'alumni')->get();
                        @endphp

                        @foreach($alumni as $user)
                        <div class="form-check border-bottom py-2">
                            <input class="form-check-input alumni-checkbox" type="checkbox" name="recipients[]" value="{{ $user->email }}" id="user_{{ $user->id }}">
                            <label class="form-check-label" for="user_{{ $user->id }}">
                                <strong>{{ $user->first_name }} {{ $user->last_name }}</strong><br>
                                <span class="text-muted small">{{ $user->email }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById('selectAll').addEventListener('click', function() {
        let checkboxes = document.querySelectorAll('.alumni-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>
@endsection