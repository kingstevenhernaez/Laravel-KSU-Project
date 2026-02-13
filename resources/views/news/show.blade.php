<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $newsItem->title }} - KSU Alumni</title>
    
    {{-- Fonts & Bootstrap --}}
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Montserrat', sans-serif; background-color: #f8f9fa; }
        .news-header { background-color: #0B3D2E; padding: 60px 0; color: white; margin-bottom: 40px; }
        .news-img-container { max-height: 500px; overflow: hidden; border-radius: 12px; margin-bottom: 30px; }
        .news-content { line-height: 1.8; font-size: 1.1rem; color: #333; }
        .meta-text { color: #FFC72C; font-weight: 600; text-transform: uppercase; font-size: 0.9rem; }
    </style>
</head>
<body>

    {{-- Navbar (Simplified) --}}
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #0B3D2E;">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="fas fa-arrow-left me-2"></i> BACK TO HOME
            </a>
        </div>
    </nav>

    {{-- Article Container --}}
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                {{-- Title Section --}}
                <div class="mb-4 text-center">
                    <span class="meta-text">
                        <i class="far fa-calendar-alt me-1"></i> {{ $newsItem->created_at->format('F d, Y') }}
                        <span class="mx-2">|</span>
                        <i class="far fa-user me-1"></i> {{ $newsItem->author ?? 'Admin' }}
                    </span>
                    <h1 class="fw-bold mt-3 display-4 text-dark">{{ $newsItem->title }}</h1>
                </div>

                {{-- Image --}}
                @if($newsItem->image)
                <div class="news-img-container shadow">
                    <img src="{{ asset('storage/' . $newsItem->image) }}" class="w-100" alt="{{ $newsItem->title }}">
                </div>
                @endif

                {{-- Content --}}
                <div class="bg-white p-5 shadow-sm rounded">
                    <div class="news-content">
                        {{-- Use this to render HTML styles correctly --}}
                        {!! $newsItem->content !!}
                    </div>
                </div>

                {{-- Share / Back Buttons --}}
                <div class="mt-5 text-center">
                    <p class="fw-bold">Share this update:</p>
                    <a href="#" class="btn btn-outline-primary rounded-circle me-2"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="btn btn-outline-info rounded-circle me-2"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="btn btn-outline-secondary rounded-circle"><i class="fas fa-link"></i></a>
                </div>

            </div>
        </div>
    </div>

</body>
</html>