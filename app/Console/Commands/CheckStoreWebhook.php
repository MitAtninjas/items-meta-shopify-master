<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

use App\Models\Store;
use App\Services\ShopifyApiService;
use App\Models\StoreWebhook;
use File;


class CheckStoreWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkStoreWebhook:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        try {
            $stores = Store::get();
            $showString = [];

            $csvFileName =  storage_path("webhook/". 'Webhook' . date('YmdHis') . '.csv');
            // $csvFileName = ;
            if (!File::exists(storage_path()."/webhook")) {
                File::makeDirectory(storage_path() . "/webhook");
            }
    
            $headers = array(
                'Content-Type' => 'text/csv'
            );
                  

            $handle = fopen($csvFileName, 'w');
            fputcsv($handle, ['Shop ID', 'Store Url', 'Address', 'Webhook ID', 'Webhook Topic', 'Status', 'Error Message']); // Add more headers as needed

            foreach ($stores as $key => $store) {
                // $showString[] = 'Store Url : ' . $store->store_url;

                // $webhookData = StoreWebhook::where(['store_id' => $store->id])->first();
                // $webhookId = json_decode($webhookData->response)->webhook->id;

                $shopifyService = new ShopifyApiService($store);
                $originalOrderResponse = $shopifyService->getResourceList('webhooks', 1);
                if($originalOrderResponse->successful()){
                    // dd($originalOrderResponse->json());
                    if(!empty($originalOrderResponse->json()) && !empty($originalOrderResponse->json()["webhooks"])){
                        $arrayResponse = $originalOrderResponse->json()["webhooks"];
                        $webhookDatas = StoreWebhook::where(['store_id' => $store->id])->get();
                        foreach ($webhookDatas as $key => $webhookData) {
                            $key = array_search((string) $webhookData->webhook_id, array_map('strval', array_column($arrayResponse, 'id')));
                            $showString=[];
                            $showString[] = $store->id;
                            $showString[] = $store->store_url;
                            $showString[] = $arrayResponse[$key]['address'] ?? '';
                            $showString[] = (string) $webhookData->webhook_id;
                            $showString[] = $webhookData->topic;
                            $arrayResponse[$key]['is_check_count'] = 1;
                            if($key ===  false){
                                $statusCheck = 'Remove'; // Shopify remove
                                foreach ($arrayResponse as $key => $value) {
                                    if($value['topic'] == $webhookData->topic){
                                        $statusCheck = 'Mismatch ID'; // Webhhook mismatch
                                        break;
                                    }
                                }
                                $showString[] = $statusCheck;

                            }else{
                                $showString[] = 'Active';
                            }
                            fputcsv($handle, $showString);
                        }
                        foreach ($arrayResponse as $key => $value) {
                            if(empty($value['is_check_count']) || $value['is_check_count'] == 0){
                                $showString=[];
                                $showString[] = $store->id;
                                $showString[] = $store->store_url;
                                $showString[] = $value['address'] ?? '';
                                $showString[] = (string) $value['id'];
                                $showString[] = $value['topic'];
                                $showString[] = 'Not In DB';
                                fputcsv($handle, $showString);
                            }
                        }
                    }

                }else{
                    $showString=[];
                    $showString[] = $store->id;
                    $showString[] = $store->store_url;
                    $showString[] = '';
                    $showString[] = '';
                    $showString[] = '';
                    $showString[] = 'Invalid';
                    $showString[] = $originalOrderResponse->json()['errors'];
                    fputcsv($handle, $showString);
                }
            }
            fclose($handle);

        } catch (Exception $e) {
            \Log::info('Check Webhook Error:' . $e->getMessage() . $e->getLine());
            return false;

        }
    }
}
