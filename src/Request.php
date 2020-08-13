<?php
/**
 * File RequestMaker.php
 * Created by HuuLe at 11/08/2020 16:48
 */

namespace HuuLe\AmazonSDK;

/**
 * Trait RequestMaker
 * @package HuuLe\AmazonSDK
 */
trait Request
{
    /**
     * Make Amazon MWS Request
     * @param array $parameters
     * @param callable $callback
     * @return object|void
     * @author HuuLe
     */
    function makeRequest($parameters = [])
    {
        $caller = debug_backtrace();
        if (!empty($caller[1]['function'])) {
            $requestName = ucfirst($caller[1]['function']);
            $requestClass = $this->getRequestClass($requestName);
            if (empty($parameters['Merchant']) && $this->getMerchant())
                $parameters['Merchant'] = $this->getMerchant();
            if ($this->getMWSAuthToken())
                $parameters['MWSAuthToken'] = $this->getMWSAuthToken();
            if (class_exists($requestClass))
                return new $requestClass($parameters);
        }
        return;
    }
}
