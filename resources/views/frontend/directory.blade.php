@extends('layouts.app') 

@section('content')

{{-- MAIN CONTENT WRAPPER --}}
<div class="d-flex flex-column min-vh-100">

    {{-- SEARCH & RESULTS SECTION --}}
    <div class="py-5 bg-light flex-grow-1">
        <div class="container">
            
            {{-- Header --}}
            <div class="row justify-content-center mb-4">
                <div class="col-lg-10">
                    <h2 class="fw-bold text-dark mb-2">Alumni Directory Results</h2>
                    <p class="text-muted">
                        Found <strong>{{ $alumni->total() }}</strong> match(es) for your search.
                    </p>
                    
                    {{-- Search Bar --}}
                    <form action="{{ route('public.directory') }}" method="GET" class="mb-4">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Search again..." 
                                   value="{{ request('search') }}">
                            <button class="btn btn-warning fw-bold text-dark" type="submit" style="background-color: #FFC72C; border: none;">
                                Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- LIST VIEW (TABLE) --}}
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="py-3 ps-4">Alumni Name</th>
                                            <th class="py-3">Course / Department</th>
                                            <th class="py-3">Year Graduated</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($alumni as $alum)
                                        <tr>
                                            {{-- NAME COLUMN --}}
                                            <td class="ps-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    {{-- Avatar --}}
                                                    @if($alum->image)
                                                        <img src="{{ asset($alum->image) }}" class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle bg-success text-white d-flex justify-content-center align-items-center me-3 fw-bold" style="width: 40px; height: 40px; font-size: 16px;">
                                                            {{ substr($alum->first_name ?? $alum->name ?? 'A', 0, 1) }}
                                                        </div>
                                                    @endif
                                                    
                                                    {{-- Name Display --}}
                                                    <div>
                                                        <h6 class="mb-0 fw-bold text-dark">
                                                            {{ $alum->first_name ?? '' }} {{ $alum->last_name ?? '' }}
                                                            @if(empty($alum->first_name) && empty($alum->last_name))
                                                                {{ $alum->name ?? 'Unknown Name' }}
                                                            @endif
                                                        </h6>
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- COURSE COLUMN --}}
                                            <td class="text-muted">
                                                {{ $alum->department->name ?? 'Unknown Course' }}
                                            </td>

                                            {{-- BATCH COLUMN --}}
                                            <td>
                                                <span class="badge bg-light text-dark border">
                                                    Batch {{ $alum->batch ?? 'N/A' }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-5">
                                                <div class="opacity-50 mb-2">
                                                    <i class="fas fa-search fa-3x text-muted"></i>
                                                </div>
                                                <h5 class="text-muted">No alumni found.</h5>
                                                <p class="small text-muted mb-0">Try checking your spelling or search for a different name.</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        {{-- Pagination --}}
                        <div class="card-footer bg-white border-0 py-3">
                            {{ $alumni->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    {{-- 🟢 PROFESSIONAL FOOTER (Matches Homepage) --}}
    <footer style="background-color: #0B3D2E; color: white; border-top: 4px solid #FFC72C; padding-top: 60px;">
        <div class="container">
            <div class="row">
                
                {{-- Left Column: Logo & Info --}}
                <div class="col-md-5 mb-4">
                    <div class="d-flex align-items-center mb-3">
                        {{-- FIXED LOGO PATH --}}
                        <img src="{{ asset('images/ksu-logo.png') }}" 
                             alt="KSU Logo" 
                             width="50" 
                             class="me-3"
                             onerror="this.src='{{ asset('assets/images/branding/ksu-logo.png') }}'">
                        
                        <div>
                            <h6 class="fw-bold mb-0 text-uppercase" style="font-size: 14px; letter-spacing: 1px;">Kalinga State University</h6>
                            <small class="text-white-50" style="font-size: 12px;">Alumni Association</small>
                        </div>
                    </div>
                    <p class="small text-white-50" style="max-width: 300px; line-height: 1.6;">
                        Connecting graduates, fostering excellence, and building a legacy for the future of KSU.
                    </p>
                </div>

                {{-- Middle Column: Quick Links --}}
                <div class="col-md-3 mb-4">
                    <h6 class="fw-bold text-warning mb-3" style="color: #FFC72C !important;">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none small">Home</a></li>
                        <li class="mb-2"><a href="{{ route('login') }}" class="text-white-50 text-decoration-none small">Login</a></li>
                        <li class="mb-2"><a href="{{ route('register') }}" class="text-white-50 text-decoration-none small">Register</a></li>
                    </ul>
                </div>

                {{-- Right Column: Contact --}}
                <div class="col-md-4 mb-4">
                    <h6 class="fw-bold text-warning mb-3" style="color: #FFC72C !important;">Contact Us</h6>
                    <ul class="list-unstyled small text-white-50">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2" style="color: #FFC72C;"></i> Purok 6, Bulanao, Tabuk City, Kalinga</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2" style="color: #FFC72C;"></i> alumni@ksu.edu.ph</li>
                        <li class="mb-2"><i class="fas fa-phone me-2" style="color: #FFC72C;"></i> +63 912 345 6789</li>
                    </ul>
                </div>
            </div>

            <hr style="border-color: rgba(255,255,255,0.1); margin-top: 20px; margin-bottom: 20px;">
            
            <div class="text-center pb-4">
                <small class="text-white-50">&copy; {{ date('Y') }} Kalinga State University. All Rights Reserved.</small>
            </div>
        </div>
    </footer>

</div>
@endsection