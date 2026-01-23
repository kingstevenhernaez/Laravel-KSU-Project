@extends('layouts.app')

@push('title')
    {{ __('Tracer Surveys') }}
@endpush

@section('content')
    <div class="p-30">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-20">
            <h4 class="fs-20 fw-600">{{ __('Tracer Surveys') }}</h4>
            <a href="{{ route('admin.tracer_surveys.create') }}" class="btn btn-primary">{{ __('Create Survey') }}</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="text-end">{{ __('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($surveys as $s)
                            <tr>
                                <td>{{ $s->title }}</td>
                                <td>
                                    @if($s->is_published)
                                        <span class="badge bg-success">{{ __('Published') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('Draft') }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.tracer_surveys.edit', $s->id) }}">{{ __('Edit') }}</a>
                                    @if(!$s->is_published)
                                        <form class="d-inline" method="POST" action="{{ route('admin.tracer_surveys.publish', $s->id) }}">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-success" type="submit">{{ __('Publish') }}</button>
                                        </form>
                                    @else
                                        <form class="d-inline" method="POST" action="{{ route('admin.tracer_surveys.unpublish', $s->id) }}">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-warning" type="submit">{{ __('Unpublish') }}</button>
                                        </form>
                                    @endif
                                    <form class="d-inline" method="POST" action="{{ route('admin.tracer_surveys.delete', $s->id) }}" onsubmit="return confirm('{{ __('Delete this survey?') }}')">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-danger" type="submit">{{ __('Delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-muted">{{ __('No surveys yet.') }}</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $surveys->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
