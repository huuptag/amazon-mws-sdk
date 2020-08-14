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
     * @return object|void
     * @author HuuLe
     */
    function makeRequest($parameters = [])
    {
        $caller = debug_backtrace();
        if (!empty($caller[1]['function'])) {
            $requestName = ucfirst($caller[1]['function']);
            $requestClass = $this->getRequestClass($requestName);
            if (class_exists($requestClass)) {
                $requestObj = new $requestClass($parameters);
                if ($sellerID = $this->getSellerID()) {
                    if (method_exists($requestObj, 'setMerchant'))
                        $requestObj->setMerchant($sellerID);
                    if (method_exists($requestObj, 'setSellerId'))
                        $requestObj->setSellerId($sellerID);
                }
                if (($MWSAuthToken = $this->getMWSAuthToken()) && method_exists($requestObj, 'setMWSAuthToken'))
                    $requestObj->setMWSAuthToken($MWSAuthToken);
                if (($marketplaceID = $this->getMarketplaceID()) && method_exists($requestObj, 'setMarketplaceId'))
                    $requestObj->setMarketplaceId($marketplaceID);
                return $requestObj;
            }
        }
        return;
    }
}
