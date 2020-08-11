<?php
/**
 * File AmazonResponse.php
 * Created by HuuLe at 11/08/2020 12:02
 */

namespace HuuLe\AmazonSDK;

interface AmazonResponse
{
    /**
     * Get Response function
     * @param $response
     * @return array|bool
     * @author HuuLe
     */
    public function getResponse($response);
}
