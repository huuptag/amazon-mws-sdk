<?php
/**
 * File MWSClient.php
 * Created by HuuLe at 11/08/2020 08:24
 */

namespace HuuLe\AmazonSDK\Client;

use HuuLe\AmazonSDK\Service;

class MWSClient extends Client implements Service
{
    public function __construct($wmsAccessKeyID, $wmsSecretAccessKey, $config)
    {
        parent::__construct($wmsAccessKeyID, $wmsSecretAccessKey, $config);
    }

    public function client()
    {
        // TODO: Implement service() method.
        return new \MarketplaceWebService_Client(
            $this->getWmsAccessKeyID(),
            $this->getWmsSecretAccessKey(),
            $this->getConfig(),
            $this->getApplicationName(),
            $this->getApplicationVersion()
        );
    }

    public function makeRequest($requestName, ...$config)
    {
        // TODO: Implement makeRequest() method.
    }

    public function getResponse($response)
    {
        // TODO: Implement getResponse() method.
    }
}
