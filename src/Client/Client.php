<?php

/**
 * File Client.php
 * Created by HuuLe at 10/08/2020 17:27
 */

namespace HuuLe\AmazonSDK\Client;

include_once(__DIR__ . '/../AmazonMWS/AmazonAutoLoader.php');

use HuuLe\AmazonSDK\Constant as Constant;

class Client
{
    private $wmsAccessKeyID;
    private $wmsSecretAccessKey;
    private $config;
    private $applicationName = Constant::MWS_APPLICATION_NAME;
    private $applicationVersion = Constant::MWS_APPLICATION_VERSION;

    private $className;
    private $serviceFolder;
    private $requestName;

    const RequestClassFormat = '%s_Model_%sRequest';
    const ResponseClassFormat = '%s_Model_%sResponse';

    /**
     * Client constructor.
     * @param string $className
     * @param string $wmsAccessKeyID
     * @param string $wmsSecretAccessKey
     * @param array $config
     */
    public function __construct($className, $wmsAccessKeyID, $wmsSecretAccessKey, $config)
    {
        $this->wmsAccessKeyID = $wmsAccessKeyID;
        $this->wmsSecretAccessKey = $wmsSecretAccessKey;
        $this->config = $config;
        $this->className = $className;
        $this->serviceFolder = str_replace('_Client', '', $className);
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @return string|string[]
     */
    public function getServiceFolder()
    {
        return $this->serviceFolder;
    }

    /**
     * @return mixed
     */
    public function getRequestName()
    {
        return $this->requestName;
    }

    /**
     * Get Request Class function
     * @param string $requestName
     * @return string
     * @author HuuLe
     */
    public function getRequestClass($requestName)
    {
        $this->requestName = $requestName;
        return sprintf(self::RequestClassFormat, $this->getServiceFolder(), $requestName);
    }

    /**
     * Get Response Class function
     * @return string
     * @author HuuLe
     */
    public function getResponseClass()
    {
        return sprintf(self::ResponseClassFormat, $this->getServiceFolder(), $this->getRequestName());
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
