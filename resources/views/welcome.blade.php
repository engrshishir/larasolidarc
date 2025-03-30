@extends('layouts.app')

@section('title', 'Custom Page Title')

@push('layout-app-head')
    <meta name="description" content="This is a custom page description for SEO purposes.">
@endpush

@section('layout-app-content')
    <div class="container mx-auto p-4 bg-amber-500">
    </div>
@endsection

@push('layout-app-body-scripts')
    <script>
    </script>
@endpush
