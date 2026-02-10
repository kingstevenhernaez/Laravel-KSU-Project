@extends('layouts.alumni')

@section('content')
<div class="container py-5">
    
    {{-- Page Header --}}
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h2 class="fw-bold text-dark">Official Alumni Identification</h2>
            <p class="text-muted">Digital Proof of Membership • Kalinga State University</p>
        </div>
    </div>

    <div class="row justify-content-center g-5">
        
        {{-- FRONT SIDE --}}
        <div class="col-md-6 col-lg-5">
            <h5 class="text-center fw-bold text-muted mb-3">FRONT SIDE</h5>
            
            <div id="idCardFront" class="p-1 bg-white rounded">
                <div class="id-card-container position-relative overflow-hidden shadow-lg mx-auto">
                    
                    {{-- Design Elements --}}
                    <div class="id-bg-top"></div>
                    <div class="id-bg-bottom"></div>

                    {{-- Card Content --}}
                    <div class="position-relative z-index-1 text-center h-100 d-flex flex-column">
                        
                        {{-- HEADER --}}
                        <div class="pt-4 px-2">
                            <img src="{{ asset('images/ksu-alumni-logo.png') }}" 
                                 width="60" alt="KSU Alumni" 
                                 class="mb-2"
                                 onerror="this.src='{{ asset('assets/images/branding/ksu-logo.png') }}'"
                                 crossorigin="anonymous">
                            
                            <h6 class="fw-bold text-uppercase text-white mb-0" 
                                style="font-size: 11px; line-height: 1.3; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                                Kalinga State University<br>
                                Alumni Federated Association Inc.
                            </h6>
                        </div>

                        {{-- PHOTO & NAME --}}
                        <div class="mt-4 flex-grow-1 d-flex flex-column justify-content-center">
                            
                            {{-- 🟢 SQUARE PHOTO CONTAINER --}}
                            <div class="avatar-container mb-3">
                                @if($user->image)
                                    {{-- Changed rounded-circle to rounded-3 for square with soft corners --}}
                                    <img src="{{ asset($user->image) }}" class="rounded-3 border border-4 border-warning shadow" style="width: 140px; height: 140px; object-fit: cover;" crossorigin="anonymous">
                                @else
                                    {{-- Fallback placeholder is also square now --}}
                                    <div class="rounded-3 bg-white text-success d-flex justify-content-center align-items-center mx-auto border border-4 border-warning shadow" style="width: 140px; height: 140px; font-size: 50px; font-weight: bold;">
                                        {{ substr($user->first_name ?? 'A', 0, 1) }}
                                    </div>
                                @endif
                            </div>

                            {{-- 🟢 COMPLETE NAME (With Fallback) --}}
                            {{-- This automatically shows the logged-in user's name. --}}
                            {{-- If the name is missing in the DB, it shows 'Alumni Name' instead of being blank. --}}
                            <h4 class="fw-bold text-dark mb-0 text-uppercase px-2 text-truncate mx-auto" style="max-width: 90%; font-size: 18px;">
                                {{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'Alumni Name' }}
                            </h4>
                            
                            {{-- COURSE / DEPARTMENT --}}
                            <p class="text-success fw-bold mb-1 small text-uppercase px-3">
                                {{ $user->department->name ?? 'Alumni Member' }}
                            </p>
                            
                            <div class="mt-1">
                                <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold shadow-sm">
                                    BATCH {{ $user->batch ?? 'N/A' }}
                                </span>
                            </div>
                        </div>

                        {{-- FOOTER: UNIQUE ID NUMBER --}}
                        <div class="py-2 w-100" style="background: #f8f9fa; border-top: 1px solid #eee;">
                            <small class="text-muted d-block text-uppercase" style="font-size: 9px; letter-spacing: 1px;">ID Number</small>
                            <span class="fw-bold text-dark font-monospace" style="font-size: 14px; letter-spacing: 1px;">{{ $alumniIdNumber }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <button onclick="downloadId('idCardFront', 'KSU-ID-Front')" class="btn btn-primary w-100 mt-3 fw-bold shadow-sm">
                <i class="fas fa-download me-2"></i> Download Front
            </button>
        </div>

        {{-- BACK SIDE --}}
        <div class="col-md-6 col-lg-5">
            <h5 class="text-center fw-bold text-muted mb-3">BACK SIDE</h5>
            
            <div id="idCardBack" class="p-1 bg-white rounded">
                <div class="id-card-container position-relative overflow-hidden shadow-lg mx-auto" style="background: #fdfdfd;">
                    
                    <div class="id-logo-watermark"></div>

                    <div class="position-relative z-index-1 h-100 d-flex flex-column justify-content-between p-4 text-center">
                        
                        {{-- QR Code --}}
                        <div class="mt-4">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ route('public.directory') }}?search={{ $user->id }}" 
                                 width="100" class="border p-2 rounded bg-white shadow-sm" crossorigin="anonymous">
                            <p class="small text-muted mt-2" style="font-size: 10px;">Scan to Verify Membership</p>
                        </div>

                        {{-- Text --}}
                        <div class="px-2">
                            <p class="text-muted fst-italic mb-0" style="font-size: 9px; line-height: 1.5;">
                                This card certifies that the person whose name and picture appear hereon is a bonafide member of the <strong>KSU Alumni Federated Association Inc.</strong>
                            </p>
                            <p class="text-muted fst-italic mt-2 mb-0" style="font-size: 9px; line-height: 1.5;">
                                If found, please return to the Kalinga State University Alumni Center, Tabuk City, Kalinga.
                            </p>
                        </div>

                        {{-- Signatory --}}
                        <div class="mb-4">
                            <div class="mx-auto" style="width: 200px; border-bottom: 2px solid #333; margin-bottom: 5px;">
                                <span style="font-family: 'Brush Script MT', cursive; font-size: 24px; color: #0B3D2E;"> Lou Marshal m. Banggawan</span>
                            </div>
                            <h6 class="fw-bold text-dark text-uppercase mb-0" style="font-size: 12px;">Alumni Center Director</h6>
                            <small class="text-muted" style="font-size: 10px;">Signatory</small>
                        </div>
                        
                        <div style="height: 10px; background: #0B3D2E; width: 100%; position: absolute; bottom: 0; left: 0;"></div>
                    </div>
                </div>
            </div>

            <button onclick="downloadId('idCardBack', 'KSU-ID-Back')" class="btn btn-secondary w-100 mt-3 fw-bold shadow-sm">
                <i class="fas fa-download me-2"></i> Download Back
            </button>
        </div>

    </div>
</div>

{{-- Scripts & Styles --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    function downloadId(elementId, fileName) {
        const idCard = document.querySelector("#" + elementId + " .id-card-container");
        const btn = event.currentTarget;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';
        btn.disabled = true;
        html2canvas(idCard, { scale: 3, useCORS: true, backgroundColor: "#ffffff" }).then(canvas => {
            const link = document.createElement('a');
            link.download = fileName + '-{{ $user->id }}.png';
            link.href = canvas.toDataURL("image/png");
            link.click();
            btn.innerHTML = originalText;
            btn.disabled = false;
        }).catch(err => { console.error(err); alert("Error generating ID card."); btn.innerHTML = originalText; btn.disabled = false; });
    }
</script>
<style>
    .id-card-container { width: 100%; max-width: 350px; height: 550px; background: white; border-radius: 16px; position: relative; border: 1px solid #e0e0e0; }
    .id-bg-top { position: absolute; top: 0; left: 0; right: 0; height: 180px; background: #0B3D2E; border-radius: 0 0 40% 40% / 0 0 30px 30px; z-index: 0; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .id-bg-top::after { content: ''; position: absolute; bottom: -6px; left: 0; right: 0; height: 6px; background: #FFC72C; }
    .id-logo-watermark { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 250px; height: 250px; background: url('{{ asset("images/ksu-alumni-logo.png") }}') no-repeat center; background-size: contain; opacity: 0.04; z-index: 0; filter: grayscale(100%); }
</style>
@endsection