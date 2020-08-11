<?php
/**
 * File RequestInterface.php
 * Created by HuuLe at 11/08/2020 11:49
 */

namespace HuuLe\AmazonSDK;

/**
 * Interface RequestInterface
 * @package HuuLe\AmazonSDK
 */
interface AmazonRequest
{
    /**
     * Make Request function
     * @param string $requestName
     * @param array $parameters
     * @return array|bool
     * @author HuuLe
     */
    public function makeRequest($requestName, $parameters);
}
