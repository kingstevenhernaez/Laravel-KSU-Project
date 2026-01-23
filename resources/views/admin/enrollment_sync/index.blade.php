@extends('layouts.app')

@section('content')
    <div class="p-30">
        <div class="row">
            <div class="col-lg-8">
                <div class="bg-white p-20 border radius-10">
                    <h4 class="mb-10">{{ __('Enrollment Sync') }}</h4>
                    <p class="mb-20">
                        {{ __('This tool imports records from the Enrollment API (mock) and upserts into the synced registry. Records are never deleted.') }}
                    </p>

                    <button id="ksu-sync-btn" type="button" class="zBtn zBtn-primary">
                        {{ __('Sync Now') }}
                    </button>

                    <div id="ksu-sync-msg" class="mt-15"></div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="bg-white p-20 border radius-10">
                    <h5 class="mb-10">{{ __('Last Sync Status') }}</h5>

                    @if($latestLog)
                        <ul class="list-unstyled mb-0">
                            <li><strong>{{ __('Started') }}:</strong> {{ optional($latestLog->started_at)->format('Y-m-d H:i:s') }}</li>
                            <li><strong>{{ __('Finished') }}:</strong> {{ optional($latestLog->finished_at)->format('Y-m-d H:i:s') }}</li>
                            <li><strong>{{ __('Inserted') }}:</strong> {{ $latestLog->inserted }}</li>
                            <li><strong>{{ __('Updated') }}:</strong> {{ $latestLog->updated }}</li>
                            <li><strong>{{ __('Failed') }}:</strong> {{ $latestLog->failed }}</li>
                        </ul>

                        @if($latestLog->error_summary)
                            <div class="alert alert-warning mt-15 mb-0">
                                <div class="fw-600 mb-5">{{ __('Error Summary (first lines)') }}</div>
                                <pre class="mb-0" style="white-space: pre-wrap;">{{ $latestLog->error_summary }}</pre>
                            </div>
                        @endif
                    @else
                        <p class="mb-0">{{ __('No sync has been run yet.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function () {
            const btn = document.getElementById('ksu-sync-btn');
            const msg = document.getElementById('ksu-sync-msg');

            if (!btn) return;

            const setMsg = (html) => {
                if (!msg) return;
                msg.innerHTML = html || '';
            };

            const setLoading = (loading) => {
                btn.disabled = loading;
                btn.textContent = loading ? 'Syncing...' : 'Sync Now';
            };

            btn.addEventListener('click', async function () {
                setMsg('');
                setLoading(true);

                try {
                    const res = await fetch(@json(route('admin.enrollment_sync.run')), {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': @json(csrf_token()),
                        },
                        body: JSON.stringify({})
                    });

                    const data = await res.json().catch(() => null);

                    if (!res.ok || !data) {
                        throw new Error((data && data.message) ? data.message : ('Request failed (HTTP ' + res.status + ')'));
                    }

                    // Toast if your theme provides toastr; fallback to inline message.
                    if (window.toastr && typeof window.toastr.success === 'function') {
                        window.toastr.success(data.message || 'Sync complete');
                    } else {
                        setMsg('<div class="alert alert-success">' + (data.message || 'Sync complete') + '</div>');
                    }

                    // Refresh the page to update the "Last Sync Status" box.
                    window.setTimeout(() => window.location.reload(), 600);
                } catch (e) {
                    const text = (e && e.message) ? e.message : 'Sync failed';
                    if (window.toastr && typeof window.toastr.error === 'function') {
                        window.toastr.error(text);
                    } else {
                        setMsg('<div class="alert alert-danger">' + text + '</div>');
                    }
                } finally {
                    setLoading(false);
                }
            });
        })();
    </script>
@endpush
