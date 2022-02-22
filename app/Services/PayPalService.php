<?php

namespace App\Services;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

class PayPalService
{
    public static function client()
    {
        return new PayPalHttpClient(self::environment());
    }

    public static function environment()
    {
        $client_id = env("PAYPAL_CLIENT_ID");
        $client_secret = env("PAYPAL_CLIENT_SECRET");
        $environment = env("PAYPAL_ENVIRONMENT", 'development');

        if ($environment === 'production') {
            return new ProductionEnvironment($client_id, $client_secret);
        }

        return new SandboxEnvironment($client_id, $client_secret);
    }
}