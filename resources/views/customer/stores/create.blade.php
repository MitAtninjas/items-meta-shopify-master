@extends('layouts.backend')

@section('title', 'Create Store')

@push('css_before')
<link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
@endpush

@section('content')
<div class="block block-rounded">
    <div class="block-content block-content-full">
        <h2 class="content-heading">Store Information</h2>
        {!! Form::open(['method' => 'POST', 'route' => ['customer.stores.store']]) !!}
        <div class="row mb-3">
            <div class="col-md-12">
                <h2 class="content-heading pt-0">
                    <i class="fab fa-fw fa-shopify text-muted mr-1"></i> Shopify Details
                </h2>
                <div class="row push">
                    <div class="col-lg-4">
                        <p class="text-muted">Store & API Details</p>
                    </div>
                    <div class="col-lg-8 col-xl-5">
                        <div class="form-group">
                            {!! Form::label('store_url', 'Store Url', ['class' => 'required']) !!}
                            {!! Form::text('store_url', '', ['placeholder' => 'shop@myshopify.com', 'class' =>
                            'form-control
                            form-control-alt']); !!}
                        </div>
                        <div class="form-group  ">
                            {!! Form::label('store_type', 'Store Type', ['class' => 'required']) !!}
                            {!! Form::select('store_type', config('constants.store_type'), null, ['placeholder' =>
                            'Select Store Type', 'class' => 'form-control form-control-alt']); !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('app_name', 'Private App Name', ['class' => 'required']) !!}
                            {!! Form::email('app_name', '', ['placeholder' => 'App Name', 'class' => 'form-control
                            form-control-alt']); !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('api_key', 'API Key', ['class' => 'required']) !!}
                            {!! Form::text('api_key', '', ['placeholder' => 'Api Key', 'class' => 'form-control
                            form-control-alt']); !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('api_password', 'API Password', ['class' => 'required']) !!}
                            {!! Form::text('api_password', '', ['placeholder' => 'Api Password', 'class' =>
                            'form-control
                            form-control-alt']); !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('shared_secret', 'Shared Secret', ['class' => 'required']) !!}
                            {!! Form::text('shared_secret', '', ['placeholder' => 'Shared Secret', 'class' =>
                            'form-control
                            form-control-alt']); !!}
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="btn-group-horizontal btn-group">
            <button type="submit" class="btn btn-primary ajax-submit"><i class="fa fa-save mr-1"></i>Save</button>
            <a href="{{ route('customer.stores.index') }}" class="btn btn-outline-primary"><i
                    class="fa fa-times mr-1"></i>Cancel</a>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection

@push('js_after')
<script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
         jQuery('#customer_id').select2({ 
            placeholder: 'Select Customer'
        });
        jQuery('#togglePassword').click(function (e) {
            console.log("toggle password");
            // toggle the type attribute
            const type = jQuery('#password').attr('type') === 'password' ? 'text' : 'password';
            jQuery('#password').attr('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });

    })
</script>
@endpush
