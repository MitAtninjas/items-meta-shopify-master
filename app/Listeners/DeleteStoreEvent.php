<?php

namespace App\Listeners;

use App\Events\StoreDeleted;
use App\Models\Store;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\WebhookService;



class DeleteStoreEvent
{
    public $carrierService;
    public $webhookService;
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
     * @param  StoreDeleted  $event
     * @return void
     */
    public function handle(StoreDeleted $event)
    {
        $deletedStore = $event->store;


        $this->webhookService = new WebhookService(Store::class, $deletedStore);
        $this->webhookService->deleteWebhook('orders/create');


    }
}
