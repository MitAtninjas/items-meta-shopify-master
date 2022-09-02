@extends('layouts.backend')

@section('title', __('Dashboard'))

@push('css_after')
<link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
@endpush

@section('content')
<div class="block block-rounded">
    <div class="block-content text-center">
        <p>
            You're logged in!
        </p>
    </div>
</div>
@endsection

@push('js_after')
@endpush