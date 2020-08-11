<?php
/**
 * File MWSProductClient.php
 * Created by HuuLe at 11/08/2020 08:27
 */

namespace HuuLe\AmazonSDK\Client;

class MWSProductClient extends Client
{
    public function __construct($wmsAccessKeyID, $wmsSecretAccessKey, $config)
    {
        parent::__construct($wmsAccessKeyID, $wmsSecretAccessKey, $config);
    }
}
