@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    {{-- Page Header & Search --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-0"><i class="fas fa-history text-success me-2"></i> Email History Log</h3>
            <p class="text-muted small mb-0">View and search all previously sent email blasts.</p>
        </div>
        
        <div class="d-flex gap-2 align-items-center">
            {{-- 🟢 Search Bar (Hooks into your Controller's search feature) --}}
            <form action="{{ route('admin.messages.sent') }}" method="GET" class="d-flex bg-white border rounded-pill overflow-hidden shadow-sm" style="max-width: 300px;">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0 shadow-none ps-3" placeholder="Search subject or email...">
                <button type="submit" class="btn btn-light border-0"><i class="fas fa-search text-muted"></i></button>
            </form>

            <a href="{{ route('admin.messages.create') }}" class="btn btn-success fw-bold shadow-sm rounded-pill px-4">
                <i class="fas fa-paper-plane me-2"></i> New Blast
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- History Table --}}
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <tr>
                            <th class="px-4 py-3">Subject & Message</th>
                            <th class="py-3">Recipient Email</th>
                            <th class="py-3 text-center">Date Sent</th>
                            <th class="py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        {{-- 🟢 Matches your controller's $history variable --}}
                        @forelse($history as $msg)
                        <tr>
                            <td class="px-4 py-3" style="max-width: 300px;">
                                <span class="fw-bold text-dark d-block text-truncate">{{ $msg->subject }}</span>
                                <small class="text-muted text-truncate d-block">
                                    {{ Str::limit(strip_tags($msg->message), 60) }}
                                </small>
                            </td>
                            
                            <td class="text-muted fw-medium">
                                <i class="fas fa-envelope text-success opacity-50 me-1"></i> {{ $msg->recipient_email }}
                            </td>
                            
                            <td class="text-center text-muted fw-medium" style="font-size: 0.85rem;">
                                {{ \Carbon\Carbon::parse($msg->sent_at)->format('M d, Y h:i A') }}
                            </td>
                            
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- View Full Message Button --}}
                                    <button type="button" class="btn btn-sm btn-outline-primary action-btn rounded-circle" data-bs-toggle="modal" data-bs-target="#viewModal{{ $msg->id }}" title="Read Email">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    {{-- Delete Log Button --}}
                                    <form action="{{ route('messages.destroy', $msg->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this log?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger action-btn rounded-circle" title="Delete Log">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>

                            {{-- 🟢 View Modal for this specific message --}}
                            <div class="modal fade" id="viewModal{{ $msg->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content border-0 shadow rounded-4">
                                        <div class="modal-header border-bottom bg-light pt-4 pb-3 px-4">
                                            <div>
                                                <h5 class="fw-bold text-dark mb-1">{{ $msg->subject }}</h5>
                                                <small class="text-muted"><i class="fas fa-to me-1"></i> Sent to: <strong>{{ $msg->recipient_email }}</strong> on {{ \Carbon\Carbon::parse($msg->sent_at)->format('M d, Y h:i A') }}</small>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4 text-dark" style="background: #fdfdfd; min-height: 200px;">
                                            {{-- Print the raw message (assuming it has HTML formatting from a WYSIWYG editor) --}}
                                            {!! $msg->message !!}
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-light fw-bold rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </tr>
                        
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <div class="mb-3">
                                    <i class="fas fa-envelope-open-text fa-3x text-light"></i>
                                </div>
                                <h5 class="fw-bold text-dark">No emails found</h5>
                                @if(request()->has('search') && request('search') != '')
                                    <p class="small">No results match your search for "{{ request('search') }}". <a href="{{ route('admin.messages.sent') }}">Clear search.</a></p>
                                @else
                                    <p class="small">Your communication history will appear here once you send an email blast.</p>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Pagination --}}
        @if($history->hasPages())
        <div class="card-footer bg-white py-3 border-0">
            {{ $history->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    .action-btn { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; padding: 0; transition: all 0.2s ease-in-out; }
    .action-btn i { font-size: 0.85rem; }
    .action-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.15); }
</style>
@endsection