<?php
/**
 * File MWSClient.php
 * Created by HuuLe at 11/08/2020 08:24
 */

namespace HuuLe\AmazonSDK\Client;

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
     * ===== REPORT
     */
    /**
     * Get Report function
     * @param string|array $parameters
     * @return MWSClient
     * @author HuuLe
     */
    public function getReport($parameters)
    {
        try {
            // Only ReportId
            if (is_numeric($parameters))
                $parameters = ['ReportId' => $parameters];
            $request = $this->makeRequest($parameters);
            if ($request instanceof \MarketplaceWebService_Model_GetReportRequest) {
                if (!$request->isSetReport())
                    $request->setReport(@fopen('php://memory', 'rw+'));
                $response = $this->client->getReport($request);
                $this->setResponse($response);
                if ($response->isSetGetReportResult()) {
                    // Check result
                    $getReportResult = $response->getGetReportResult();
                    if ($getReportResult instanceof \MarketplaceWebService_Model_GetReportResult)
                        if ($getReportResult->isSetContentMd5())
                            $getReportResult->getContentMd5();
                    // Get stream data
                    $reportResource = $request->getReport();
                    if (is_resource($reportResource))
                        $this->setData(stream_get_contents($reportResource));
                }
            } else
                $this->setWrongRequestTypeError();
        } catch (\MarketplaceWebService_Exception $ex) {
            $this->setError($ex);
        }
        return $this;
    }

    /**
     * Get Report List function
     * @param array $parameters
     * @return MWSClient
     * @author HuuLe
     */
    public function getReportList($parameters)
    {
        try {
            $request = $this->makeRequest($parameters);
            if ($request instanceof \MarketplaceWebService_Model_GetReportListRequest) {
                $response = $this->client->getReportList($request);
                $this->setResponse($response);
                if ($response->isSetGetReportListResult()) {
                    // Check result
                    $getReportListResult = $response->getGetReportListResult();
                    if ($getReportListResult instanceof \MarketplaceWebService_Model_GetReportListResult)
                        if ($getReportListResult->isSetReportInfo()) {
                            $requestList = $getReportListResult->getReportInfoList();
                            $this->setData($this->toArray($requestList));
                        }
                    // Check next token
                    if ($getReportListResult->isSetNextToken())
                        $this->setNextToken($getReportListResult->getNextToken());
                }
            } else
                $this->setWrongRequestTypeError();
        } catch (\MarketplaceWebService_Exception $ex) {
            $this->setError($ex);
        }
        return $this;
    }

    /**
     * Get Report Count function
     * @param array $parameters
     * @return MWSClient
     * @author HuuLe
     */
    public function getReportCount($parameters)
    {
        // TODO: Implement getReportCount() method.
        return $this;
    }

    /**
     * ===== REPORT REQUEST
     */
    /**
     * Get Report Request List function
     * @param array $parameters
     * @return MWSClient
     * @author HuuLe
     */
    public function getReportRequestList($parameters)
    {
        try {
            $request = $this->makeRequest($parameters);
            if ($request instanceof \MarketplaceWebService_Model_GetReportRequestListRequest) {
                $response = $this->client->getReportRequestList($request);
                $this->setResponse($response);
                if ($response->isSetGetReportRequestListResult()) {
                    // Check result
                    $getReportRequestListResult = $response->getGetReportRequestListResult();
                    if ($getReportRequestListResult instanceof \MarketplaceWebService_Model_GetReportRequestListResult)
                        if ($getReportRequestListResult->isSetReportRequestInfo()) {
                            $requestList = $getReportRequestListResult->getReportRequestInfoList();
                            $this->setData($this->toArray($requestList));
                        }
                    // Check next token
                    if ($getReportRequestListResult->isSetNextToken())
                        $this->setNextToken($getReportRequestListResult->getNextToken());
                }
            } else
                $this->setWrongRequestTypeError();
        } catch (\MarketplaceWebService_Exception $ex) {
            $this->setError($ex);
        }
        return $this;
    }

    /**
     * Get Report Request List By Next Token function
     * @param array $parameters
     * @return MWSClient
     * @author HuuLe
     */
    public function getReportRequestListByNextToken($parameters)
    {
        try {
            $request = $this->makeRequest($parameters);
            if ($request instanceof \MarketplaceWebService_Model_GetReportRequestListByNextTokenRequest) {
                $response = $this->client->getReportRequestListByNextToken($request);
                if ($response->isSetGetReportRequestListByNextTokenResult()) {
                    $getReportRequestListByNextTokenResult = $response->getGetReportRequestListByNextTokenResult();
                    if ($getReportRequestListByNextTokenResult instanceof \MarketplaceWebService_Model_GetReportRequestListByNextTokenResult) {
                        $requestList = $getReportRequestListByNextTokenResult->getReportRequestInfoList();
                        $this->setData($this->toArray($requestList));
                    }
                    // Check next token
                    if ($getReportRequestListByNextTokenResult->isSetNextToken())
                        $this->setNextToken($getReportRequestListByNextTokenResult->getNextToken());
                }
            } else
                $this->setWrongRequestTypeError();
        } catch (\MarketplaceWebService_Exception $ex) {
            $this->setError($ex);
        }
        return $this;
    }

    /**
     * Get Report Request List Count function
     * @param array $parameters
     * @return MWSClient
     * @author HuuLe
     */
    public function getReportRequestCount($parameters)
    {
        // TODO: Implement getReportRequestCount() method.
        return $this;
    }

    /**
     * Get Request Report function
     * @param array $parameters
     * @return MWSClient
     * @author HuuLe
     */
    public function requestReport($parameters)
    {
        // TODO: Implement requestReport() method.
        return $this;
    }

    /**
     * Get Request Report function
     * @param array $parameters
     * @return MWSClient
     * @author HuuLe
     */
    public function cancelReportRequests($parameters)
    {
        // TODO: Implement cancelReportRequests() method.
        return $this;
    }

    /**
     * ===== REPORT SCHEDULE
     */
    /**
     * Get Report Schedule List function
     * @param array $parameters
     * @return MWSClient
     * @author HuuLe
     */
    public function getReportScheduleList($parameters)
    {
        // TODO: Implement getReportScheduleList() method.
        return $this;
    }

    /**
     * Get Report Schedule Count function
     * @param array $parameters
     * @return MWSClient
     * @author HuuLe
     */
    public function getReportScheduleCount($parameters)
    {
        try {
            $request = $this->makeRequest($parameters);
            if ($request instanceof \MarketplaceWebService_Model_GetReportScheduleCountRequest) {
                $response = $this->client->getReportScheduleCount($request);
                $this->setResponse($response);
                if ($response->isSetGetReportScheduleCountResult()) {
                    // Check result
                    $getReportScheduleCountResult = $response->getGetReportScheduleCountResult();
                    if ($getReportScheduleCountResult instanceof \MarketplaceWebService_Model_GetReportScheduleCountResult)
                        if ($getReportScheduleCountResult->isSetCount())
                            $this->setData($getReportScheduleCountResult->getCount());
                }
            } else
                $this->setWrongRequestTypeError();
        } catch (\MarketplaceWebService_Exception $ex) {
            $this->setError($ex);
        }
        return $this;
    }

    /**
     * Get Request Report function
     * @param array $parameters
     * @return MWSClient
     * @author HuuLe
     */
    public function getReportScheduleListByNextToken($parameters)
    {
        // TODO: Implement getReportScheduleListByNextToken() method.
        return $this;
    }

    /**
     * Get Request Report function
     * @param array $parameters
     * @return MWSClient
     * @author HuuLe
     */
    public function getReportListByNextToken($parameters)
    {
        // TODO: Implement getReportListByNextToken() method.
        return $this;
    }

    /**
     * Get Request Report function
     * @param array $parameters
     * @return MWSClient
     * @author HuuLe
     */
    public function manageReportSchedule($parameters)
    {
        // TODO: Implement manageReportSchedule() method.
        return $this;
    }

    /**
     * Get Request Report function
     * @param array $parameters
     * @return MWSClient
     * @author HuuLe
     */
    public function updateReportAcknowledgements($parameters)
    {
        // TODO: Implement updateReportAcknowledgements() method.
        return $this;
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
        return $this;
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
        return $this;
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
        return $this;
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
        return $this;
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
        return $this;
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
        return $this;
    }
}
