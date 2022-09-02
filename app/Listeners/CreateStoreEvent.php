<?php

namespace App\Listeners;

use App\Events\StoreCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\WebhookService;


class CreateStoreEvent
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
     * @param  StoreCreated  $event
     * @return void
     */
    public function handle(StoreCreated $event)
    {
        $createdStore = $event->store;


        //create webhook
        $this->webhookService = new WebhookService($createdStore, (object)[]);
        $notifyUrl = config('app.url') . "/webhook/order-created";
        $this->webhookService->createWebhook('orders/create', $notifyUrl);

        if ($createdStore->fulfillment_edit_hook) {
            //dsd
            $editUrl = $notifyUrl = config('app.url') . "/webhook/fulfilment-updated";
            $this->webhookService->createWebhook('fulfillments/update', $editUrl);
        }

    }
}
