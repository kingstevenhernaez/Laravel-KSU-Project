@extends('layouts.alumni')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="card border-0 shadow-sm rounded-4 border-top border-success border-4">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                            <i class="fas fa-file-signature fa-2x"></i>
                        </div>
                        <h4 class="fw-bold text-dark">Request Official Document</h4>
                        <p class="text-muted">Fill out the form below to request documents from the registrar.</p>
                    </div>

                    <form action="{{ route('alumni.documents.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Document Type</label>
                            <select name="document_type" class="form-select form-select-lg bg-light" required>
                                <option value="">-- Select Document --</option>
                                <option value="Official Transcript of Records (OTR)">Official Transcript of Records (OTR)</option>
                                <option value="Diploma">Diploma</option>
                                <option value="Certificate of Good Moral Character">Certificate of Good Moral Character</option>
                                <option value="Honorable Dismissal">Honorable Dismissal</option>
                                <option value="Authentication/Certified True Copy">Authentication/Certified True Copy</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Number of Copies</label>
                            <input type="number" name="copies" class="form-control form-control-lg bg-light" min="1" max="10" value="1" required>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold">Purpose of Request</label>
                            <textarea name="purpose" class="form-control form-control-lg bg-light" rows="3" placeholder="e.g., Board Examination, Employment abroad, Transfer to another school..." required></textarea>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('alumni.documents.index') }}" class="btn btn-link text-muted fw-bold text-decoration-none px-4">Cancel</a>
                            <button type="submit" class="btn btn-success btn-lg fw-bold px-5 shadow-sm rounded-pill">
                                Submit Request <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection