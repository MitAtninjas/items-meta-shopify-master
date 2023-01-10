<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Store;
use App\Services\OrderService;
use App\Services\ShopifyApiService;



class Processorders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'processorders:get { webshop?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Order updates';

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
     * @return mixed
     */
    public function handle()
    {
        //
        set_time_limit(0);
        set_time_limit(0);
        date_default_timezone_set ('Europe/Amsterdam');
        $storeId = $this->argument('webshop');
        //\Log::info('Order Update '.$storeId);
        $store = Store::find($storeId);

		$shopifyApiService = new ShopifyApiService($store);
		
		$ordersResponse = $shopifyApiService->getResourceListParams('orders',['limit' => 70]);
		
		$ordersArray = $ordersResponse->json();
		//var_dump($ordersArray);
		//die();
		$orders = $ordersArray['orders'];
        



        try{
			
			foreach($orders as $order) {
				\Log::info($order['created_at']);
				$orderDate = strtotime($order['created_at']);
				$checkDate = strtotime('-2 day');
				//var_dump($order);
				\Log::info('order date'.date('Y-m-d',$orderDate));
				\Log::info('check date'.date('Y-m-d',$checkDate));
				\Log::info('**'.$orderDate);
				\Log::info('**'.$checkDate);
				
				if($orderDate <= $checkDate)
					continue;
				
				\Log::info('------');
				
				
				
				
				$orderId = $order['id'];
				\Log::info($orderId.'----');
				$existingMeta = $shopifyApiService->getResourceById('orders',$orderId.'/metafields');
				
				$metafieldsArray = $existingMeta->json();
				
				$metafields = $metafieldsArray['metafields'];
				if(!empty($metafields))
					continue;
				
				
				$fulfillmentOrderResponse = $shopifyApiService->getResourceById('orders',$orderId.'/fulfillment_orders');

				if($fulfillmentOrderResponse) {

					$fulfillmentOrders = $fulfillmentOrderResponse->json();

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


						if($fulfillOrder['assigned_location_id'] == $store->location_id) {

							foreach($fulfillOrder['line_items'] as $lineItem)
							{
								$metaItems[] = [
									'lineItemId' => $lineItem['line_item_id'],
									'locationId' => $store->location_id,
									'quantity' => $lineItem['quantity']
								];
							}

							break;
						}



					}



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
						\Log::info('***Order Updated'.$orderId.'****');
						\Log::info('Order Updated'.$orderId);
					}else {
						\Log::info('***Order Not Updated'.$orderId.'****');
						\Log::info('Order Not Updated. No Meta. Order'.$orderId);

					}



				}
				
				
				
			}
			
			// var_dump();
			// die();



        }catch(\Throwable $e) {

            \Log::info('could not fetch fulfillment Order');
            \Log::info(json_encode($e->getMessage()));
            throw new \Exception($e->getMessage());
            //$locationList = null;

        }

		\Log::info('done');
        \Log::info('Order Update Complete');





    }




}
