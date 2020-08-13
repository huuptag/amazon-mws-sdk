<?php
/**
 * File MWSClient.php
 * Created by HuuLe at 11/08/2020 08:24
 */

namespace HuuLe\AmazonSDK\Client;

use HuuLe\AmazonSDK\Result;

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

class MWSClient extends Client implements \MarketplaceWebService_Interface
{
    protected $client;

    /**
     * MWS Client constructor.
     * @param string $wmsAccessKeyID
     * @param string $wmsSecretAccessKey
     * @param array $config
     */
    public function __construct($wmsAccessKeyID, $wmsSecretAccessKey, $config = [])
    {
        parent::__construct(\MarketplaceWebService_Client::class, $wmsAccessKeyID, $wmsSecretAccessKey, $config);
        $this->client = new \MarketplaceWebService_Client(
            $this->getMwsAccessKeyID(),
            $this->getMwsSecretAccessKey(),
            $this->getConfig(),
            $this->getApplicationName(),
            $this->getApplicationVersion()
        );
    }

    /**
     * Return Data function
     * @return MWSClient
     * @author HuuLe
     */
    public function cloneThis()
    {
        return clone $this;
    }

    /**
     * ===== REPORT
     */
    /**
     * Get Report function
     * @param string|array $parameters
     * @return Result
     * @author HuuLe
     */
    public function getReport($parameters)
    {
        // Make response handler
        $result = new Result();
        try {
            // Only ReportId
            if (is_numeric($parameters))
                $parameters = ['ReportId' => $parameters];
            $request = $this->makeRequest($parameters);
            // Check request type
            if ($request instanceof \MarketplaceWebService_Model_GetReportRequest) {
                if (!$request->isSetReport())
                    $request->setReport(@fopen('php://memory', 'rw+'));
                // Send a request to Amazon MWS library
                $getReportResponse = $this->client->getReport($request);
                $result->setResponse($getReportResponse);
                // Check response
                if ($getReportResponse->isSetGetReportResult()) {
                    $getReportResult = $getReportResponse->getGetReportResult();
                    // Check result type
                    if ($getReportResult instanceof \MarketplaceWebService_Model_GetReportResult &&
                        $getReportResult->isSetContentMd5())
                        $getReportResult->getContentMd5();
                    // Get stream data to write file
                    $reportResource = $request->getReport();
                    if (is_resource($reportResource))
                        $result->setData(stream_get_contents($reportResource));
                }
            } else
                $result->setWrongRequestTypeError();
        } catch (\MarketplaceWebService_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * Get Report List function
     * @param array $parameters
     * @return Result
     * @author HuuLe
     */
    public function getReportList($parameters)
    {
        $result = new Result();
        try {
            $request = $this->makeRequest($parameters);
            if ($request instanceof \MarketplaceWebService_Model_GetReportListRequest) {
                $getReportListResponse = $this->client->getReportList($request);
                $result->setResponse($getReportListResponse);
                if ($getReportListResponse->isSetGetReportListResult()) {
                    // Check result
                    $getReportListResult = $getReportListResponse->getGetReportListResult();
                    if ($getReportListResult instanceof \MarketplaceWebService_Model_GetReportListResult)
                        if ($getReportListResult->isSetReportInfo()) {
                            $requestList = $getReportListResult->getReportInfoList();
                            $result->setData($this->toArray($requestList));
                        }
                    // Check next token
                    if ($getReportListResult->isSetNextToken())
                        $result->setNextToken($getReportListResult->getNextToken());
                }
            } else
                $this->setWrongRequestTypeError();
        } catch (\MarketplaceWebService_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * Get Report List by Next Token function
     * @param string $nextToken
     * @return Result
     * @author HuuLe
     */
    public function getReportListByNextToken($nextToken)
    {
        $result = new Result();
        try {
            if ($nextToken) {
                $request = $this->makeRequest([
                    'NextToken' => $nextToken
                ]);
                if ($request instanceof \MarketplaceWebService_Model_GetReportListByNextTokenRequest) {
                    $response = $this->client->getReportListByNextToken($request);
                    $result->setResponse($response);
                    if ($response->isSetGetReportListByNextTokenResult()) {
                        // Check result
                        $getReportListResult = $response->getGetReportListByNextTokenResult();
                        if ($getReportListResult instanceof \MarketplaceWebService_Model_GetReportListByNextTokenResult)
                            if ($getReportListResult->isSetReportInfo()) {
                                $requestList = $getReportListResult->getReportInfoList();
                                $result->setData($this->toArray($requestList));
                            }
                        // Check next token
                        if ($getReportListResult->isSetNextToken())
                            $result->setNextToken($getReportListResult->getNextToken());
                    }
                } else
                    $result->setWrongRequestTypeError();
            } else
                $result->setMissingNextTokenError();
        } catch (\MarketplaceWebService_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * Get Report Count function
     * @param array $parameters
     * @return Result
     * @author HuuLe
     */
    public function getReportCount($parameters)
    {
        $result = new Result();
        try {
            $request = $this->makeRequest($parameters);
            if ($request instanceof \MarketplaceWebService_Model_GetReportCountRequest) {
                $response = $this->client->getReportCount($request);
                $result->setResponse($response);
                if ($response->isSetGetReportCountResult()) {
                    // Check result
                    $getReportListResult = $response->getGetReportCountResult();
                    if ($getReportListResult instanceof \MarketplaceWebService_Model_GetReportCountResult &&
                        $getReportListResult->isSetCount()) {
                        $result->setData($getReportListResult->getCount());
                    }
                }
            } else
                $this->setWrongRequestTypeError();
        } catch (\MarketplaceWebService_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * Get Report By Request ID And Type function
     * @param string $reportRequestID
     * @param string $reportType
     * @return Result
     * @author HuuLe
     */
    public function getReportByRequestIDAndType($reportRequestID, $reportType)
    {
        $result = $this->getReportList([
            'MaxCount' => 1,
            'ReportRequestIdList' => [
                'Id' => $reportRequestID
            ],
            'ReportTypeList' => [
                'Type' => $reportType
            ]
        ]);
        if ($result->getData() && !empty($result->getData()[0])) {
            $resultItem = $result->getData()[0];
            $result->setData($resultItem);
        }
        return $result;
    }

    /**
     * ===== REPORT REQUEST
     */
    /**
     * Get Report Request List function
     * @param array $parameters
     * @return Result
     * @author HuuLe
     */
    public function getReportRequestList($parameters)
    {
        $result = new Result();
        try {
            $request = $this->makeRequest($parameters);
            if ($request instanceof \MarketplaceWebService_Model_GetReportRequestListRequest) {
                $response = $this->client->getReportRequestList($request);
                $result->setResponse($response);
                if ($response->isSetGetReportRequestListResult()) {
                    // Check result
                    $getReportRequestListResult = $response->getGetReportRequestListResult();
                    if ($getReportRequestListResult instanceof \MarketplaceWebService_Model_GetReportRequestListResult)
                        if ($getReportRequestListResult->isSetReportRequestInfo()) {
                            $requestList = $getReportRequestListResult->getReportRequestInfoList();
                            $result->setData($this->toArray($requestList));
                        }
                    // Check next token
                    if ($getReportRequestListResult->isSetNextToken())
                        $result->setNextToken($getReportRequestListResult->getNextToken());
                }
            } else
                $result->setWrongRequestTypeError();
        } catch (\MarketplaceWebService_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * Get Report Request List By Next Token function
     * @param string $nextToken
     * @return Result
     * @author HuuLe
     */
    public function getReportRequestListByNextToken($nextToken)
    {
        $result = new Result();
        try {
            if ($nextToken) {
                $request = $this->makeRequest([
                    'NextToken' => $nextToken
                ]);
                if ($request instanceof \MarketplaceWebService_Model_GetReportRequestListByNextTokenRequest) {
                    $response = $this->client->getReportRequestListByNextToken($request);
                    $result->setResponse($response);
                    if ($response->isSetGetReportRequestListByNextTokenResult()) {
                        $getReportRequestListByNextTokenResult = $response->getGetReportRequestListByNextTokenResult();
                        if ($getReportRequestListByNextTokenResult instanceof \MarketplaceWebService_Model_GetReportRequestListByNextTokenResult) {
                            $requestList = $getReportRequestListByNextTokenResult->getReportRequestInfoList();
                            $result->setData($this->toArray($requestList));
                        }
                        // Check next token
                        if ($getReportRequestListByNextTokenResult->isSetNextToken())
                            $result->setNextToken($getReportRequestListByNextTokenResult->getNextToken());
                    }
                } else
                    $result->setWrongRequestTypeError();
            } else
                $result->setMissingNextTokenError();
        } catch (\MarketplaceWebService_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * Get Report Request List Count function
     * @param array $parameters
     * @return Result
     * @author HuuLe
     */
    public function getReportRequestCount($parameters)
    {
        $result = new Result();
        try {
            $request = $this->makeRequest($parameters);
            if ($request instanceof \MarketplaceWebService_Model_GetReportRequestCountRequest) {
                $response = $this->client->getReportRequestCount($request);
                $result->setResponse($response);
                if ($response->isSetGetReportRequestCountResult()) {
                    $getReportRequestCountResult = $response->getGetReportRequestCountResult();
                    if ($getReportRequestCountResult instanceof \MarketplaceWebService_Model_GetReportRequestCountResult &&
                        $getReportRequestCountResult->isSetCount()) {
                        $result->setData($getReportRequestCountResult->getCount());
                    }
                }
            } else
                $result->setWrongRequestTypeError();
        } catch (\MarketplaceWebService_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * Get Request Report function
     * @param array $parameters
     * @return Result
     * @author HuuLe
     */
    public function requestReport($parameters)
    {
        $result = new Result();
        try {
            $request = $this->makeRequest($parameters);
            if ($request instanceof \MarketplaceWebService_Model_RequestReportRequest) {
                $response = $this->client->requestReport($request);
                $result->setResponse($response);
                if ($response->isSetRequestReportResult()) {
                    $requestReportResult = $response->getRequestReportResult();
                    if ($requestReportResult instanceof \MarketplaceWebService_Model_RequestReportResult &&
                        $requestReportResult->isSetReportRequestInfo()) {
                        $reportRequestInfo = $requestReportResult->getReportRequestInfo();
                        $result->setData($this->toArray($reportRequestInfo));
                    }
                }
            } else
                $this->setWrongRequestTypeError();
        } catch (\MarketplaceWebService_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * Get Request Report function
     * @param array $parameters
     * @return Result
     * @author HuuLe
     */
    public function cancelReportRequests($parameters)
    {
        $result = new Result();
        try {
            $request = $this->makeRequest($parameters);
            if ($request instanceof \MarketplaceWebService_Model_CancelReportRequestsRequest) {
                $response = $this->client->cancelReportRequests($request);
                $result->setResponse($response);
                if ($response->isSetCancelReportRequestsResult()) {
                    $cancelReportRequestsResult = $response->getCancelReportRequestsResult();
                    if ($cancelReportRequestsResult instanceof \MarketplaceWebService_Model_CancelReportRequestsResult) {
                        $data = [];
                        if ($cancelReportRequestsResult->isSetCount())
                            $data['Count'] = $cancelReportRequestsResult->getCount();
                        if ($cancelReportRequestsResult->isSetReportRequestInfo())
                            $data['ReportRequestInfo'] = $this->toArray($cancelReportRequestsResult->getReportRequestInfoList());
                        $result->setData($data);
                    }
                }
            } else
                $result->setWrongRequestTypeError();
        } catch (\MarketplaceWebService_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * ===== REPORT SCHEDULE
     */
    /**
     * Get Report Schedule List function
     * @param array $parameters
     * @return Result
     * @author HuuLe
     */
    public function getReportScheduleList($parameters)
    {
        $result = new Result();
        try {
            $request = $this->makeRequest($parameters);
            if ($request instanceof \MarketplaceWebService_Model_GetReportScheduleListRequest) {
                $response = $this->client->getReportScheduleList($request);
                $result->setResponse($response);
                if ($response->isSetGetReportScheduleListResult()) {
                    $getReportScheduleListResult = $response->getGetReportScheduleListResult();
                    if ($getReportScheduleListResult instanceof \MarketplaceWebService_Model_GetReportScheduleListResult &&
                        $getReportScheduleListResult->isSetReportSchedule()) {
                        $reportScheduleList = $getReportScheduleListResult->getReportScheduleList();
                        $result->setData($this->toArray($reportScheduleList));
                        // Check next token
                        if ($getReportScheduleListResult->isSetNextToken())
                            $result->setNextToken($getReportScheduleListResult->getNextToken());
                    }
                }
            } else
                $result->setWrongRequestTypeError();
        } catch (\MarketplaceWebService_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * Get Request Report function
     * @param string $nextToken
     * @return Result
     * @author HuuLe
     */
    public function getReportScheduleListByNextToken($nextToken)
    {
        $result = new Result();
        try {
            $request = $this->makeRequest([
                'NextToken' => $nextToken
            ]);
            if ($request instanceof \MarketplaceWebService_Model_GetReportScheduleListByNextTokenRequest) {
                $response = $this->client->getReportScheduleListByNextToken($request);
                $result->setResponse($response);
                if ($response->isSetGetReportScheduleListByNextTokenResult()) {
                    $getReportScheduleListByNextTokenResult = $response->getGetReportScheduleListByNextTokenResult();
                    if ($getReportScheduleListByNextTokenResult instanceof \MarketplaceWebService_Model_GetReportScheduleListByNextTokenResult &&
                        $getReportScheduleListByNextTokenResult->isSetReportSchedule()) {
                        $reportScheduleList = $getReportScheduleListByNextTokenResult->getReportScheduleList();
                        $result->setData($this->toArray($reportScheduleList));
                        // Check next token
                        if ($getReportScheduleListByNextTokenResult->isSetNextToken())
                            $result->setNextToken($getReportScheduleListByNextTokenResult->getNextToken());
                    }
                }
            }
        } catch (\MarketplaceWebService_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * Get Report Schedule Count function
     * @param array $parameters
     * @return Result
     * @author HuuLe
     */
    public function getReportScheduleCount($parameters)
    {
        $result = new Result();
        try {
            $request = $this->makeRequest($parameters);
            if ($request instanceof \MarketplaceWebService_Model_GetReportScheduleCountRequest) {
                $response = $this->client->getReportScheduleCount($request);
                $result->setResponse($response);
                if ($response->isSetGetReportScheduleCountResult()) {
                    // Check result
                    $getReportScheduleCountResult = $response->getGetReportScheduleCountResult();
                    if ($getReportScheduleCountResult instanceof \MarketplaceWebService_Model_GetReportScheduleCountResult)
                        if ($getReportScheduleCountResult->isSetCount())
                            $result->setData($getReportScheduleCountResult->getCount());
                }
            } else
                $result->setWrongRequestTypeError();
        } catch (\MarketplaceWebService_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * Get Request Report function
     * @param array $parameters
     * @return Result
     * @author HuuLe
     */
    public function manageReportSchedule($parameters)
    {
        $result = new Result();
        try {
            $request = $this->makeRequest($parameters);
            if ($request instanceof \MarketplaceWebService_Model_ManageReportScheduleRequest) {
                $response = $this->client->manageReportSchedule($request);
                $result->setResponse($response);
                if ($response->isSetManageReportScheduleResult()) {
                    $manageReportScheduleResult = $response->getManageReportScheduleResult();
                    if ($manageReportScheduleResult instanceof \MarketplaceWebService_Model_ManageReportScheduleResult &&
                        $manageReportScheduleResult->isSetCount())
                        $result->setData($manageReportScheduleResult->getCount());
                }
            } else
                $result->setWrongRequestTypeError();
        } catch (\MarketplaceWebService_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * Get Request Report function
     * @param array $parameters
     * @return MWSClient
     * @author HuuLe
     */
    public function updateReportAcknowledgements($parameters)
    {
        $result = new Result();
        return $this->cloneThis();
    }

    /**
     * ===== FEED
     */
    /**
     * Get Request Report function
     * @param array $parameters
     * @return MWSClient
     * @author HuuLe
     */
    public function getFeedSubmissionList($parameters)
    {
        // TODO: Implement getFeedSubmissionList() method.
        return $this->cloneThis();
    }

    /**
     * Get Request Report function
     * @param array $parameters
     * @return MWSClient
     * @author HuuLe
     */
    public function getFeedSubmissionListByNextToken($parameters)
    {
        // TODO: Implement getFeedSubmissionListByNextToken() method.
        return $this->cloneThis();
    }

    /**
     * Get Request Report function
     * @param array $parameters
     * @return MWSClient
     * @author HuuLe
     */
    public function getFeedSubmissionCount($parameters)
    {
        // TODO: Implement getFeedSubmissionCount() method.
        return $this->cloneThis();
    }

    /**
     * Get Request Report function
     * @param array $parameters
     * @return MWSClient
     * @author HuuLe
     */
    public function getFeedSubmissionResult($parameters)
    {
        // TODO: Implement getFeedSubmissionResult() method.
        return $this->cloneThis();
    }

    /**
     * Get Request Report function
     * @param array $parameters
     * @return MWSClient
     * @author HuuLe
     */
    public function submitFeed($parameters)
    {
        // TODO: Implement submitFeed() method.
        return $this->cloneThis();
    }

    /**
     * Get Request Report function
     * @param array $parameters
     * @return MWSClient
     * @author HuuLe
     */
    public function cancelFeedSubmissions($parameters)
    {
        // TODO: Implement cancelFeedSubmissions() method.
        return $this->cloneThis();
    }
}
