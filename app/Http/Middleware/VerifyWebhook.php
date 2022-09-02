<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Store;

class VerifyWebhook
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $hmac = request()->header('x-shopify-hmac-sha256') ?: '';
        $storeUrl = request()->header('x-shopify-shop-domain');
        $data = request()->getContent();
        \Log::info($hmac);
        //get shopify store secret
        $storeDetails  = Store::where('store_url', $storeUrl)->first();

        if (!empty($storeDetails)) {

            if($storeDetails->custom_app)
            {
                $shopifyAppSecret = $storeDetails->api_password;
                \Log::info('Verify webhook Custom app');
            } else
            {
                \Log::info('Verify webhook Not Custom app');
                $shopifyAppSecret = $storeDetails->shared_secret;
            }

            // From https://help.shopify.com/api/getting-started/webhooks#verify-webhook
            $hmacLocal = base64_encode(hash_hmac('sha256', $data, $shopifyAppSecret, true));
            if (!hash_equals($hmac, $hmacLocal) || empty($storeUrl)) {
                // Issue with HMAC or missing store header
                abort(200, 'Invalid webhook signature');
            }
        } else {
            abort(200, 'Invalid store url');
        }

        return $next($request);
    }
}
