<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $docTitle }} - KSU Alumni System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* 🟢 GOVERNMENT STANDARD: A4 PORTRAIT */
        @page { size: A4 portrait; margin: 15mm; }
        
        body { font-family: 'Times New Roman', Times, serif; color: #000; background: #fff; font-size: 10pt; }
        
        /* Letterhead & Logos */
        .letterhead { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #004d00; padding-bottom: 10px; margin-bottom: 20px; }
        .logo-container img { width: 80px; height: auto; } /* Smaller logos for portrait */
        .header-text { text-align: center; flex-grow: 1; padding: 0 10px; }
        .republic-text { font-size: 9pt; margin: 0; font-family: Arial, sans-serif; }
        .univ-name { font-size: 16pt; font-weight: 900; color: #004d00; margin: 2px 0; text-transform: uppercase; }
        .alumni-org { font-size: 11pt; font-weight: bold; margin: 0; text-transform: uppercase; color: #222; }
        .address-text { font-size: 8pt; margin: 0; font-family: Arial, sans-serif; }
        
        /* Titles & Table */
        .report-title { text-align: center; font-size: 14pt; font-weight: bold; text-transform: uppercase; text-decoration: underline; margin-bottom: 5px; }
        .report-subtitle { text-align: center; font-size: 10pt; font-style: italic; margin-bottom: 20px; }
        table.report-table { width: 100%; border-collapse: collapse; font-size: 9pt; margin-bottom: 30px; }
        table.report-table th { background-color: #e8ede8 !important; font-weight: bold; text-transform: uppercase; padding: 6px 4px; border: 1px solid #000; text-align: center; vertical-align: middle; }
        table.report-table td { padding: 4px; border: 1px solid #000; vertical-align: middle; }
        
        /* Signatory - Security Feature */
        .signature-section { margin-top: 40px; width: 100%; display: flex; justify-content: flex-end; page-break-inside: avoid; }
        .signature-box { width: 250px; text-align: left; }
        .signature-line { border-bottom: 1px solid #000; margin-bottom: 5px; height: 30px; margin-top: 30px; }
        .signature-name { font-weight: bold; text-transform: uppercase; font-size: 10pt; margin: 0; }
        .signature-title { font-size: 9pt; margin: 0; }

        @media print { .no-print { display: none !important; } body { -webkit-print-color-adjust: exact; } }
    </style>
</head>
<body>
    <div class="container-fluid py-2">
        <div class="d-flex justify-content-end mb-3 no-print">
            <button onclick="window.close()" class="btn btn-sm btn-secondary me-2">Close</button>
            <button onclick="window.print()" class="btn btn-sm btn-success fw-bold"><i class="fas fa-print"></i> Print Report</button>
        </div>

        <div class="letterhead">
            <div class="logo-container"><img src="{{ asset('assets/images/branding/ksu-logo.png') }}" alt="KSU Logo"></div>
            <div class="header-text">
                <p class="republic-text">Republic of the Philippines</p>
                <h1 class="univ-name">Kalinga State University</h1>
                <h2 class="alumni-org">Federated Alumni Association Inc.</h2>
                <p class="address-text">City of Tabuk, Kalinga</p>
            </div>
            {{-- 🟢 CORRECTED LOGO FILENAME: ksu-alumni-logo.png --}}
            <div class="logo-container"><img src="{{ asset('assets/images/branding/ksu-alumni-logo.png') }}" alt="Alumni Logo" onerror="this.style.display='none'"></div>
        </div>

        <div class="report-title">{{ $docTitle }}</div>
        <div class="report-subtitle">{{ $filterSubtitle }}<br>Date Generated: {{ date('F j, Y') }}</div>

        <table class="report-table">
            <thead>
                <tr>
                    <th width="3%">No.</th>
                    {{-- DYNAMIC HEADERS --}}
                    @if(in_array('id_number', $columns)) <th width="12%">ID Number</th> @endif
                    @if(in_array('fullname', $columns))  <th>Full Name</th> @endif
                    @if(in_array('course', $columns))    <th>Course</th> @endif
                    @if(in_array('batch', $columns))     <th width="8%">Batch</th> @endif
                    @if(in_array('email', $columns))     <th>Email Address</th> @endif
                    @if(in_array('emp_status', $columns))<th width="10%">Status</th> @endif
                    @if(in_array('company', $columns))   <th>Company</th> @endif
                    @if(in_array('job_title', $columns)) <th>Job Title</th> @endif
                </tr>
            </thead>
            <tbody>
                @forelse($alumni as $index => $alum)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    
                    {{-- DYNAMIC DATA CELLS (Must match header order) --}}
                    @if(in_array('id_number', $columns))
                        <td class="text-center">{{ $alum->student_id ?? ($alum->misRecord->student_id ?? 'N/A') }}</td>
                    @endif

                    @if(in_array('fullname', $columns))
                        <td><strong>{{ strtoupper($alum->last_name) }}</strong>, {{ $alum->first_name }}</td>
                    @endif

                    @if(in_array('course', $columns))
                        <td>{{ $alum->misRecord->course ?? ($alum->course ?? 'N/A') }}</td>
                    @endif

                    @if(in_array('batch', $columns))
                        <td class="text-center">{{ $alum->misRecord->year_graduated ?? ($alum->batch ?? 'N/A') }}</td>
                    @endif

                    @if(in_array('email', $columns))
                        <td>{{ $alum->email }}</td>
                    @endif

                    @if(in_array('emp_status', $columns))
                        <td class="text-center">{{ $alum->employment_status ?? 'Unspecified' }}</td>
                    @endif

                    @if(in_array('company', $columns))
                        <td>{{ $alum->company ?? '' }}</td>
                    @endif

                    @if(in_array('job_title', $columns))
                        <td>{{ $alum->job_title ?? '' }}</td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="{{ count($columns) + 1 }}" class="text-center p-3 fst-italic">No records found for these filters.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="signature-section">
            <div class="signature-box">
                <p class="mb-0">Prepared and Certified Correct by:</p>
                <div class="signature-line"></div>
                {{-- USES THE AUTHENTICATED USER'S NAME --}}
                <p class="signature-name">{{ strtoupper($signatory->first_name . ' ' . $signatory->last_name) }}</p>
                <p class="signature-title">System Administrator / OIC</p>
                <p class="signature-title" style="font-size: 8pt;">Date Signed: {{ date('m/d/Y') }}</p>
            </div>
        </div>
    </div>
    <script>window.onload = function() { window.print(); }</script>
</body>
</html>