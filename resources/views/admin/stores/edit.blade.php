@extends('layouts.backend')

@section('title', 'Edit Store')

@push('css_before')
<link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
@endpush

@section('content')
<div class="block block-rounded">
    <div class="block-content block-content-full">
        <h2 class="content-heading">Store Information</h2>
        {!! Form::open(['method' => 'PATCH', 'route' => ['admin.stores.update', $store]]) !!}
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="row">
                    <div class="form-group col-sm-6">
                        {!! Form::label('customer_id', 'Customer') !!}
                        <select name="customer_id" class="form-control form-control-alt" id="customer_id">
                            <option></option>
                            @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}"
                                {{ ($customer && $customer->id === $store->customer_id) ? 'selected' : ''}}>
                                {{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <h2 class="content-heading pt-0">
                    <i class="fab fa-fw fa-shopify text-muted mr-1"></i> Shopify Details
                </h2>
                <div class="row push">
                    <div class="col-lg-4">
                        <p class="text-muted">Store & API Details</p>
                    </div>
                    <div class="col-lg-8 col-xl-5">
                        <div class="form-group">
                            {!! Form::label('store_url', 'Store URL', ['class' => 'required']) !!}
                            {!! Form::text('store_url', $store->store_url, ['placeholder' => 'shop.myshopify.com',
                            'class' =>
                            'form-control form-control-alt']); !!}
                        </div>
                        <div class="form-group  ">
                            {!! Form::label('store_type', 'Store Type', ['class' => 'required']) !!}
                            {!! Form::select('store_type', config('constants.store_type'), $store->store_type,
                            ['placeholder' =>
                            'Select Store Type', 'class' => 'form-control form-control-alt']); !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('app_name', 'Private App Name') !!}
                            {!! Form::email('app_name', $store->app_name, ['placeholder' => 'App Name', 'class' =>
                            'form-control
                            form-control-alt']); !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('api_key', 'API Key', ['class' => 'required']) !!}
                            {!! Form::text('api_key', $store->api_key, ['placeholder' => 'Api Key', 'class' =>
                            'form-control
                            form-control-alt']); !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('api_password', 'API Password', ['class' => 'required']) !!}
                            {!! Form::text('api_password', $store->api_password, ['placeholder' => 'Api Password',
                            'class' =>
                            'form-control form-control-alt']); !!}
                        </div>
                        @if(!$store->custom_app)
                        <div class="form-group">
                            {!! Form::label('shared_secret', 'Shared Secret', ['class' => 'required']) !!}
                            {!! Form::text('shared_secret', $store->shared_secret, ['placeholder' => 'Shared Secret',
                            'class' =>
                            'form-control form-control-alt']); !!}
                        </div>
                        @endif
                        <div class="form-group">
                            {!! Form::label('access_token', 'Access Token', ['class' => 'required']) !!}
                            {!! Form::text('access_token', $store->access_token, ['placeholder' => 'Access Token', 'class' =>
                            'form-control form-control-alt']); !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('api_version', 'API Version', ['class' => 'required']) !!}
                            {!! Form::text('api_version', $store->api_version, ['placeholder' => 'API Version',
                            'class' =>
                            'form-control form-control-alt']); !!}
                        </div>
                        <div class="form-group">
                            {!!
                            Form::label('location_id',
                            'ActiveAnts Inventory', ['class' =>
                            'col-sm-8 required']) !!}
                            <div class="form-group col-sm-12">
                                <select
                                    name="location_id"
                                    class="form-control form-control-alt"
                                    id="location_id"
                                    style="width: 100%;"
                                    data-placeholder="Select Active Ants Inventory Location">
                                    <option></option>
                                    @if (!empty($locationsList))
                                        @foreach ($locationsList as $location)
                                            <option value="{{ $location['id'] }}"
                                                {{ !empty($store->location_id) && $store->location_id == $location['id'] ? 'selected' : ''}}>
                                                {{ $location['name'] }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('enabled', 'Enable', ['class' => 'required']) !!}
                            <div class="form-group col-sm-8">
                                <div class="custom-control custom-switch mb-1">
                                    <input type="checkbox" class="custom-control-input"
                                           name="enabled"
                                           {{ (!empty($store->enabled) && $store->enabled == "1") ? 'checked' : '' }}
                                           id="enabled" value="1">
                                    <label class="custom-control-label"
                                           for="enabled"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('mixed_orders', 'Mixed Orders', ['class' => 'required']) !!}
                            <div class="form-group col-sm-8">
                                <div class="custom-control custom-switch mb-1">
                                    <input type="checkbox" class="custom-control-input"
                                           name="mixed_orders"
                                           {{ (!empty($store->mixed_orders) && $store->mixed_orders == "1") ? 'checked' : '' }}
                                           id="mixed_orders" value="1">
                                    <label class="custom-control-label"
                                           for="mixed_orders"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('fulfillment_edit_hook', 'Check Orders Update', ['class' => 'required']) !!}
                            <div class="form-group col-sm-8">
                                <div class="custom-control custom-switch mb-1">
                                    <input type="checkbox" class="custom-control-input"
                                           name="fulfillment_edit_hook"
                                           {{ (!empty($store->fulfillment_edit_hook) && $store->fulfillment_edit_hook == "1") ? 'checked' : '' }}
                                           id="fulfillment_edit_hook" value="1">
                                    <label class="custom-control-label"
                                           for="fulfillment_edit_hook"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('is_county_enable', 'Always Order From Specific Country', ['class' => 'required']) !!}
                            <div class="form-group col-sm-8">
                                <div class="custom-control custom-switch mb-1">
                                    <input type="checkbox" class="custom-control-input"
                                           name="is_county_enable"
                                           {{ (!empty($store->is_county_enable) && $store->is_county_enable == "1") ? 'checked' : '' }}
                                           id="is_county_enable" value='1'>
                                    <label class="custom-control-label"
                                           for="is_county_enable"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="country_list_div">
                            {!! Form::label('country_list_label', 'Country List', ['class' => 'required']) !!}
                            <div class="form-group col-sm-8">
                                <div class="custom-control custom-switch mb-1">
                                    <select id="country_list" class="form-control select2" name="country_id[]" multiple="multiple">
                                        @foreach ($countrys as $country)
                                            <option value="{{$country->id}}" 
                                                {{ !empty($store->country_id) && in_array($country->id, $store->country_id) ? 'selected' : '' }}
                                                >{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


            </div>
        </div>
        <div class="btn-group-horizontal btn-group">
            <button type="submit" class="btn btn-primary ajax-submit"><i class="fa fa-save mr-1"></i>Save</button>
            <a href="{{ route('admin.stores.index') }}" class="btn btn-outline-primary"><i
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

        $('#country_list').select2();
        checkCounRediobtn();
    })

    $('#is_county_enable').change(function() {
        checkCounRediobtn();
    });

    function checkCounRediobtn() {
        
        if( $('#is_county_enable').is(':checked') ){
            $('#country_list_div').show();
        }else{
            $('#country_list_div').hide();
        }
    }
</script>
@endpush
