@extends('frontend.layouts.app')

@push('title')
    {{ __('Public Alumni Directory') }}
@endpush

@section('content')
    <section class="pt-60 pb-110">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="text-center pb-25">
                        <h2 class="fs-36 fw-700 lh-46 text-black-color">{{ __('Public Alumni Directory') }}</h2>
                        <p class="fs-16 fw-400 lh-26 text-para-color mb-0">
                            {{ __('Search alumni records. This page shows only Name, Course/Program, and Year Graduated.') }}
                        </p>
                    </div>

                    <div class="bg-white bd-one bd-c-black-10 bd-ra-14 p-20 p-md-30">
                        <form method="GET" action="{{ route('public.alumni.directory') }}" class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center" style="gap:10px;">
                            <input
                                type="text"
                                name="q"
                                value="{{ $search ?? '' }}"
                                class="form-control"
                                placeholder="{{ __('Search by name, student number, or program...') }}"
                                style="height:44px; border-radius:10px;"
                            />
                            <button type="submit" class="btn" style="height:44px; border-radius:10px; background:#f4b400; color:#1b1c17; font-weight:700; white-space:nowrap;">
                                {{ __('Search') }}
                            </button>
                            @if(!empty($search))
                                <a href="{{ route('public.alumni.directory') }}" class="btn btn-outline-secondary" style="height:44px; border-radius:10px; white-space:nowrap;">
                                    {{ __('Clear') }}
                                </a>
                            @endif
                        </form>

                        <div class="table-responsive pt-20">
                            <table class="table align-middle">
                                <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Course/Program') }}</th>
                                    <th class="text-nowrap">{{ __('Year Graduated') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($alumni as $row)
                                    <tr>
                                        <td class="fw-600">
                                            {{ trim(implode(' ', array_filter([$row->first_name, $row->middle_name, $row->last_name]))) ?: '—' }}
                                        </td>
                                        <td>
                                            {{ $row->program_name ?: ($row->program_code ?: '—') }}
                                        </td>
                                        <td class="text-nowrap">
                                            {{ $row->graduation_year ?: '—' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-20">
                                            {{ __('No alumni records found.') }}
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="pt-10">
                            {{ $alumni->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
