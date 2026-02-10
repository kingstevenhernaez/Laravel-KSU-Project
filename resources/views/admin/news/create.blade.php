@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary">Create News Post</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Headline / Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Cover Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <div class="form-text">Recommended size: 800x600px</div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Content</label>
                    <textarea name="details" class="form-control" rows="8" required></textarea>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-5 fw-bold">Publish Post</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection