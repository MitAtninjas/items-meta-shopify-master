<?php

namespace App\Listeners;

use App\Events\OrderFulillmentCheck;
use App\Models\Store;
use App\Services\OrderService;
use App\Services\ShopifyApiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\WebhookService;
use Illuminate\Support\Facades\Log;


class FulfillmentCheckEvent
{

    public $webhookService;
    public $carrierService;
    public $shippingSetting;
    public $scriptTagService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param OrderFulillmentCheck $event
     * @return void
     */
    public function handle(OrderFulillmentCheck $event)
    {
        \Log::info('Start Event Order Check');
        $store = $event->store;
        $orderId = $event->order_id;
        $eventType = $event->eventType;
        //create webhook

        try {
            $storeUrl = $store->store_url;
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
                if ($store->fulfillment_edit_hook && $eventType == 'create')
                    sleep(60);
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
                    if(
                        !empty($metaItems)
                        && $sendMixed
                        && $sendCountryOrder
                        && !$metaExists
                    ) {
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
        } catch (\Throwable $th) {
            Log::info(json_encode($th->getMessage()));
            throw new \Exception($th->getMessage());
        }
    }
}

