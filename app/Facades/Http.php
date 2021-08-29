<?php

namespace App\Facades;

class Http extends \Illuminate\Support\Facades\Http {

    /**
     * Adds client certificate and key to the request
     *
     * @return \Illuminate\Http\Client\PendingRequest
     */
    public static function certified(): \Illuminate\Http\Client\PendingRequest
    {
        return self::withOptions([
           'cert' => config('certificates.client.cert'),
           'ssl_key' => config('certificates.client.key'),
           'verify' => false
        ]);
    }
}
