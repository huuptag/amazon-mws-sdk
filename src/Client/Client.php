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

function dd(...$params)
{
    $caller = debug_backtrace();
    foreach ($params as $param) {
        echo "<pre>";
        print_r($param);
        echo "</pre>";
    }
    echo "<div><strong>Called from</strong> " . $caller[0]['file'] . " in line " . $caller[0]['line'] . '</div>';
    die;
}

class Client
{
    use Request, ResponseParser, Helpers, Errors;

    private $mwsAccessKeyID;
    private $mwsSecretAccessKey;
    private $sellerID;
    private $marketplaceID;
    private $MWSAuthToken;
    private $config;
    private $serviceURL;
    private $applicationName = Constant::MWS_APPLICATION_NAME;
    private $applicationVersion = Constant::MWS_APPLICATION_VERSION;

    private $className;
    private $serviceFolder;
    private $defaultTimezone = Constant::DEFAULT_TIMEZONE;
    private $defaultDateFormat = Constant::DEFAULT_DATETIME_FORMAT;

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
    public function getSellerID()
    {
        return $this->sellerID;
    }

    /**
     * @return mixed
     */
    public function getMarketplaceID()
    {
        return $this->marketplaceID;
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
     * @return mixed
     */
    public function getServiceURL()
    {
        return $this->serviceURL;
    }

    /**
     * @return string
     */
    public function getDefaultDateFormat()
    {
        return $this->defaultDateFormat;
    }

    /**
     * @return string
     */
    public function getDefaultTimezone()
    {
        return $this->defaultTimezone;
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
     * @param mixed $sellerID
     */
    public function setSellerID($sellerID)
    {
        $this->sellerID = $sellerID;
    }

    /**
     * @param mixed $marketplaceID
     */
    public function setMarketplaceID($marketplaceID)
    {
        $this->marketplaceID = $marketplaceID;
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
     * @param mixed $serviceURL
     */
    public function setServiceURL($serviceURL)
    {
        $this->serviceURL = $serviceURL;
    }

    /**
     * @param string $defaultDateFormat
     */
    public function setDefaultDateFormat($defaultDateFormat)
    {
        $this->defaultDateFormat = $defaultDateFormat;
    }

    /**
     * @param string $defaultTimezone
     */
    public function setDefaultTimezone($defaultTimezone)
    {
        $this->defaultTimezone = $defaultTimezone;
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
        if (empty($config['ServiceURL']) && $this->getServiceURL())
            $config['ServiceURL'] = $this->getServiceURL();
        if (!empty($config['ApplicationName'])) {
            $this->setApplicationName($config['ApplicationName']);
            unset($config['ApplicationName']);
        }
        if (!empty($config['ApplicationVersion'])) {
            $this->setApplicationVersion($config['ApplicationVersion']);
            unset($config['ApplicationVersion']);
        }
        if (!empty($config['SellerID'])) {
            $this->setSellerID($config['SellerID']);
            unset($config['SellerID']);
        }
        if (!empty($config['MarketplaceID'])) {
            $this->setMarketplaceID($config['MarketplaceID']);
            unset($config['MarketplaceID']);
        }
        if (!empty($config['DefaultTimezone'])) {
            $this->setDefaultTimezone($config['DefaultTimezone']);
            unset($config['DefaultTimezone']);
        }
        if (!empty($config['DefaultDateTimeFormat'])) {
            $this->setDefaultDateFormat($config['DefaultDateTimeFormat']);
            unset($config['DefaultDateTimeFormat']);
        }
        return $config;
    }
}
