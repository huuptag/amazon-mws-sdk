<?php

/**
 * File Client.php
 * Created by HuuLe at 10/08/2020 17:27
 */

namespace HuuLe\AmazonSDK\Client;

include_once(__DIR__ . '/../AmazonMWS/AmazonAutoLoader.php');

use HuuLe\AmazonSDK\Constant;
use HuuLe\AmazonSDK\Errors;
use HuuLe\AmazonSDK\Helpers;
use HuuLe\AmazonSDK\Request;
use HuuLe\AmazonSDK\ResponseParser;

class Client
{
    use Request, ResponseParser, Helpers, Errors;

    private $mwsAccessKeyID;
    private $mwsSecretAccessKey;
    private $merchant;
    private $MWSAuthToken;
    private $config;
    private $applicationName = Constant::MWS_APPLICATION_NAME;
    private $applicationVersion = Constant::MWS_APPLICATION_VERSION;

    private $className;
    private $serviceFolder;

    const RequestClassFormat = '%s_Model_%sRequest';

    /**
     * Client constructor.
     * @param string $className
     * @param string $mwsAccessKeyID
     * @param string $mwsSecretAccessKey
     * @param array $config
     */
    public function __construct($className, $mwsAccessKeyID, $mwsSecretAccessKey, $config)
    {
        $this->mwsAccessKeyID = $mwsAccessKeyID;
        $this->mwsSecretAccessKey = $mwsSecretAccessKey;
        $this->config = $this->checkConfig($config);
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
     * Get Request Class function
     * @param string $requestName
     * @return string
     * @author HuuLe
     */
    public function getRequestClass($requestName)
    {
        return sprintf(self::RequestClassFormat, $this->getServiceFolder(), $requestName);
    }

    /**
     * @return mixed
     */
    public function getMwsAccessKeyID()
    {
        return $this->mwsAccessKeyID;
    }

    /**
     * @return mixed
     */
    public function getMwsSecretAccessKey()
    {
        return $this->mwsSecretAccessKey;
    }

    /**
     * @return mixed
     */
    public function getMerchant()
    {
        return $this->merchant;
    }

    /**
     * @return mixed
     */
    public function getMWSAuthToken()
    {
        return $this->MWSAuthToken;
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
     * @param mixed $mwsAccessKeyID
     */
    public function setMwsAccessKeyID($mwsAccessKeyID)
    {
        $this->mwsAccessKeyID = $mwsAccessKeyID;
    }

    /**
     * @param mixed $mwsSecretAccessKey
     */
    public function setMwsSecretAccessKey($mwsSecretAccessKey)
    {
        $this->mwsSecretAccessKey = $mwsSecretAccessKey;
    }

    /**
     * @param mixed $merchant
     */
    public function setMerchant($merchant)
    {
        $this->merchant = $merchant;
    }

    /**
     * @param mixed $MWSAuthToken
     */
    public function setMWSAuthToken($MWSAuthToken)
    {
        $this->MWSAuthToken = $MWSAuthToken;
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

    /**
     * Default Config function
     * @param array $config
     * @return array
     * @author HuuLe
     */
    public function checkConfig($config)
    {
        if (!is_array($config))
            $config = [];
        if (empty($config['ServiceURL']))
            $config['ServiceURL'] = Constant::MWS_SERVICE_URL;
        return $config;
    }
}
