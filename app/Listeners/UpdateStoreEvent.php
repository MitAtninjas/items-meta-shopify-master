<?php

namespace App\Listeners;

use App\Events\StoreUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\WebhookService;


class UpdateStoreEvent
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


    }

    /**
     * Handle the event.
     *
     * @param  StoreUpdated  $event
     * @return void
     */
    public function handle(StoreUpdated $event)
    {

        $updatedStore = $event->store;


        //webhook service
        $this->webhookService = new WebhookService($updatedStore, $event->originalStore);
        $notifyUrl = config('app.url') . "/webhook/order-created";
        $this->webhookService->updateWebhook('orders/create', $notifyUrl);

        if ($updatedStore->fulfillment_edit_hook) {
            //dsd
            $editUrl = $notifyUrl = config('app.url') . "/webhook/orders-updated";
            $fulfillmentUrl = $notifyUrl = config('app.url') . "/webhook/fulfilment-updated";
            $this->webhookService->updateWebhook('orders/updated', $editUrl);
            $this->webhookService->updateWebhook('fulfillment_events/create', $fulfillmentUrl);


        }

    }
}
