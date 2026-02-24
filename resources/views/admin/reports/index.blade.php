@extends('layouts.admin')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
    .select2-container .select2-selection--single { height: 38px !important; border: 1px solid #ced4da !important; border-radius: 0.375rem !important; padding: 5px 10px; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 36px !important; }
    .column-checkbox-label { cursor: pointer; font-size: 0.9rem; }
</style>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 rounded-3 mt-4">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-success"><i class="fas fa-print me-2"></i> Custom Report Builder</h5>
                    <span class="badge bg-light text-dark border">Gov. Standard: A4 Portrait</span>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('admin.reports.print') }}" method="GET" target="_blank">
                        
                        <div class="row">
                            {{-- COLUMN 1: FILTERS --}}
                            <div class="col-md-6 pe-md-4 border-end">
                                <h6 class="fw-bold text-muted mb-3 small text-uppercase">Step 1: Filter Data</h6>
                                
                                {{-- Report Type --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">POPULATION TYPE</label>
                                    <select class="form-select border-success" name="report_type" required>
                                        <option value="general">General Masterlist (All Alumni)</option>
                                        <option value="employed_only">Graduates WITH Jobs Only</option>
                                        <option value="unemployed_only">Graduates WITHOUT Jobs Only</option>
                                    </select>
                                </div>

                                {{-- Course Filter --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">FILTER BY COURSE</label>
                                    <select class="form-select ajax-course" name="course" style="width: 100%;">
                                        <option value="all" selected>All Courses / Departments</option>
                                    </select>
                                </div>

                                {{-- Batch Range Filters --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">YEAR GRADUATED (RANGE)</label>
                                    <div class="d-flex gap-2">
                                        <select class="form-select" name="batch_from"><option value="all">From (Earliest)</option>@foreach($batches as $b)<option value="{{ $b }}">{{ $b }}</option>@endforeach</select>
                                        <span class="d-flex align-items-center text-muted small">to</span>
                                        <select class="form-select" name="batch_to"><option value="all">To (Latest)</option>@foreach($batches as $b)<option value="{{ $b }}">{{ $b }}</option>@endforeach</select>
                                    </div>
                                </div>
                            </div>

                            {{-- COLUMN 2: CUSTOM COLUMNS --}}
                            <div class="col-md-6 ps-md-4">
                                <h6 class="fw-bold text-muted mb-3 small text-uppercase">Step 2: Select Columns to Print</h6>
                                <p class="small text-muted mb-3">Check the boxes of the data fields you want to appear on the printed document.</p>
                                
                                <div class="row g-2">
                                    {{-- Identity Columns --}}
                                    <div class="col-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="columns[]" value="id_number" id="col_id"><label class="form-check-label column-checkbox-label" for="col_id">ID Number</label></div></div>
                                    <div class="col-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="columns[]" value="fullname" id="col_name" checked><label class="form-check-label column-checkbox-label" for="col_name">Full Name</label></div></div>
                                    
                                    {{-- Academic Columns --}}
                                    <div class="col-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="columns[]" value="course" id="col_course" checked><label class="form-check-label column-checkbox-label" for="col_course">Course/Program</label></div></div>
                                    <div class="col-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="columns[]" value="batch" id="col_batch" checked><label class="form-check-label column-checkbox-label" for="col_batch">Year Graduated</label></div></div>
                                    
                                    {{-- Contact Columns --}}
                                    <div class="col-12"><div class="form-check"><input class="form-check-input" type="checkbox" name="columns[]" value="email" id="col_email"><label class="form-check-label column-checkbox-label" for="col_email">Email Address</label></div></div>
                                    
                                    <hr class="text-muted my-2 opacity-25">

                                    {{-- Employment Columns --}}
                                    <div class="col-12"><div class="form-check"><input class="form-check-input" type="checkbox" name="columns[]" value="emp_status" id="col_emp"><label class="form-check-label column-checkbox-label" for="col_emp">Employment Status</label></div></div>
                                    <div class="col-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="columns[]" value="company" id="col_comp"><label class="form-check-label column-checkbox-label" for="col_comp">Company Name</label></div></div>
                                    <div class="col-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="columns[]" value="job_title" id="col_job"><label class="form-check-label column-checkbox-label" for="col_job">Job Title/Position</label></div></div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 fw-bold py-3 mt-4" style="background: #004d00; border: none;">
                            <i class="fas fa-print me-2"></i> Generate Custom A4 Report
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.ajax-course').select2({
            placeholder: "Type to search courses...", allowClear: true,
            ajax: { url: '{{ route("admin.reports.api.courses") }}', dataType: 'json', delay: 250,
                data: function (params) { return { q: params.term }; },
                processResults: function (data) { return { results: data.results }; }
            }
        });
    });
</script>
@endsection