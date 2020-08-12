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
trait RequestMaker
{
    /**
     * Make Amazon MWS Request
     * @param array $parameters
     * @return mixed|null
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
            if (class_exists($requestClass)) {
                // Clear data for new request
                $this->setData('');
                return new $requestClass($parameters);
            }
        }
        return null;
    }
}
