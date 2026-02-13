@extends('layouts.admin')

@section('content')
{{-- 1. Add Summernote CSS (The Editor Style) --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create News Post</h1>
        <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group mb-3">
                    <label for="title" class="form-label fw-bold">News Headline</label>
                    <input type="text" name="title" class="form-control" required placeholder="Enter headline...">
                </div>

                <div class="form-group mb-3">
                    <label for="image" class="form-label fw-bold">Cover Image (Optional)</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>

                {{-- The Content Area --}}
                <div class="form-group mb-3">
                    <label for="content" class="form-label fw-bold">Content</label>
                    {{-- ID="summernote" is what the script looks for --}}
                    <textarea id="summernote" name="content" required></textarea>
                </div>

                <button type="submit" class="btn btn-success mt-3 px-4">
                    <i class="fas fa-paper-plane me-1"></i> Publish News
                </button>
            </form>
        </div>
    </div>
</div>

{{-- 2. Add Summernote JS (The Editor Logic) --}}
{{-- Note: We need jQuery for Summernote to work --}}
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            placeholder: 'Write your news here... You can change fonts, colors, and add images!',
            tabsize: 2,
            height: 300, // Height of the editor
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>
@endsection