<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Directory - Kalinga State University</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background-color: #f8f9fa; font-family: 'Montserrat', sans-serif; }
        
        .directory-header {
            background-color: #0B3D2E;
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
            border-bottom: 5px solid #FFC72C;
        }

        /* Clean Table Style */
        .table-custom {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            width: 100%;
        }
        .table-custom thead {
            background-color: #1a1a1a;
            color: white;
        }
        .table-custom th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            padding: 15px;
            border: none;
            letter-spacing: 0.5px;
        }
        .table-custom td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #eee;
            color: #333;
            font-size: 0.95rem;
        }
        .table-custom tr:last-child td {
            border-bottom: none;
        }
        .table-custom tr:hover {
            background-color: #f8f9fa;
        }
        
        .avatar-circle {
            width: 40px;
            height: 40px;
            background-color: #e9ecef;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            margin-right: 12px;
        }
        
        /* Pagination Fix for Bootstrap */
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }
        .page-item.active .page-link {
            background-color: #0B3D2E;
            border-color: #0B3D2E;
        }
        .page-link {
            color: #0B3D2E;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="directory-header text-center">
        <div class="container">
            <h3 class="fw-bold text-uppercase mb-3">Alumni Directory</h3>
            
            <div class="d-flex justify-content-center">
                <form action="{{ route('public.directory') }}" method="GET" class="d-flex w-100 shadow-sm rounded-pill bg-white p-1" style="max-width: 600px;">
                    <input type="text" name="search" class="form-control border-0 rounded-pill ps-4 shadow-none" 
                           placeholder="Search by Name, Course, or Batch..." value="{{ request('search') }}">
                    <button class="btn btn-warning rounded-pill px-4 fw-bold text-uppercase" type="submit" style="background-color: #FFC72C; border:none;">
                        Search
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Results --}}
    <div class="container pb-5">
        @if($alumni->count() > 0)
            
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th scope="col" width="45%">Alumni Name</th>
                            <th scope="col" width="35%">Course / Department</th>
                            <th scope="col" width="20%" class="text-center">Year Graduated</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alumni as $alum)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $alum->first_name }} {{ $alum->last_name }}</div>
                                        <small class="text-muted" style="font-size: 11px;">{{ $alum->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{ $alum->department->name ?? 'Course Not Set' }}
                            </td>
                            <td class="text-center">
                                @if($alum->batch)
                                    <span class="badge bg-warning text-dark">{{ $alum->batch }}</span>
                                @else
                                    <span class="text-muted small">N/A</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- 🟢 PAGINATION LINKS --}}
            <div class="mt-4">
                {{ $alumni->withQueryString()->links() }}
            </div>

            <div class="text-center mt-2 text-muted small">
                Showing {{ $alumni->firstItem() }} to {{ $alumni->lastItem() }} of {{ $alumni->total() }} alumni
            </div>

        @else
            {{-- No Results --}}
            <div class="text-center py-5 bg-white rounded shadow-sm">
                <i class="fas fa-search fa-3x text-muted opacity-25 mb-3"></i>
                <h4 class="fw-bold text-secondary">No matches found.</h4>
                <p class="text-muted">We couldn't find "<strong>{{ request('search') }}</strong>".</p>
                <a href="{{ route('public.directory') }}" class="btn btn-sm btn-outline-success mt-2">View Full List</a>
            </div>
        @endif
    </div>

    <footer class="text-center py-3 text-muted small border-top bg-white">
        &copy; {{ date('Y') }} Kalinga State University Alumni Association
    </footer>

</body>
</html>