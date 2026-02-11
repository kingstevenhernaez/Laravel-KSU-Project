@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h2 class="mt-4">Communication Center</h2>
    
    <form action="{{ route('admin.emails.send') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Subject</label>
                            <input type="text" name="subject" class="form-control" placeholder="Enter subject line..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Message Content</label>
                            <textarea name="message" class="form-control" rows="10" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary px-5">Send Message</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light fw-bold">Recipients (Alumni)</div>
                    <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                        <div class="mb-2 border-bottom pb-2">
                            <input type="checkbox" id="selectAll"> <label for="selectAll" class="fw-bold">Select All Alumni</label>
                        </div>
                        @foreach($alumni as $user)
                        <div class="mb-1">
                            <input type="checkbox" name="recipients[]" value="{{ $user->email }}" class="alumni-checkbox">
                            <label>{{ $user->first_name }} {{ $user->last_name }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById('selectAll').onclick = function() {
        let checkboxes = document.querySelectorAll('.alumni-checkbox');
        for (let checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    }
</script>
@endsection