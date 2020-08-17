<?php
/**
 * File MWSOrderClient.php
 * Created by HuuLe at 11/08/2020 08:27
 */

namespace HuuLe\AmazonSDK\Client;

use HuuLe\AmazonSDK\Constant;
use HuuLe\AmazonSDK\Result;

/**
 * Class MWSOrderClient
 * @package HuuLe\AmazonSDK\Client
 * @author HuuLe
 */
class MWSOrderClient extends Client implements \MarketplaceWebServiceOrders_Interface
{
    private $client;

    /**
     * MWSOrderClient constructor.
     * @param string $wmsAccessKeyID
     * @param string $wmsSecretAccessKey
     * @param array $config
     * Config parameters:
     * - ServiceURL:    string(default: https://mws.amazonservices.com/Orders/2013-09-01)
     * - ProxyHost:     string
     * - ProxyPort:     int
     * - ProxyUsername: string
     * - ProxyPassword: string
     * - MaxErrorRetry: int
     */
    public function __construct($wmsAccessKeyID, $wmsSecretAccessKey, $config = [])
    {
        if (empty($config['ServiceURL']))
            $config['ServiceURL'] = Constant::MWS_ORDER_SERVICE_URL;
        parent::__construct(\MarketplaceWebServiceOrders_Client::class, $wmsAccessKeyID, $wmsSecretAccessKey, $config);
        $this->client = new \MarketplaceWebServiceOrders_Client(
            $this->getMwsAccessKeyID(),
            $this->getMwsSecretAccessKey(),
            $this->getApplicationName(),
            $this->getApplicationVersion(),
            $this->getConfig() // <-- Config has been moved to here
        );
    }

