<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Request Report - KSU</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #fff; color: #000; font-family: 'Arial', sans-serif; font-size: 12px; }
        .report-header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #0B3D2E; padding-bottom: 15px; }
        .table th { background-color: #f8f9fa !important; font-size: 11px; text-transform: uppercase; border-bottom: 2px solid #000; }
        .table td { border-bottom: 1px solid #ddd; }
        @media print {
            .no-print { display: none !important; }
            @page { margin: 1cm; size: landscape; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="container-fluid py-4">
        
        <div class="no-print text-end mb-3">
            <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> Print Now</button>
            <button onclick="window.close()" class="btn btn-secondary">Close</button>
        </div>

        <div class="report-header">
            <h3 class="fw-bold mb-1" style="color: #0B3D2E;">Kalinga State University (KSU)</h3>
            <h5 class="mb-2">Office of the University Registrar</h5>
            <h4 class="fw-bold mt-3">Targeted Document Request Report</h4>
            
            <p class="mb-0 mt-2 text-muted">
                <strong>Filters Applied:</strong> 
                Course: {{ request('course') ?: 'All' }} | 
                Batch: {{ request('batch') ?: 'All' }} | 
                Doc Type: {{ request('document_type') ?: 'All' }} | 
                Status: {{ request('status') ?: 'All' }}
            </p>
            <p class="mb-0 text-muted"><small>Generated on: {{ now()->format('F d, Y h:i A') }}</small></p>
        </div>

        <p class="fw-bold">Total Records: {{ count($requests) }}</p>

        <table class="table table-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tracking Code</th>
                    <th>Alumni Name</th>
                    <th>Student ID</th>
                    <th>Course</th>
                    <th>Batch</th>
                    <th>Document</th>
                    <th>Copies</th>
                    <th>Purpose</th>
                    <th>Date Requested</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $index => $req)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="fw-bold">{{ $req->tracking_code }}</td>
                    <td>{{ $req->user->last_name ?? '' }}, {{ $req->user->first_name ?? 'Unknown' }}</td>
                    <td>{{ $req->user->student_id ?? 'N/A' }}</td>
                    <td>{{ $req->user->course ?? 'N/A' }}</td>
                    <td>{{ $req->user->batch ?? 'N/A' }}</td>
                    <td>{{ $req->document_type }}</td>
                    <td class="text-center">{{ $req->copies }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($req->purpose, 30) }}</td>
                    <td>{{ $req->created_at->format('Y-m-d') }}</td>
                    <td><strong>{{ $req->status }}</strong></td>
                </tr>
                @empty
                <tr><td colspan="11" class="text-center py-4">No records found matching the criteria.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-5 pt-5 row text-center">
            <div class="col-4">
                <hr style="border-top: 1px solid #000; width: 80%; margin: 0 auto;">
                <p class="mt-2 mb-0">Prepared By</p>
                <p class="fw-bold">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
            </div>
            <div class="col-4"></div>
            <div class="col-4">
                <hr style="border-top: 1px solid #000; width: 80%; margin: 0 auto;">
                <p class="mt-2 mb-0">University Registrar</p>
            </div>
        </div>

    </div>
</body>
</html>