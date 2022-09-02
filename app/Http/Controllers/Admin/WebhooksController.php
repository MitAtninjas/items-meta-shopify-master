<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Log;
use App\Services\OrderService;
use App\Services\ShopifyApiService;
use App\Events\OrderFulillmentCheck;

class WebhooksController extends Controller
{
    /**
     * @var \App\Services\OrderService
     */
    public $orderService;

    /**
     * This method serves webhook of order/create and order/update
     *
     * @return void
     */
    public function updateOrderMeta(Request $request)
    {
        try {
            if ($request->hasHeader('x-shopify-shop-domain')) {
                $storeUrl = $request->header('x-shopify-shop-domain');
                //\Log::info(json_encode($request->all()));
                $store = Store::where('store_url', $storeUrl)->first();

                $orderId = $request->id;
                $metaItems = [];
                $variants = [];
				$shopifyApiService = new ShopifyApiService($store);
                $sendMixed = true;
                $locations = [];

                $countryFilter = false;


                \Log::info('start process order '.$orderId.' StoreId: '.$storeUrl);

                $checkCountry = [
                    'brauzz.myshopify.com' => true
                ];

                $shopCountry = [
                    'brauzz.myshopify.com' => ['Belgium']
                ];

                if (isset($checkCountry[$storeUrl]) && $checkCountry[$storeUrl]) {
                    if (isset($shopCountry[$storeUrl]) && !empty($shopCountry[$storeUrl])) {
                        $countryFilter = true;
                    }

                }

                $sendCountryOrder = true;



                \Log::info('start process order'.$orderId);



				//FulfillmentOrder
				try{


                    if ($store->fulfillment_edit_hook) {
                        \Log::info('Skip Order crreate webhook for Order fulfillment - on hold '.$orderId.' Store '.$storeUrl);
                        return;
                    }

                    $fulfillmentOrderResponse = $shopifyApiService->getResourceById('orders',$orderId.'/fulfillment_orders');

					if($fulfillmentOrderResponse) {

						$fulfillmentOrders = $fulfillmentOrderResponse->json();
                        \Log::info('CheckFulfillmentOrders '.json_encode($fulfillmentOrders));
						foreach($fulfillmentOrders as $fulfillmentOrder){

							//ob_start();
							//var_dump($fulfillmentOrder);
							//$result = ob_get_clean();

							\Log::info(' Fulfillment Order.'.json_encode($fulfillmentOrder));
							//\Log::info(' Fulfillment Order.'.$fulfillmentOrder->assigned_location_id);
							if(isset($fulfillmentOrder[0]))
								$fulfillOrder = $fulfillmentOrder[0];
							else
								continue;





							foreach($fulfillmentOrder as $fulfillOrder)
							{
                                $locations[$fulfillOrder['assigned_location_id']] = $fulfillOrder['assigned_location_id'];
                                if($fulfillOrder['assigned_location_id'] == $store->location_id) {
                                    if ($store->fulfillment_edit_hook && $fulfillOrder['status'] == 'on_hold') {
                                        \Log::info('Order Create Order status is on Hold .'. $orderId.' Store: '.$storeUrl);
                                        //continue;
                                    }
									foreach($fulfillOrder['line_items'] as $lineItem)
									{
										$metaItems[] = [
											'lineItemId' => $lineItem['line_item_id'],
											'locationId' => $store->location_id,
											'quantity' => $lineItem['quantity']
										];
									}

                                    if (isset($fulfillOrder['destination'])) {
                                        \Log::info('COUNTRY ORDER VALUE is'.json_encode($fulfillOrder['destination']));
                                        if (isset($fulfillOrder['destination']['country'])) {
                                            $destinationCountry = $fulfillOrder['destination']['country'];
                                            \Log::info('COUNTRY ORDER VALUE is'.json_encode($destinationCountry));
                                        }
                                    } else {
                                        \Log::info('COUNTRY ORDER VALUE is DATA not found');
                                    }
                                    if ($countryFilter) {
                                        if (!empty($fulfillOrder['destination'])) {
                                            $destinationCountry = $fulfillOrder['destination']['country'];
                                            \Log::info('COUNTRY ORDER VALUE is'.json_encode($destinationCountry));
                                            $allowedCountries = $shopCountry[$storeUrl];

                                            if (!in_array($destinationCountry,$allowedCountries)) {
                                                $sendCountryOrder = false;
                                            }

                                        }

                                    }

									//break;
								}
							}

						}

                        //Check Mixed

                        if(!$store->mixed_orders)
                        {
                            \Log::info('STORE SET AS NO MIXED ORDER VALUE is'.json_encode([$store->mixed_orders]).' :'.$storeUrl);
                            \Log::info('STORE SET AS NO MIXED ORDER LOCATIONS is'.json_encode([$locations]).' :'.$storeUrl);
                            if(count($locations) > 1)
                                $sendMixed = false;


                        }

                        \Log::info('MIXED ORDER VALUE is'.json_encode([$sendMixed]));
                        \Log::info('COUNTRY ORDER VALUE is'.json_encode([$sendCountryOrder]));
						if(!empty($metaItems) && $sendMixed && $sendCountryOrder) {
							\Log::info('Meta Info Updating'.json_encode($metaItems));
							$metaField = [
								'metafield' => [
									'namespace' => 'activeants_line',
									'key' => 'line_item_locations_mapping',
									'value' => json_encode($metaItems),
									'type' => 'json'
								]
							];

							$this->orderService = new OrderService($store);
							$orderUpdated = $this->orderService->createOrderMeta($orderId, $metaField);
							\Log::info('Order Updated'.$orderId);
						}else {

							\Log::info('Order Not Updated. No Meta. Order'.$orderId);

						}



					}



				}catch(\Throwable $e) {

                        \Log::info('could not fetch fulfillment Order');
						\Log::info(json_encode($e->getMessage()));
						throw new \Exception($e->getMessage());
						//$locationList = null;

				}


				//InventoryIds
				/*

                $inventoryIdList = [];
                $inventoryIds = '';


                foreach($variants as $variant) {
                    \Log::info('Check Variants '.$orderId);
                    try{
                        $shopifyApiService = new ShopifyApiService($store);
                        $variantResponse = $shopifyApiService->getResourceById('variants',$variant['variant_id']);

                        if($variantResponse) {
                            $variantDetails = $variantResponse->json();
                            \Log::info('Check Variants Response'.$orderId);
                            if(!empty($variantDetails) && isset($variantDetails['variant'])){
                                \Log::info('Check Variants Response Details'.$orderId);
                                if(isset($variantDetails['variant']['inventory_item_id'])){
                                    \Log::info('Check Variants Response Details InventId'.$orderId);
                                    $inventoryIdList['i_'.$variantDetails['variant']['inventory_item_id']] = $variant['variant_id'];
                                    if(empty($inventoryIds))
                                        $inventoryIds = $variantDetails['variant']['inventory_item_id'];
                                    else
                                        $inventoryIds = $inventoryIds.','.$variantDetails['variant']['inventory_item_id'];
                                }





                            }

                        }

                    } catch(\Exception $e) {

                        $locationList = null;

                    }

                }


                if(!empty($inventoryIds)){

                    \Log::info('Check Inventory Ids'.$orderId.' INV'.json_encode($inventoryIds).' LOC'.json_encode($store->location_id));
                    $inventoryLevelResponse = $shopifyApiService->getResourceListParams('inventory_levels',[
                        'inventory_item_ids' => $inventoryIds,
                        'location_ids' => $store->location_id

                    ]);


                    if($inventoryLevelResponse) {
                        \Log::info('Check Inventory Ids Response'.$orderId);
                        $inventoryLevelDetails = $inventoryLevelResponse->json();

                        if($inventoryLevelDetails && isset($inventoryLevelDetails['inventory_levels'])){
                            \Log::info('Check Inventory Ids Response Details'.json_encode($inventoryLevelDetails['inventory_levels']));

                            foreach ($inventoryLevelDetails['inventory_levels'] as $responseInventory){
                                \Log::info('Check Inventory Ids Response DetailsLoop'.$orderId);
                                $itemId = $responseInventory['inventory_item_id'];

                                $variantId = $inventoryIdList['i_'.$itemId];

                                $metaItems[] = $variants['v_'. $variantId]['updateArray'];



                            }

                        }



                    }

                }

                //update order with shipping address and notes
                if(!empty($metaItems)) {
                    \Log::info('Meta Info Updating'.json_encode($metaItems));
                    $metaField = [
                        'metafield' => [
                            'namespace' => 'activeants_line',
                            'key' => 'line_item_locations_mapping',
                            'value' => json_encode($metaItems),
                            'type' => 'json'
                        ]
                    ];

                    $this->orderService = new OrderService($store);
                    $orderUpdated = $this->orderService->createOrderMeta($orderId, $metaField);
                    \Log::info('Order Updated'.$orderId);
                }
				*/
            }
        } catch (\Throwable $th) {
            Log::info(json_encode($th->getMessage()));
            throw new \Exception($th->getMessage());
        }

    }

