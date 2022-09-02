<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ShopifyApiService;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;

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
        $stores = Store::orderBy('id', 'desc');

        if (!empty($request->customer)) {
            $stores = $stores->where('customer_id', $request->customer);
        }

        $customers = User::all();

        return DataTables::of($stores)
            ->addIndexColumn()
            ->rawColumns(['store_url', 'app_name', 'customer', 'actions'])
            ->editColumn('store_url', function ($stores) {
                return $stores->store_url;
            })
            ->editColumn('app_name', function ($stores) {
                return $stores->app_name;
            })
            ->addColumn('customer', function ($stores) use ($customers) {
                if (!empty($stores->customer_id)) {
                    $customer = $customers->find($stores->customer_id);
                    return $customer->name;
                }
                return '<i class="fa fa-minus"></i>';
            })
            ->editColumn('created_at', function ($stores) {
                return $stores->created_at->toDayDateTimeString();
            })
            ->addColumn('actions', function ($stores) {
                return view('admin.stores.actions', compact('stores'));
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
        $stores = Store::all();
        $customers = User::where('role', 'Customer')->get();
        return view('admin.stores.index', compact('stores', 'customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = User::where('role', 'Customer')->get();
        return view('admin.stores.create', compact('customers'));
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
            'customer_id' => 'nullable|exists:users,id',
            'store_url' => ['required', 'regex:/[^.\s]+\.myshopify\.com/'],
            'store_type' => [
                'required',
                Rule::in(array_keys(config('constants.store_type')))
            ],
            'app_name' => 'required|string|max:255',
            'api_key' => 'required|string|max:255',
            'api_password' => 'required|string|max:255',
            'access_token' => 'required|string|max:255',
            'api_version' => 'required|string|max:255',

        ];

        $customMessages = [
            'regex' => 'The :attribute field should be valid shopify store url.'
        ];

        if(empty($request->enabled))
            $request->request->add(['enabled' => false]);

        if(empty($request->mixed_orders))
            $request->request->add(['mixed_orders' => false]);

        $this->validate($request, $rules, $customMessages);

        try {
            DB::beginTransaction();

            //parse store url  & standard option & date settings
            $parsedUrl = parse_url($request->store_url);
            $request->merge([
                'store_url' => $parsedUrl['host'] ?? $parsedUrl['path'],

            ]);

            $store = Store::create($request->all());

           //event for store created
            event(new StoreCreated($store));

            DB::commit();

            return ['response' => 1, 'msg' => 'Store created successfully', 'redirect' => route('admin.stores.index')];
        } catch (\Throwable $e) {

            DB::rollback();
            $msg = config('app.env') === 'local'
                ? $e->getMessage()
                : 'Failed to create store';

            return ['response' => 2, 'msg' => $msg, 'redirect' => route('admin.stores.index')];
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
        $customers = User::where('role', 'Customer')->get();


        try{
            $shopifyApiService = new ShopifyApiService($store);
            $locationList = $shopifyApiService->getResourceList('locations');

        } catch(\Exception $e) {

            $locationList = null;

        }

        $locationsList = [];
        if($locationList) {
            $locationData = $locationList->json();

            if(!empty($locationData) && isset($locationData['locations'])){

                $store->location_data = json_encode($locationData);

                $store->save();

                $locationsList = $locationData['locations'];



            }

        }

        if(empty($locationsList) && $store->location_data  ) {

            $locationStores = json_decode($store->location_data,true);

            if(isset($locationStores['locations'])){

                $locationsList = $locationStores['locations'];
            }


        }

        return view('admin.stores.edit', compact('customers', 'store','locationsList'));
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
            'customer_id' => 'nullable|exists:users,id',
            'store_url' => ['required', 'regex:/[^.\s]+\.myshopify\.com/'],
            'store_type' => [
                'required',
                Rule::in(array_keys(config('constants.store_type')))
            ],
            'app_name' => 'required|string|max:255',
            'api_key' => 'required|string|max:255',
            'api_password' => 'required|string|max:255',
            'access_token' => 'required|string|max:255',
            'api_version' => 'required|string|max:255',
            'location_id' => 'required'


        ];

        $customMessages = [
            'regex' => 'The :attribute field should be valid shopify store url.'
        ];

        if(empty($request->mixed_orders))
            $request->request->add(['mixed_orders' => false]);

        if(empty($request->enabled))
            $request->request->add(['enabled' => false]);

        $this->validate($request, $rules, $customMessages);

        try {

            DB::beginTransaction();

            //parse store url
            $parsedUrl = parse_url($request->store_url);
            $request->merge([
                'store_url' => $parsedUrl['host'] ?? $parsedUrl['path'],
            ]);

            //replicate store before update
            $storeOriginal = $store->replicate();

            $store->update($request->all());



            //


            DB::commit();

            //event for store updated
            event(new StoreUpdated($store, $storeOriginal));

            return ['response' => 1, 'msg' => 'Store updated successfully', 'redirect' => route('admin.stores.index')];
        } catch (\Throwable $e) {

            DB::rollback();

            $msg = config('app.env') === 'local'
                ? $e->getMessage()
                : 'Failed to update store.';

            return ['response' => 2, 'msg' => $msg, 'redirect' => route('admin.stores.index')];
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

            return ['response' => 1, 'msg' => 'Store deleted successfully.', 'redirect' => route('admin.stores.index')];
        } catch (\Throwable $e) {
            DB::rollback();

            $msg = config('app.env') === 'local'
                ? $e->getMessage()
                : 'Failed to delete store';

            return ['response' => 2, 'msg' => $msg, 'redirect' => route('admin.stores.index')];
        }
    }
}
