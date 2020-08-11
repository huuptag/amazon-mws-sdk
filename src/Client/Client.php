<?php

/**
 * File Client.php
 * Created by HuuLe at 10/08/2020 17:27
 */

namespace HuuLe\AmazonSDK\Client;

use HuuLe\AmazonSDK\Constant as Constant;

class Client
{
    private $wmsAccessKeyID;
    private $wmsSecretAccessKey;
    private $config;
    private $applicationName = Constant::WMS_APPLICATION_NAME;
    private $applicationVersion = Constant::WMS_APPLICATION_VERSION;

    /**
     * Client constructor.
     * @param string $wmsAccessKeyID
     * @param string $wmsSecretAccessKey
     * @param array $config
     */
    public function __construct($wmsAccessKeyID, $wmsSecretAccessKey, $config)
    {
        $this->wmsAccessKeyID = $wmsAccessKeyID;
        $this->wmsSecretAccessKey = $wmsSecretAccessKey;
        $this->config = $config;
    }

    /**
     * @return mixed
     */
    public function getWmsAccessKeyID()
    {
        return $this->wmsAccessKeyID;
    }

    /**
     * @return mixed
     */
    public function getWmsSecretAccessKey()
    {
        return $this->wmsSecretAccessKey;
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return mixed
     */
    public function getApplicationName()
    {
        return $this->applicationName;
    }

    /**
     * @return mixed
     */
    public function getApplicationVersion()
    {
        return $this->applicationVersion;
    }

    /**
     * @param mixed $wmsAccessKeyID
     */
    public function setWmsAccessKeyID($wmsAccessKeyID)
    {
        $this->wmsAccessKeyID = $wmsAccessKeyID;
    }

    /**
     * @param mixed $wmsSecretAccessKey
     */
    public function setWmsSecretAccessKey($wmsSecretAccessKey)
    {
        $this->wmsSecretAccessKey = $wmsSecretAccessKey;
    }

    /**
     * @param mixed $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @param mixed $applicationName
     */
    public function setApplicationName($applicationName)
    {
        $this->applicationName = $applicationName;
    }

    /**
     * @param mixed $applicationVersion
     */
    public function setApplicationVersion($applicationVersion)
    {
        $this->applicationVersion = $applicationVersion;
    }
}
