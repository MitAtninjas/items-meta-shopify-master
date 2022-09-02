<?php

namespace App\Services;

use App\Models\StoreWebhook;
use Illuminate\Support\Facades\Http;
use App\Services\ShopifyApiService;

class WebhookService
{
    private $store;
    private $originalStore;

    public function __construct($store, $originalStore)
    {
        $this->store = $store;
        $this->originalStore = $originalStore;
    }

    /**
     * Create a Shopify Webhook Subscription
     *
     * @param string $event - event that triggers webhook like orders/create
     * @param string $notifyUrl - notify url where webhook posts request
     * @param string $format - format of request. json/xml
     * @return void
     */
    public function createWebhook($event, $notifyUrl, $format = 'json')
    {
        $requestBody = json_encode([
            'webhook' => [
                'topic' => $event,
                'address' => $notifyUrl,
                'format' => $format
            ]
        ]);
        $shopifyApiService = new ShopifyApiService($this->store);
        $existingWebook = false;
        $existingWebhookResponse = $shopifyApiService->getResourceList('webhooks');


        if($existingWebhookResponse->successful()){
            \Log::info('Check Existing Webhook exists');
            $existingData  = $existingWebhookResponse->json();

            if(isset($existingData['webhooks']) && is_array($existingData['webhooks'])){
                \Log::info('Check Existing Webhook exists data'.json_encode($existingData));
                foreach ($existingData['webhooks'] as $webhookItem){
                    if($webhookItem['address'] == $notifyUrl){
                        $existingWebook = true;
                        $localCheck = StoreWebhook::where([
                                'store_id' => $this->store->id,
                                'webhook_id' => $webhookItem['id'],
                                'topic' => $event
                            ])->exists();


                        if(!$localCheck){
                            $storeWebhook = new StoreWebhook();
                            $storeWebhook->store_id = $this->store->id;
                            $storeWebhook->webhook_id = $webhookItem['id'];
                            $storeWebhook->topic = $event;
                            $storeWebhook->response = json_encode($webhookItem);
                            $storeWebhook->save();
                        }
                    }
                }

            }



        }



        if(!$existingWebook) {
            \Log::info('Not Existing Webhook ');
            $webhookResponse = $shopifyApiService->createResource('webhooks', $requestBody);
            $data = $webhookResponse->json();

            if ($webhookResponse->successful()) {

                //update store webhhok
                $storeWebhook = new StoreWebhook();
                $storeWebhook->store_id = $this->store->id;
                $storeWebhook->webhook_id = $data['webhook']['id'];
                $storeWebhook->topic = $event;
                $storeWebhook->response = json_encode($data);
                $storeWebhook->save();
            } else {
                if (!empty($data['errors'])) {
                    throw new \Exception(json_encode($data['errors']));
                }
            }
        }

    }
    /**
     * Update Webhook Attributes
     *
     * @param string $event - 'orders/create'
     * @param string $notifyUrl - notify url
     * @return void
     */
    public function updateWebhook($event, $notifyUrl)
    {
        $storeWebhook = StoreWebhook::where([
            ['topic', '=', $event],
            ['store_id', '=', $this->store->id]
        ])->first();

        if (!empty($storeWebhook)) {
            //delete old store webhook
            $shopifyApiService = new ShopifyApiService($this->originalStore);
            $deleteWebhookResponse = $shopifyApiService->deleteResourceById('webhooks', $storeWebhook->webhook_id);



            if ($deleteWebhookResponse) {
                //create new store webhook
                StoreWebhook::where([
                    ['topic', '=', $event],
                    ['store_id', '=', $this->store->id]
                ])->delete();

                $this->createWebhook($event, $notifyUrl);
            }
        } else {
            //create new store webhook
            $this->createWebhook($event, $notifyUrl);
        }
    }

    /**
     * Delete Webhook
     *
     * @param string $event - 'order/create'
     * @return void
     */
    public function deleteWebhook($event)
    {
        $storeWebhook = StoreWebhook::where([
            ['topic', '=', $event],
            ['store_id', '=', $this->store->id]
        ])->first();

        if (!empty($storeWebhook)) {
            $shopifyApiService = new ShopifyApiService($this->originalStore);
            return $shopifyApiService->deleteResourceById('webhooks', $storeWebhook->webhook_id);
        }
    }
}
