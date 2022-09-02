<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

use App\Models\Store;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Events\StoreCreated;
use App\Events\StoreDeleted;
use App\Events\StoreUpdated;

class StoreController extends Controller
{
    public $carrierService;

    /**
     * Carrier Service instance
     */
    public function __construct()
    {
        //
    }

    public function data(Request $request)
    {
        $stores = Store::where('customer_id', auth()->user()->id)->orderBy('id', 'desc')->get();

        return DataTables::of($stores)
            ->addIndexColumn()
            ->rawColumns(['store_url', 'app_name', 'actions'])
            ->editColumn('store_url', function ($stores) {
                return $stores->store_url;
            })
            ->editColumn('app_name', function ($stores) {
                return $stores->app_name;
            })
            ->editColumn('created_at', function ($stores) {
                return $stores->created_at->toDayDateTimeString();
            })
            ->addColumn('actions', function ($stores) {
                return view('customer.stores.actions', compact('stores'));
            })
            ->removeColumn('updated_at')
            ->make(true);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stores = Store::where('customer_id', auth()->user()->id)->get();
        return view('customer.stores.index', compact('stores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customer.stores.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = [
            'store_url' => ['required', 'regex:/[^.\s]+\.myshopify\.com/'],
            'store_type' => [
                'required',
                Rule::in(array_keys(config('constants.store_type')))
            ],
            'app_name' => 'required|string|max:255',
            'api_key' => 'required|string|max:255',
            'api_password' => 'required|string|max:255',
            'access_token' => 'required|string|max:255',

        ];

        $customMessages = [
            'regex' => 'The :attribute field should be valid shopify store url.'
        ];

        $this->validate($request, $rules, $customMessages);
        try {
            DB::beginTransaction();

            //parse store url
            $parsedUrl = parse_url($request->store_url);
            $request->merge([
                'store_url' => $parsedUrl['host'] ?? $parsedUrl['path'],
            ]);

            $store = Store::create($request->all() + ['customer_id' => auth()->user()->id]);

            $shippingMethods = config('constants.default_shipping_methods');

            if (!empty($shippingMethods)) {

                $shippingArr = [];
                foreach ($shippingMethods as $key => $value) {
                    $temp = [];
                    $temp['store_id'] = $store->id;
                    $temp['name'] = $key;
                    $temp['type'] = (in_array($key, config('constants.default_shipping_methods.standard'))) ?  "standard" : "service_points";
                    $temp['status'] = false;
                    $temp['created_at'] = Carbon::now();
                    $temp['updated_at'] = Carbon::now();
                    $shippingArr[] = $temp;
                }

                ShippingMethod::insert($shippingArr);
            }
            DB::commit();

            //event for store created
            event(new StoreCreated($store));

            return ['response' => 1, 'msg' => 'Store created successfully', 'redirect' => route('customer.stores.index')];
        } catch (\Throwable $e) {

            DB::rollback();
            $msg = config('app.env') === 'local'
                ? $e->getMessage()
                : 'Failed to create store';

            return ['response' => 2, 'msg' => $msg, 'redirect' => route('customer.stores.index')];
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
        return view('customer.stores.edit', compact('store'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store)
    {
        $rules = [
            'store_url' => ['required', 'regex:/[^.\s]+\.myshopify\.com/'],
            'store_type' => [
                'required',
                Rule::in(array_keys(config('constants.store_type')))
            ],
            'app_name' => 'required|string|max:255',
            'api_key' => 'required|string|max:255',
            'api_password' => 'required|string|max:255',
            'access_token' => 'required|string|max:255',

        ];

        $customMessages = [
            'regex' => 'The :attribute field should be valid shopify store url.'
        ];

        $this->validate($request, $rules, $customMessages);

        try {

            DB::beginTransaction();

            //parse store url
            $parsedUrl = parse_url($request->store_url);
            $request->merge([
                'store_url' => $parsedUrl['host'] ?? $parsedUrl['path'],
            ]);

            $store->update($request->all() + ['customer_id' => auth()->user()->id]);

            DB::commit();
            //only update wehook if store url has changed
            if ($store->wasChanged('store_url') || $store->wasChanged('api_key') || $store->wasChanged('api_password')) {

                $originalAttributes = [
                    'api_key' => $store->getOriginal('api_key'),
                    'api_password' => $store->getOriginal('api_password'),
                    'store_url' => $store->getOriginal('store_url')
                ];
                //event for store updated
                event(new StoreUpdated($store, $originalAttributes));
            }

            return ['response' => 1, 'msg' => 'Store updated successfully', 'redirect' => route('customer.stores.index')];
        } catch (\Throwable $e) {

            DB::rollback();

            $msg = config('app.env') === 'local'
                ? $e->getMessage()
                : 'Failed to update store.';

            return ['response' => 2, 'msg' => $msg, 'redirect' => route('customer.stores.index')];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        try {
            DB::beginTransaction();

            //store ref for event listner
            $storeRef = $store;

            //event for store deleted
            event(new StoreDeleted($storeRef));

            $store->delete();

            DB::commit();

            return ['response' => 1, 'msg' => 'Store deleted successfully.', 'redirect' => route('customer.stores.index')];
        } catch (\Throwable $e) {
            DB::rollback();

            $msg = config('app.env') === 'local'
                ? $e->getMessage()
                : 'Failed to delete store';

            return ['response' => 2, 'msg' => $msg, 'redirect' => route('customer.stores.index')];
        }
    }
}