    /**
     * Get Service Status function
     * @param mixed $request
     * @return Result
     * @author HuuLe
     */
    public function getServiceStatus($request = false)
    {
        $result = new Result();
        try {
            $request = $this->makeRequest();
            if ($request instanceof \MarketplaceWebServiceOrders_Model_GetServiceStatusRequest) {
                $response = $this->client->getServiceStatus($request);
                $result->setResponse($response);
                if ($response->isSetGetServiceStatusResult()) {
                    $getServiceStatusResult = $response->getGetServiceStatusResult();
                    if ($getServiceStatusResult instanceof \MarketplaceWebServiceOrders_Model_GetServiceStatusResult) {
                        $data = [];
                        if ($getServiceStatusResult->isSetStatus())
                            $data['Status'] = $getServiceStatusResult->getStatus();
                        if ($getServiceStatusResult->isSetTimestamp())
                            $data['Timestamp'] = $getServiceStatusResult->getTimestamp();
                        if ($getServiceStatusResult->isSetMessages())
                            $data['Messages'] = $getServiceStatusResult->getMessages();
                        if ($getServiceStatusResult->isSetMessageId())
                            $data['MessageId'] = $getServiceStatusResult->getMessageId();
                        $result->setData($data);
                    }
                }
            } else
                $result->setWrongRequestTypeError();
        } catch (\MarketplaceWebServiceOrders_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * List Orders function
     * @param array $parameters
     * @return Result
     * @author HuuLe
     */
    public function listOrders($parameters)
    {
        $result = new Result();
        try {
            $request = $this->makeRequest($parameters);
            if ($request instanceof \MarketplaceWebServiceOrders_Model_ListOrdersRequest) {
                $response = $this->client->listOrders($request);
                $result->setResponse($response);
                if ($response->isSetListOrdersResult()) {
                    $listOrdersResult = $response->getListOrdersResult();
                    if ($listOrdersResult instanceof \MarketplaceWebServiceOrders_Model_ListOrdersResult) {
                        if ($listOrdersResult->isSetOrders())
                            $result->setData($this->toArray($listOrdersResult->getOrders()));
                        if ($listOrdersResult->isSetNextToken())
                            $result->setNextToken($listOrdersResult->getNextToken());
                    }
                }
            } else
                $result->setWrongRequestTypeError();
        } catch (\MarketplaceWebServiceOrders_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * List Orders By Next Token function
     * @param string|array $parameters
     * @return Result
     * @author HuuLe
     */
    public function listOrdersByNextToken($parameters)
    {
        $result = new Result();
        try {
            if (!empty($parameters)) {
                // Next Token only
                if (is_string($parameters))
                    $request = $this->makeRequest([
                        'NextToken' => $parameters
                    ]);
                else
                    $request = $this->makeRequest($parameters);
                if ($request instanceof \MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenRequest) {
                    $response = $this->client->listOrdersByNextToken($request);
                    $result->setResponse($response);
                    if ($response->isSetListOrdersByNextTokenResult()) {
                        $listOrdersByNextTokenResult = $response->getListOrdersByNextTokenResult();
                        if ($listOrdersByNextTokenResult instanceof \MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenResult) {
                            if ($listOrdersByNextTokenResult->isSetOrders())
                                $result->setData($this->toArray($listOrdersByNextTokenResult->getOrders()));
                            if ($listOrdersByNextTokenResult->isSetNextToken())
                                $result->setNextToken($listOrdersByNextTokenResult->getNextToken());
                        }
                    }
                } else
                    $result->setWrongRequestTypeError();
            } else
                $result->setMissingNextTokenError();
        } catch (\MarketplaceWebServiceOrders_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * Get Order function
     * @param array $parameters
     * @return Result
     * @author HuuLe
     */
    public function getOrder($parameters)
    {
        $result = new Result();
        try {
            if (!empty($parameters)) {
                if (is_string($parameters) || !$this->isAssoc($parameters))
                    $request = $this->makeRequest([
                        'AmazonOrderId' => $parameters
                    ]);
                else
                    $request = $this->makeRequest($parameters);
                if ($request instanceof \MarketplaceWebServiceOrders_Model_GetOrderRequest) {
                    $response = $this->client->getOrder($request);
                    $result->setResponse($response);
                    if ($response->isSetGetOrderResult()) {
                        $getOrderResult = $response->getGetOrderResult();
                        if ($getOrderResult instanceof \MarketplaceWebServiceOrders_Model_GetOrderResult &&
                            $getOrderResult->isSetOrders()) {
                            $result->setData($this->toArray($getOrderResult->getOrders()));
                        }
                    }
                } else
                    $result->setWrongRequestTypeError();
            } else
                $result->throwError('Missing Amazon Order Id');
        } catch (\MarketplaceWebServiceOrders_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * List Order Items function
     * @param string $amazonOrderID
     * @return Result
     * @author HuuLe
     */
    public function listOrderItems($amazonOrderID)
    {
        $result = new Result();
        try {
            if (!empty($amazonOrderID)) {
                $request = $this->makeRequest([
                    'AmazonOrderId' => $amazonOrderID
                ]);
                if ($request instanceof \MarketplaceWebServiceOrders_Model_ListOrderItemsRequest) {
                    $response = $this->client->listOrderItems($request);
                    $result->setResponse($response);
                    if ($response->isSetListOrderItemsResult()) {
                        $listOrderItemsResult = $response->getListOrderItemsResult();
                        if ($listOrderItemsResult instanceof \MarketplaceWebServiceOrders_Model_ListOrderItemsResult) {
                            if ($listOrderItemsResult->isSetOrderItems())
                                $result->setData($this->toArray($listOrderItemsResult->getOrderItems()));
                            if ($listOrderItemsResult->isSetNextToken())
                                $result->setNextToken($listOrderItemsResult->getNextToken());
                        }
                    }
                } else
                    $result->setWrongRequestTypeError();
            } else
                $result->throwError('Missing Amazon Order ID');
        } catch (\MarketplaceWebServiceOrders_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * List Order Items By Next Token function
     * @param string|array $parameters
     * @return Result
     * @author HuuLe
     */
    public function listOrderItemsByNextToken($parameters)
    {
        $result = new Result();
        try {
            if (!empty($parameters)) {
                // Next Token only
                if (is_string($parameters))
                    $request = $this->makeRequest([
                        'NextToken' => $parameters
                    ]);
                else
                    $request = $this->makeRequest($parameters);
                if ($request instanceof \MarketplaceWebServiceOrders_Model_ListOrderItemsByNextTokenRequest) {
                    $response = $this->client->listOrderItemsByNextToken($request);
                    $result->setResponse($response);
                    if ($response->isSetListOrderItemsByNextTokenResult()) {
                        $listOrderItemsResult = $response->getListOrderItemsByNextTokenResult();
                        if ($listOrderItemsResult instanceof \MarketplaceWebServiceOrders_Model_ListOrderItemsByNextTokenResult) {
                            if ($listOrderItemsResult->getOrderItems())
                                $result->setData($this->toArray($listOrderItemsResult->getOrderItems()));
                            if ($listOrderItemsResult->isSetNextToken())
                                $result->setNextToken($listOrderItemsResult->getNextToken());
                        }
                    }
                } else
                    $result->setWrongRequestTypeError();
            } else
                $result->setMissingNextTokenError();
        } catch (\MarketplaceWebServiceOrders_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }
}
