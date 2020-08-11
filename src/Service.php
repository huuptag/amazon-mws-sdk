<?php
/**
 * File Service.php
 * Created by HuuLe at 11/08/2020 08:47
 */

namespace HuuLe\AmazonSDK;

/**
 * Interface Service
 * @package HuuLe\AmazonSDK
 */
interface Service
{
    /**
     * Generate Web Service Client
     * @return array|bool
     * @author HuuLe
     */
    public function client();

    /**
     * Make a Request to sent Amazon WMS
     * @param string $requestName
     * @param array $config
     * @return array|bool
     * @author HuuLe
     */
    public function makeRequest($requestName, ...$config);

    /**
     * Get Response from Amazon MWS
     * @param string $response
     * @return array|bool
     * @author HuuLe
     */
    public function getResponse($response);
}
