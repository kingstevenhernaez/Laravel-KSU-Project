{{-- COMPATIBILITY VIEW --}}
{{-- Some older layouts may still call: @include('layouts.style') --}}
{{-- This must NOT contain <head> tags. It is a head-partial only. --}}

<link rel="stylesheet" href="{{ asset('super_admin/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('super_admin/css/plugins.css') }}">
<link rel="stylesheet" href="{{ asset('super_admin/css/dataTables.css') }}">
<link rel="stylesheet" href="{{ asset('super_admin/css/dataTables.responsive.min.css') }}">
<link rel="stylesheet" href="{{ asset('super_admin/css/buttons.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('super_admin/css/summernote/summernote-lite.min.css') }}">
<link rel="stylesheet" href="{{ asset('super_admin/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('super_admin/css/responsive.css') }}">

@stack('style')
