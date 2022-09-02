<?php

namespace App\Services;

use App\Models\Store;
use App\Services\ShopifyApiService;


class OrderService
{
    private $store;

    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    /**
     * Create Carrier Service for store
     * @param String $orderId
     * @param Array $note_attributes
     *
     * @return bool
     */
    public function updateOrder($orderId, $note_attributes)
    {
        $requestBody = json_encode([
            'order' => [
                'id' => $orderId,
                "note_attributes" => $note_attributes
            ]
        ]);

        $shopifyApiService = new ShopifyApiService($this->store);
        $updateOrderResponse = $shopifyApiService->updateResource('orders', $orderId, $requestBody);

        if ($updateOrderResponse->successful()) {
            return true;
        } else {
            $data = $updateOrderResponse->json();
            if (!empty($data['errors'])) {
                throw new \Exception(json_encode($data['errors']));
            }
        }

        return false;
    }

    public function createOrderMeta($orderId, $metaData)
    {
        $requestBody = json_encode($metaData);

        $shopifyApiService = new ShopifyApiService($this->store);
        $updateOrderResponse = $shopifyApiService->createResourceMeta('orders', $orderId, $requestBody);

        if ($updateOrderResponse->successful()) {
            return true;
        } else {
            $data = $updateOrderResponse->json();
            if (!empty($data['errors'])) {
                throw new \Exception(json_encode($data['errors']));
            }
        }

        return false;
    }

    public function getOrderMeta($orderId)
    {
        $params = [
            'namespace' => 'activeants_line'
        ];
        $shopifyApiService = new ShopifyApiService($this->store);
        $getOrderResponse = $shopifyApiService->getResourceById('orders', $orderId.'/metafields',$params);



        if ($getOrderResponse->successful()) {
            return $getOrderResponse->json();
        } else {
            $data = $getOrderResponse->json();
            if (!empty($data['errors'])) {
                throw new \Exception(json_encode($data['errors']));
            }
        }

        return false;
    }
}
