@extends('layouts.backend')

@section('title', 'User Profile')

@push('css_after')
@endpush

@section('content')
<div class="block block-rounded">
    <div class="bg-image" style="background-image: url({{ asset('media/photos/photo17.jpg') }});">
        <div class="bg-black-25">
            <div class="content content-full">
                <div class="py-5 text-center">
                    <h1 class="font-w700 my-2 mb-2 text-white">{{ ucfirst($user->name) }} </h1>
                    <h3 class="text-white">({{ ucfirst(config('constants.roles')[$user->role]) }})</h3>
                    <a class="btn btn-hero-primary" href="{{ route('admin.users.edit', $user->id) }}">
                        <i class="fa fa-fw fa fa-pencil-alt mr-1"></i> Edit
                    </a>
                    <a class="btn btn-hero-dark" href="{{ route('admin.users.change-password', $user->id) }}">
                        <i class="fa fa-fw fa-user-lock mr-1"></i> Change Password 
                    </a>
                    <a class="btn btn-hero-secondary btn-delete" href="{{ route('admin.users.destroy', $user->id) }}">
                        <i class="fa fa-fw fa-trash mr-1"></i> Delete
                    </a>
                </div>
            </div>
        </div>
    </div>
    <br/>
    <div class="block-content block-content-full table-responsive">
        <div class="row">
            <div class="col-lg-12">
                <div class="block block-rounded block-bordered">
                    <div class="block-header border-bottom">
                        <h3 class="block-title">Personal Information</h3>
                    </div>
                    <div class="block-content">
                        <div class="font-size-h4 mb-1">{{ $user->name }} ({{ config('constants.roles')[$user->role] }})</div>
                        <dl>
                            <dt>Email :</dt>
                            <dd>{{ $user->email }}</dd>
                            <dt>Status :</dt>
                            <dd>{{ $user->status }}</dd>
                            <dt>Member Since :</dt>
                            <dd>{{ Carbon\Carbon::parse($user->created_at)->toDayDateTimeString() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js_after')
@endpush
