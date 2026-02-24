@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    {{-- Page Header & Search --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">Email History Log</h2>
            <p class="text-muted mb-0">View and search all previously sent email blasts.</p>
        </div>
        
        <div class="d-flex gap-2">
            {{-- Search Bar --}}
            <form action="{{ route('admin.emails.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control rounded-pill rounded-end-0 border-end-0" placeholder="Search emails..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-outline-secondary rounded-pill rounded-start-0 border-start-0 border">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            
            {{-- New Blast Button (Optional: Link this to your email composer) --}}
            <a href="{{ route('admin.emails.create') }}" class="btn btn-success fw-bold shadow-sm rounded-pill px-4">
    <i class="fas fa-paper-plane me-2"></i> New Blast
</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- History Table --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4">Subject & Message</th>
                            <th>Recipient Email</th>
                            <th>Date Sent</th>
                            <th class="pe-4 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($history as $msg)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-dark d-block">{{ $msg->subject }}</span>
                                    <small class="text-muted">{{ \Illuminate\Support\Str::limit(strip_tags($msg->message), 60) }}</small>
                                </td>
                                <td class="text-muted">{{ $msg->recipient_email }}</td>
                                <td class="text-muted small">
                                    {{ \Carbon\Carbon::parse($msg->sent_at)->format('M d, Y') }} <br>
                                    <span class="text-secondary">{{ \Carbon\Carbon::parse($msg->sent_at)->format('h:i A') }}</span>
                                </td>
                                <td class="pe-4 text-end">
                                    {{-- View Full Message Button --}}
                                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#viewEmailModal{{ $msg->id }}">
                                        <i class="fas fa-eye me-1"></i> View
                                    </button>
                                </td>
                            </tr>

                            {{-- 🟢 View Modal for this specific message --}}
                            <div class="modal fade" id="viewEmailModal{{ $msg->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header bg-light border-bottom-0">
                                            <h5 class="modal-title fw-bold text-dark">{{ $msg->subject }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="mb-4 pb-3 border-bottom">
                                                <p class="mb-1"><strong class="text-dark">Sent to:</strong> <span class="text-muted">{{ $msg->recipient_email }}</span></p>
                                                <p class="mb-0"><strong class="text-dark">Date:</strong> <span class="text-muted">{{ \Carbon\Carbon::parse($msg->sent_at)->format('M d, Y h:i A') }}</span></p>
                                            </div>
                                            <div class="p-3 bg-light rounded border">
                                                {{-- Print the raw message allowing HTML --}}
                                                {!! $msg->message !!}
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top-0">
                                            <button type="button" class="btn btn-secondary fw-bold rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- End Modal --}}

                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="fas fa-envelope-open-text fa-3x mb-3 text-light"></i>
                                    
                                    @if(request()->has('search') && request('search') != '')
                                        <p class="mb-0">No results match your search for "<strong>{{ request('search') }}</strong>".</p>
                                        <a href="{{ route('admin.emails.index') }}" class="btn btn-link text-primary text-decoration-none mt-2">Clear search</a>
                                    @else
                                        <p class="mb-0">Your communication history will appear here once you send an email blast.</p>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    @if(isset($history) && $history->hasPages())
        <div class="mt-4 d-flex justify-content-end">
            {{ $history->links() }}
        </div>
    @endif

</div>
@endsection