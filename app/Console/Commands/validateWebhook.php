<?php

namespace App\Console\Commands;

use App\Models\Store;
use App\Services\ShopifyApiService;
use Illuminate\Console\Command;

class validateWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webhookvalidate:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validates if webhook exists';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $stores = Store::whereNotNull('customer_id')->get();
        $this->info('start'.PHP_EOL);
        foreach ($stores as $store) {
            $shopifyApiService = new ShopifyApiService($store);
            $webhooksresponse = $shopifyApiService->getResourceList('webhooks');

            $webhooksarray = json_encode($webhooksresponse->json());
            $this->info($store->store_url.PHP_EOL);
            $this->info($webhooksarray.PHP_EOL);


            //var_dump($ordersArray);
        }
    }
}
