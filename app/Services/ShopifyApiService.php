<?php

namespace App\Services;

use App\Models\Store;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;
use PhpParser\Node\Expr\Array_;

class ShopifyApiService
{
    /**
     * Base URL for Shopify Admin API
     *
     * @var string
     */
    private $baseUrl;

    /**
     * API Version of Shopify Admin API
     *
     * @var string
     */
    private $apiVersion;

    /**
     * Create Base URL with store details
     *
     * @param Store $store
     */

    /**
     * Base URL for Shopify Admin API
     *
     * @var string
     */
    private $customApp;

    /**
     * Base URL for Shopify Admin API
     *
     * @var Array_
     */
    private $headers;

    public function __construct(Store $store)
    {
        $this->apiVersion = config('services.shopify-api-version');
        if($store->custom_app)
        {
            \Log::info('Custom App '.$store->id);
            \Log::info('Custom App Token '.$store->access_token);
            $this->customApp = true;
            $this->headers = [
                'X-Shopify-Access-Token' => $store->access_token
            ];
            $this->baseUrl = 'https://' . $store->store_url . '/admin/api/' . $store->api_version;

        }else {
            \Log::info('Not a custom App'.$store->id);
            $this->customApp = false;
            $this->baseUrl = 'https://' . $store->api_key . ':' . $store->api_password . '@' . $store->store_url . '/admin/api/' . $store->api_version;
        }

    }

    /**
     * Return Response for Shopify API Call
     *
     * @param Response $response
     * @return mixed
     */
    public function sendResponse(Response $response, $getError = 0)
    {
        if ($response->successful() || $getError == 1) {
            return $response;
        } else {
            Log::info($response->throw()->json());
            // Throw an exception if a client or server error occurred...
            return $response->throw();
        }
    }

    /**
     * Create Shopify Resource
     *
     * @param string $resourceName
     * @param string $requestBody
     * @return object
     */
    public function createResource($resourceName, $requestBody)
    {
        $createResourceUrl = $this->baseUrl . "/" . $resourceName . ".json";

        if(!$this->customApp){
            \Log::info('create Resource not custom app');
            $createResourceResponse = Http::withBody(
                $requestBody,
                'application/json'
            )->post($createResourceUrl);
        } else
        {
            \Log::info('create Resource custom app');
            $createResourceResponse = Http::withBody(
                $requestBody,
                'application/json'
            )->withHeaders($this->headers)->post($createResourceUrl);
        }



        return $this->sendResponse($createResourceResponse);
    }

    /**
 * Update Shopify Resource
 *
 * @param string $resourceName
 * @param int $resourceId
 * @param string $requestBody
 * @return object
 */
    public function updateResource($resourceName, $resourceId, $requestBody)
    {
        $updateResourceUrl = $this->baseUrl . "/" . $resourceName . "/" . $resourceId . ".json";
        if(!$this->customApp){
            \Log::info('update Resource not custom app');
            $updateResourceResponse = Http::withBody(
                $requestBody,
                'application/json'
            )->put($updateResourceUrl);
        } else
        {
            \Log::info('update Resource custom app');
            $updateResourceResponse = Http::withBody(
                $requestBody,
                'application/json'
            )->withHeaders($this->headers)->put($updateResourceUrl);

        }



        return $this->sendResponse($updateResourceResponse);
    }

    /**
     * Create Shopify Metafield
     *
     * @param string $resourceName
     * @param int $resourceId
     * @param string $requestBody
     * @return object
     */
    public function createResourceMeta($resourceName, $resourceId, $requestBody)
    {
        $updateResourceUrl = $this->baseUrl . "/" . $resourceName . "/" . $resourceId . "/metafields.json";

        if(!$this->customApp){
            \Log::info('create Resource not custom app meta');
            $updateResourceResponse = Http::withBody(
                $requestBody,
                'application/json'
            )->post($updateResourceUrl);
        } else
        {
            \Log::info('create Resource custom app meta');
            $updateResourceResponse = Http::withBody(
                $requestBody,
                'application/json'
            )->withHeaders($this->headers)->post($updateResourceUrl);
        }



        return $this->sendResponse($updateResourceResponse);
    }

    /**
     * Get Resource List
     *
     * @param string $resourceName
     * @return object
     */
    public function getResourceList($resourceName, $getError = 0)
    {
        $getResourceUrl = $this->baseUrl . "/" . $resourceName . ".json";

        if(!$this->customApp){
            \Log::info('get Resource not custom app');
            $getResourceResponse = Http::get($getResourceUrl);
        }
        else{
            \Log::info('get Resource URL custom app new'.$getResourceUrl.' Header: '.json_encode($this->headers));
            $getResourceResponse = Http::withHeaders($this->headers)->get($getResourceUrl);

        }

        return $this->sendResponse($getResourceResponse, $getError);
    }

    /**
     * Get Resource List with Query Params
     *
     * @param string $resourceName
     * @return object
     */
    public function getResourceListParams($resourceName, $query)
    {
        $getResourceUrl = $this->baseUrl . "/" . $resourceName . ".json";

        if(!$this->customApp){
            \Log::info('get Resource not custom app List');
            $getResourceResponse = Http::get($getResourceUrl,$query);
        }
        else{
            \Log::info('get Resource URL custom app new List');
            $getResourceResponse = Http::withHeaders($this->headers)->get($getResourceUrl,$query);

        }


        return $this->sendResponse($getResourceResponse);
    }
    /**
     * Get Resource By Id
     *
     * @param string $resourceName
     * @param int $resourceId
     * @return object
     */
    public function getResourceById($resourceName, $resourceId, $params = null)
    {
        $getResourceByIdUrl = $this->baseUrl . "/" . $resourceName . "/" . $resourceId . ".json";

        if(!$this->customApp)
        {
            \Log::info('get Resource not custom app Id');
            $getResourceByIdResponse = Http::get($getResourceByIdUrl, $params);
        } else
        {
            \Log::info('get Resource  custom app Id');
            $getResourceByIdResponse = Http::withHeaders($this->headers)->get($getResourceByIdUrl,$params);

        }


        return $this->sendResponse($getResourceByIdResponse);
    }



    /**
     * Delete Resource By Id
     *
     * @param string $resourceName
     * @param int $resourceId
     * @return void
     */
    public function deleteResourceById($resourceName, $resourceId)
    {
        $deleteResourceByIdUrl = $this->baseUrl . "/" . $resourceName . "/" . $resourceId . ".json";

        if(!$this->customApp)
        {
            \Log::info('delete Resource  not custom app Id');
            $deleteResourceByIdResponse = Http::delete($deleteResourceByIdUrl);
        } else {
            \Log::info('delete Resource  custom app Id');
            $deleteResourceByIdResponse = Http::withHeaders($this->headers)->delete($deleteResourceByIdUrl);
        }


        if ($deleteResourceByIdResponse->ok()) {
            return true;
        } else {
            // Throw an exception if a client or server error occurred...
            return false;
        }
    }
}
