@extends('frontend.layouts.app')

@push('title')
    {{ __('Alumni Directory') }}
@endpush

@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <style>
        /* 1. Hide the default Search Box (We use the header search) */
        div.dataTables_filter { display: none; }
        
        /* 2. Simple Table Styling */
        table.dataTable { margin-top: 0 !important; }
        thead th { background-color: #f8f9fa; font-weight: 600; }
        
        /* 3. Ensure no images inside the table accidentally get huge */
        table img { display: none !important; } 
    </style>
@endpush

@section('content')
    <section class="breadcrumb-area" data-background="{{ getFileUrl(getOption('banner_background_breadcrumb')) }}">
        <div class="container">
            <div class="breadcrumb-wrap text-center">
                <h2 class="title">{{ __('Public Alumni Directory') }}</h2>
            </div>
        </div>
    </section>

    <section class="pt-60 pb-60">
        <div class="container">
            
            <div class="row mb-3">
                <div class="col-lg-12 text-center">
                     <p class="text-muted">
                        <i class="fa-solid fa-magnifying-glass me-1"></i>
                        {{ __('Type a name in the top search bar to find alumni.') }}
                     </p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table id="alumni-list-table" class="table table-hover table-striped align-middle w-100 mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="40%" class="ps-4">{{ __('Full Name') }}</th>
                                            <th width="40%">{{ __('Course') }}</th>
                                            <th width="20%">{{ __('Year Graduated') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="ps-4">
                                        </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // 1. Catch the search query from the URL (e.g. ?q=rizvin)
            var urlParams = new URLSearchParams(window.location.search);
            var searchQuery = urlParams.get('q') || '';

            // 2. Initialize Table
            var table = $('#alumni-list-table').DataTable({
                processing: true,
                serverSide: true,
                searching: true, // Internal search must be ON
                
                // Pre-fill search from URL
                search: {
                    search: searchQuery
                },

                // Use Direct URL
                ajax: {
                    url: "{{ url('alumni/list-search-with-filter') }}", 
                },

                columns: [
                    {data: 'name', name: 'name', className: 'ps-4 fw-bold'}, // Added bold for Name
                    {data: 'address', name: 'departments.name'}, // Course
                    {data: 'action', name: 'passing_years.name', orderable: false, searchable: false} // Year
                ],

                language: {
                    paginate: {
                        previous: "<i class='fa-solid fa-angle-left'></i>",
                        next: "<i class='fa-solid fa-angle-right'></i>"
                    },
                    emptyTable: "{{ __('No alumni found matching your criteria.') }}",
                    zeroRecords: "{{ __('No alumni found.') }}"
                },
                dom: 'rtip' // Show only Table (t), Info (i), Pagination (p)
            });
        });
    </script>
@endpush