    /**
     * This method serves webhook of orders/fulfillment_update
     *
     * @return void
     */

    public function updateOrderMetaFulfillment(Request $request)
    {
        try {
            if ($request->hasHeader('x-shopify-shop-domain')) {
                $storeUrl = $request->header('x-shopify-shop-domain');
                \Log::info('TestOrderFulfillment*******'.json_encode($request->all()));
                $store = Store::where('store_url', $storeUrl)->first();

                $orderId = $request->id;
                \Log::info('TestOrderFulfillment*******PREDISPATCH'.$orderId);
                $test = new OrderFulillmentCheck($store,$orderId,'create');
                //( $test->broadcastOn('channel-name'));
                \Log::info('TestOrderFulfillment*******POSTDISPATCH'.$orderId);
                \Log::info('TestOrderFulfillment*******New'.$orderId);
                //return;
                $metaItems = [];
                $variants = [];
                $shopifyApiService = new ShopifyApiService($store);
                $sendMixed = true;
                $locations = [];

                $countryFilter = false;


                \Log::info('start process order '.$orderId.' StoreId: '.$storeUrl);

                $checkCountry = [
                    'brauzz.myshopify.com' => true
                ];

                $shopCountry = [
                    'brauzz.myshopify.com' => ['Belgium']
                ];

                if (isset($checkCountry[$storeUrl]) && $checkCountry[$storeUrl]) {
                    if (isset($shopCountry[$storeUrl]) && !empty($shopCountry[$storeUrl])) {
                        $countryFilter = true;
                    }

                }

                $sendCountryOrder = true;



                \Log::info('start process order'.$orderId);

                //Check Fulfillment Order from Request:



                //FulfillmentOrder
                try{
                    //if ($store->fulfillment_edit_hook)
                        //sleep(5);
                    $fulfillmentOrderResponse = $shopifyApiService->getResourceById('orders',$orderId.'/fulfillment_orders');

                    if($fulfillmentOrderResponse) {

                        $fulfillmentOrders = $fulfillmentOrderResponse->json();
                        \Log::info('CheckFulfillmentOrders '.json_encode($fulfillmentOrders));
                        foreach($fulfillmentOrders as $fulfillmentOrder){
                            \Log::info(' Fulfillment Order.'.json_encode($fulfillmentOrder));
                            if(isset($fulfillmentOrder[0]))
                                $fulfillOrder = $fulfillmentOrder[0];
                            else
                                continue;

                            foreach($fulfillmentOrder as $fulfillOrder)
                            {
                                $locations[$fulfillOrder['assigned_location_id']] = $fulfillOrder['assigned_location_id'];
                                if($fulfillOrder['assigned_location_id'] == $store->location_id) {
                                    if ($store->fulfillment_edit_hook && $fulfillOrder['status'] == 'on_hold') {
                                        \Log::info('Order status is on Hold .'. $orderId.' Store: '.$storeUrl);
                                        continue;
                                    }
                                    foreach($fulfillOrder['line_items'] as $lineItem)
                                    {
                                        $metaItems[] = [
                                            'lineItemId' => $lineItem['line_item_id'],
                                            'locationId' => $store->location_id,
                                            'quantity' => $lineItem['quantity']
                                        ];
                                    }

                                    if (isset($fulfillOrder['destination'])) {
                                        \Log::info('COUNTRY ORDER VALUE is'.json_encode($fulfillOrder['destination']));
                                        if (isset($fulfillOrder['destination']['country'])) {
                                            $destinationCountry = $fulfillOrder['destination']['country'];
                                            \Log::info('COUNTRY ORDER VALUE is'.json_encode($destinationCountry));
                                        }
                                    } else {
                                        \Log::info('COUNTRY ORDER VALUE is DATA not found');
                                    }
                                    if ($countryFilter) {
                                        if (!empty($fulfillOrder['destination'])) {
                                            $destinationCountry = $fulfillOrder['destination']['country'];
                                            \Log::info('COUNTRY ORDER VALUE is'.json_encode($destinationCountry));
                                            $allowedCountries = $shopCountry[$storeUrl];

                                            if (!in_array($destinationCountry,$allowedCountries)) {
                                                $sendCountryOrder = false;
                                            }

                                        }
                                    }
                                }
                            }

                        }

                        //Check Mixed

                        if(!$store->mixed_orders)
                        {
                            \Log::info('STORE SET AS NO MIXED ORDER VALUE is'.json_encode([$store->mixed_orders]).' :'.$storeUrl);
                            \Log::info('STORE SET AS NO MIXED ORDER LOCATIONS is'.json_encode([$locations]).' :'.$storeUrl);
                            if(count($locations) > 1)
                                $sendMixed = false;


                        }

                        \Log::info('MIXED ORDER VALUE is'.json_encode([$sendMixed]));
                        \Log::info('COUNTRY ORDER VALUE is'.json_encode([$sendCountryOrder]));
                        $metaExists = false;
                        //Check if Meta Fields already exists
                        if (!empty($metaItems) && $store->fulfillment_edit_hook ) {
                            $this->orderService = new OrderService($store);
                            $existingMetasResponse = $this->orderService->getOrderMeta($orderId);
                            \Log::info('Existing Meta Response'.json_encode($existingMetasResponse));
                            $existingMetas = $existingMetasResponse['metafields'];
                            \Log::info('Existing Meta'.json_encode($existingMetas));
                            if(!empty($existingMetas)) {
                                \Log::info('Order meta already created.'. $orderId.' Store: '.$storeUrl);
                                return;
                            }



                        }
                        \Log::info('**order to update with meta'.$storeUrl);
                        //return;

                        //Create if not exists
                        if(!empty($metaItems) && $sendMixed && $sendCountryOrder && !$metaExists) {
                            \Log::info('Meta Info Updating'.json_encode($metaItems));
                            $metaField = [
                                'metafield' => [
                                    'namespace' => 'activeants_line',
                                    'key' => 'line_item_locations_mapping',
                                    'value' => json_encode($metaItems),
                                    'type' => 'json'
                                ]
                            ];

                            $this->orderService = new OrderService($store);
                            $orderUpdated = $this->orderService->createOrderMeta($orderId, $metaField);
                            \Log::info('Order Updated'.$orderId);
                        }else {

                            \Log::info('Order Not Updated. No Meta. Order'.$orderId);

                        }



                    }











                }catch(\Throwable $e) {

                    \Log::info('could not fetch fulfillment Order');
                    \Log::info(json_encode($e->getMessage()));
                    throw new \Exception($e->getMessage());
                    //$locationList = null;

                }



            }
        } catch (\Throwable $th) {
            Log::info(json_encode($th->getMessage()));
            throw new \Exception($th->getMessage());
        }
    }



}
