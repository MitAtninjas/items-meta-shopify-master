@extends('layouts.backend')

@section('title', 'Create User')

@push('css_after')
<link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
@endpush

@section('content')
<div class="block block-rounded">
    <div class="block-content block-content-full">
        <h2 class="content-heading">Personal Information</h2>
        {!! Form::open(['method' => 'POST', 'route' => ['admin.users.store'], 'files' => true]) !!}
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="row">
                    <div class="form-group col">
                        {!! Form::label('name', 'Name', ['class' => 'required']) !!}
                        {!! Form::text('name', '', ['placeholder' => 'Ex: John Doe', 'class' => 'form-control
                        form-control-alt']); !!}
                    </div>
                    <div class="form-group col">
                        {!! Form::label('role', 'Role', ['class' => 'required']) !!}
                        {!! Form::select('role', config('constants.roles'), null, ['placeholder' => 'Select User Role',
                        'class' => 'form-control form-control-alt']); !!}
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">
                        {!! Form::label('email', 'Email', ['class' => 'required']) !!}
                        {!! Form::email('email', '', ['placeholder' => 'Ex: user@example.com', 'class' => 'form-control
                        form-control-alt']); !!}
                    </div>
                    <div class="form-group col">
                        {!! Form::label('phone_no', 'Phone no.') !!}
                        {!! Form::email('phone_no', '', ['placeholder' => 'Ex.9023145787', 'class' => 'form-control
                        form-control-alt']); !!}
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6 js-pw-strength-container">
                        {!! Form::label('password', 'Password', ['class' => 'required']) !!}
                        {!! Form::password('password', ['placeholder' => 'Enter Password', 'class' => 'form-control
                        form-control-alt js-pw-strength']); !!}
                        <div class="js-pw-strength-progress pw-strength-progress mt-1"></div>
                        <p class="js-pw-strength-feedback form-text font-size-sm mb-0"></p>
                    </div>

                    <div class="form-group col-sm-6 js-pw-strength-container">
                        {!! Form::label('password_confirmation', 'Confirm Password', ['class' => 'required col-12']) !!}
                        {!! Form::password('password_confirmation', ['placeholder' => 'Enter Confirm Password', 'class'
                        => 'form-control form-control-alt js-pw-strength']); !!}
                        <div class="js-pw-strength-progress pw-strength-progress mt-1"></div>
                        <p class="js-pw-strength-feedback form-text font-size-sm mb-0"></p>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('status', 'Status', ['class' => 'd-block']) !!}
                    @foreach (config('constants.user_status.status') as $key => $status)
                    <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                        {!! Form::radio('status', $key, ($key == config('constants.user_status.default_checked')),
                        ['class' => 'custom-control-input', 'id' => 'status_'.$key]); !!}
                        {!! Form::label('status_'.$key, $status, ['class' => 'custom-control-label']) !!}
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="btn-group-horizontal btn-group">
            <button type="submit" class="btn btn-primary ajax-submit"><i class="fa fa-save mr-1"></i>Save</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary"><i
                    class="fa fa-times mr-1"></i>Cancel</a>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection

@push('js_after')
<script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('js/plugins/pwstrength-bootstrap/pwstrength-bootstrap.min.js') }}"></script>

<script type="text/javascript">
    jQuery(document).ready(function(){
        Dashmix.helpers(['select2', 'pw-strength'])
    })
</script>
@endpush