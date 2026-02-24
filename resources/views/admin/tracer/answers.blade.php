@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    
    {{-- PAGE HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark">Survey Results</h2>
            <p class="text-muted mb-0">{{ $survey->title }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.tracer.index') }}" class="btn btn-outline-secondary fw-bold rounded-pill px-3">
                <i class="fas fa-arrow-left me-2"></i> Back to Surveys
            </a>
            <a href="{{ route('admin.tracer.export', $survey->id) }}" class="btn btn-success fw-bold rounded-pill px-4 shadow-sm">
                <i class="fas fa-file-csv me-2"></i> Export to CSV
            </a>
        </div>
    </div>

    {{-- STATS SUMMARY --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm border-start border-primary border-4 rounded-4">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold text-dark mb-0">{{ $responsesByUser->count() }}</h2>
                        <p class="text-muted small fw-bold text-uppercase mb-0">Total Submissions</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DYNAMIC DATA TABLE --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="white-space: nowrap;">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4">Alumni Name</th>
                            <th>Email</th>
                            <th>Batch</th>
                            
                            {{-- Loop through questions to create columns --}}
                            @foreach($survey->questions as $q)
                                <th>
                                    <span class="d-inline-block text-truncate" style="max-width: 200px;" title="{{ $q->question_text }}">
                                        {{ $q->question_text }}
                                    </span>
                                </th>
                            @endforeach
                            
                            <th class="pe-4">Date Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($responsesByUser as $userId => $userAnswers)
                            @php 
                                $user = $userAnswers->first()->user; 
                                // Map answers using question_id as the key for easy retrieval
                                $mappedAnswers = $userAnswers->pluck('answer_text', 'question_id');
                            @endphp
                            
                            @if($user)
                            <tr>
                                <td class="ps-4 fw-bold text-dark">{{ $user->first_name }} {{ $user->last_name }}</td>
                                <td class="text-muted">{{ $user->email }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $user->batch ?? 'N/A' }}</span></td>
                                
                                {{-- Loop through questions to display the mapped answers --}}
                                @foreach($survey->questions as $q)
                                    <td class="text-wrap text-muted" style="min-width: 200px; max-width: 300px;">
                                        {{ $mappedAnswers[$q->id] ?? '--' }}
                                    </td>
                                @endforeach
                                
                                <td class="pe-4 text-muted small">{{ $userAnswers->first()->created_at->format('M d, Y') }}</td>
                            </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="100%" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 text-light"></i>
                                    <p class="mb-0">No alumni have answered this survey yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</div>
@endsection