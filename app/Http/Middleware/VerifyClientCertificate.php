<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class VerifyClientCertificate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed | void
     */
    public function handle(Request $request, Closure $next)
    {

        $chain = $this->getChainCertificates();

        $cert = urldecode($request->server('HTTP_X_CLIENT_CERT'));

        foreach ($chain as &$issuer)
        {
            if (openssl_x509_verify($cert,$issuer) == 1)
            {
                return $next($request);
            }
        }

        abort(401);
    }

    private function getChainCertificates() : array {
        $chain = file_get_contents(Config::get('certificates.chain'));

        preg_match_all("~-+BEGIN CERTIFICATE-+\s[a-zA-Z0-9/+\s]+-+END CERTIFICATE-+\s~", $chain, $issuers);

        return $issuers[0] ?? array();
    }
}
