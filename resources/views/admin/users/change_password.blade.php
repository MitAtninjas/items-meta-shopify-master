@extends('layouts.backend')

@section('title', 'Change Password of user - '.$user->name)

@push('css_after')
@endpush

@section('content')
<div class="block block-rounded">
    <div class="block-content block-content-full">
        {!! Form::open(['method' => 'POST', 'route' => ['admin.users.change-password', $user->id], 'files' => true]) !!}
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group js-pw-strength-container row">
                        {!! Form::label('password', 'Password', ['class' => 'required col-12']) !!}
                        <div class="col-lg-12">
                            {!! Form::password('password', ['placeholder' => 'Enter Password', 'class' => 'form-control form-control-alt js-pw-strength']); !!}
                            <div class="js-pw-strength-progress pw-strength-progress mt-1"></div>
                        </div>
                        <div class="col-lg-12">
                            <p class="js-pw-strength-feedback form-text font-size-sm mb-0"></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group js-pw-strength-container row">
                        {!! Form::label('password_confirmation', 'Confirm Password', ['class' => 'required col-12']) !!}
                        <div class="col-lg-12">
                            {!! Form::password('password_confirmation', ['placeholder' => 'Enter Confirm Password', 'class' => 'form-control form-control-alt js-pw-strength']); !!}
                            <div class="js-pw-strength-progress pw-strength-progress mt-1"></div>
                        </div>
                        <div class="col-lg-12">
                            <p class="js-pw-strength-feedback form-text font-size-sm mb-0"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="btn-group-horizontal btn-group">
                <button type="submit" class="btn btn-primary ajax-submit"><i class="fa fa-save mr-1"></i>Save</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary"><i class="fa fa-times mr-1"></i>Cancel</a>
            </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection

@push('js_after')
<script src="{{ asset('js/plugins/pwstrength-bootstrap/pwstrength-bootstrap.min.js') }}"></script>

<script type="text/javascript">
    jQuery(document).ready(function(){
        Dashmix.helpers(['pw-strength'])
    })
</script>
@endpush
