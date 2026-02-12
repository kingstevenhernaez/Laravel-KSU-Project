@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <<a href="{{ route('admin.messages.create') }}" class="btn btn-primary">
            
            <i class="fas fa-plus"></i> Compose New
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('admin.messages.sent') }}" method="GET" class="row g-3 mb-4">
                <div class="col-md-10">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0" 
                               placeholder="Search by recipient email or subject..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark w-100">Search</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Recipient</th>
                            <th>Subject</th>
                            <th>Date Sent</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($history as $item)
                        <tr>
                            <td><strong>{{ $item->recipient_email }}</strong></td>
                            <td>{{ $item->subject }}</td>
                            <td>{{ $item->sent_at->format('M d, Y h:i A') }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-sm btn-info text-white" 
                                            data-bs-toggle="modal" data-bs-target="#viewEmail{{ $item->id }}">
                                        <i class="fas fa-eye"></i> View
                                    </button>

                                    <form action="{{ route('admin.emails.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this record?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>

                                <div class="modal fade text-start" id="viewEmail{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white">
                                                <h5 class="modal-title">Message Content</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>To:</strong> {{ $item->recipient_email }}</p>
                                                <p><strong>Subject:</strong> {{ $item->subject }}</p>
                                                <hr>
                                                <div class="p-3 bg-light border rounded">
                                                    {!! nl2br(e($item->message)) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No sent emails found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $history->links() }}
            </div>
        </div>
    </div>
</div>
@endsection