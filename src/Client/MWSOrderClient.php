<?php
/**
 * File MWSOrderClient.php
 * Created by HuuLe at 11/08/2020 08:27
 */

namespace HuuLe\AmazonSDK\Client;

class MWSOrderClient extends Client
{
    public function __construct($wmsAccessKeyID, $wmsSecretAccessKey, $config)
    {
        parent::__construct($wmsAccessKeyID, $wmsSecretAccessKey, $config);
    }
}
