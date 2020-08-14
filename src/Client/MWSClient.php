<?php
/**
 * File MWSClient.php
 * Created by HuuLe at 11/08/2020 08:24
 */

namespace HuuLe\AmazonSDK\Client;

use HuuLe\AmazonSDK\Constant;
use HuuLe\AmazonSDK\Result;

/**
 * Class MWSClient
 * @package HuuLe\AmazonSDK\Client
 * @author HuuLe
 */
class MWSClient extends Client implements \MarketplaceWebService_Interface
{
    protected $client;

    /**
     * MWS Client constructor.
     * @param string $wmsAccessKeyID
     * @param string $wmsSecretAccessKey
     * @param array $config
     * Config parameters:
     * - ServiceURL:    string(default: https://mws.amazonservices.com)
     * - ProxyHost:     string
     * - ProxyPort:     int
     * - MaxErrorRetry: int
     */
    public function __construct($wmsAccessKeyID, $wmsSecretAccessKey, $config = [])
    {
        if (empty($config['ServiceURL']))
            $config['ServiceURL'] = Constant::MWS_SERVICE_URL;
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
                    if (is_resource($reportResource)) {
                        $result->setData(stream_get_contents($reportResource));
                        @fclose($reportResource);
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
     * @return Result
     * @author HuuLe
     */
    public function updateReportAcknowledgements($parameters)
    {
        $result = new Result();
        try {
            $request = $this->makeRequest($parameters);
            if ($request instanceof \MarketplaceWebService_Model_UpdateReportAcknowledgementsRequest) {
                $response = $this->client->updateReportAcknowledgements($request);
                $result->setResponse($response);
                if ($response->isSetUpdateReportAcknowledgementsResult()) {
                    $updateReportAcknowledgementsResult = $response->getUpdateReportAcknowledgementsResult();
                    if ($updateReportAcknowledgementsResult instanceof \MarketplaceWebService_Model_UpdateReportAcknowledgementsResult) {
                        $data = [];
                        if ($updateReportAcknowledgementsResult->isSetCount())
                            $data['Count'] = $updateReportAcknowledgementsResult->getCount();
                        if ($updateReportAcknowledgementsResult->isSetReportInfo())
                            $data['ReportInfo'] = $this->toArray($updateReportAcknowledgementsResult->getReportInfoList());
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
     * ===== FEED
     */
    /**
     * Get Request Report function
     * @param array $parameters
     * @return Result
     * @author HuuLe
     */
    public function getFeedSubmissionList($parameters)
    {
        $result = new Result();
        try {
            $request = $this->makeRequest($parameters);
            if ($request instanceof \MarketplaceWebService_Model_GetFeedSubmissionListRequest) {
                $response = $this->client->getFeedSubmissionList($request);
                $result->setResponse($response);
                if ($response->isSetGetFeedSubmissionListResult()) {
                    $getFeedSubmissionListResult = $response->getGetFeedSubmissionListResult();
                    if ($getFeedSubmissionListResult instanceof \MarketplaceWebService_Model_GetFeedSubmissionListResult &&
                        $getFeedSubmissionListResult->isSetFeedSubmissionInfo())
                        $result->setData($this->toArray($getFeedSubmissionListResult->getFeedSubmissionInfoList()));
                    if ($getFeedSubmissionListResult->isSetNextToken())
                        $result->setNextToken($getFeedSubmissionListResult->getNextToken());
                }
            } else
                $result->setWrongRequestTypeError();
        } catch (\MarketplaceWebService_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * Get Feed Submission List By Next Token function
     * @param string $nextToken
     * @return Result
     * @author HuuLe
     */
    public function getFeedSubmissionListByNextToken($nextToken)
    {
        $result = new Result();
        try {
            if ($nextToken) {
                $request = $this->makeRequest([
                    'NextToken' => $nextToken
                ]);
                if ($request instanceof \MarketplaceWebService_Model_GetFeedSubmissionListByNextTokenRequest) {
                    $response = $this->client->getFeedSubmissionListByNextToken($request);
                    $result->setResponse($response);
                    if ($response->isSetGetFeedSubmissionListByNextTokenResult()) {
                        $getFeedSubmissionListByNextToken = $response->getGetFeedSubmissionListByNextTokenResult();
                        if ($getFeedSubmissionListByNextToken instanceof \MarketplaceWebService_Model_GetFeedSubmissionListByNextTokenResult &&
                            $getFeedSubmissionListByNextToken->isSetFeedSubmissionInfo())
                            $result->setData($this->toArray($getFeedSubmissionListByNextToken->getFeedSubmissionInfoList()));
                        if ($getFeedSubmissionListByNextToken->isSetNextToken())
                            $result->setNextToken($getFeedSubmissionListByNextToken->getNextToken());
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
     * Get Feed Submission Count function
     * @param array $parameters
     * @return Result
     * @author HuuLe
     */
    public function getFeedSubmissionCount($parameters)
    {
        $result = new Result();
        try {
            $request = $this->makeRequest($parameters);
            if ($request instanceof \MarketplaceWebService_Model_GetFeedSubmissionCountRequest) {
                $response = $this->client->getFeedSubmissionCount($request);
                $result->setResponse($response);
                if ($response->isSetGetFeedSubmissionCountResult()) {
                    $getFeedSubmissionCountResult = $response->getGetFeedSubmissionCountResult();
                    if ($getFeedSubmissionCountResult instanceof \MarketplaceWebService_Model_GetFeedSubmissionCountResult &&
                        $getFeedSubmissionCountResult->isSetCount())
                        $result->setData($getFeedSubmissionCountResult->getCount());
                }
            } else
                $result->setWrongRequestTypeError();
        } catch (\MarketplaceWebService_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * Get Feed Submission Result function
     * @param string|array $parameters
     * @return Result
     * @author HuuLe
     */
    public function getFeedSubmissionResult($parameters)
    {
        $result = new Result();
        try {
            // Feed submission ID only
            if (is_numeric($parameters))
                $parameters = [
                    'FeedSubmissionId' => $parameters
                ];
            $request = $this->makeRequest($parameters);
            if ($request instanceof \MarketplaceWebService_Model_GetFeedSubmissionResultRequest) {
                if (!$request->isFeedSubmissionResult())
                    $request->setFeedSubmissionResult(@fopen('php://memory', 'rw+'));
                $response = $this->client->getFeedSubmissionResult($request);
                $result->setResponse($response);
                if ($response->isSetGetFeedSubmissionResultResult()) {
                    $getFeedSubmissionResult = $response->getGetFeedSubmissionResultResult();
                    if ($getFeedSubmissionResult instanceof \MarketplaceWebService_Model_GetFeedSubmissionResultResult) {
                        if ($getFeedSubmissionResult->isSetContentMd5())
                            $getFeedSubmissionResult->getContentMd5();
                    }
                }
                // Get stream data to write file
                $reportResource = $request->getFeedSubmissionResult();
                if (is_resource($reportResource)) {
                    $xmlData = stream_get_contents($reportResource);
                    $result->setData($this->convertXMLToArray($xmlData));
                    @fclose($reportResource);
                }
            }
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
    public function submitFeed($parameters)
    {
        $result = new Result();
        try {
            if (!empty($parameters['Feed'])) {
                $feed = $parameters['Feed'];
                unset($parameters['Feed']);
            } else {
                if (!empty($parameters['FeedType']) && !empty($parameters['FeedData'])) {
                    // Generate Feed by Type and Data, coming soon...
                    $feedData = $parameters['FeedData'];
                    unset($parameters['FeedData']);
                } else {
                    $missingParameters = [];
                    if (empty($parameters['FeedType']))
                        $missingParameters[] = 'FeedType';
                    if (empty($parameters['FeedData']))
                        $missingParameters[] = 'FeedData';
                    $result->throwError('Missing ' . join(', ', $missingParameters));
                }
            }
            if (isset($feed)) {
                $request = $this->makeRequest($parameters);
                if ($request instanceof \MarketplaceWebService_Model_SubmitFeedRequest) {
                    $feedHandle = @fopen('php://temp', 'rw+');
                    fwrite($feedHandle, $feed);
                    rewind($feedHandle);
                    if (!$request->isSetFeedContent())
                        $request->setFeedContent($feedHandle);
                    if (!$request->isSetContentMd5())
                        $request->setContentMd5(base64_encode(md5(stream_get_contents($feedHandle), true)));
                    $response = $this->client->submitFeed($request);
                    $result->setResponse($response);
                    if ($response->isSetSubmitFeedResult()) {
                        $submitFeedResult = $response->getSubmitFeedResult();
                        if ($submitFeedResult instanceof \MarketplaceWebService_Model_SubmitFeedResult &&
                            $submitFeedResult->isSetFeedSubmissionInfo()) {
                            $result->setData($this->toArray($submitFeedResult->getFeedSubmissionInfo()));
                        }
                    }
                } else
                    $result->setWrongRequestTypeError();
            } else
                $result->setMissingFeedDataError();
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
    public function cancelFeedSubmissions($parameters)
    {
        $result = new Result();
        try {
            $request = $this->makeRequest($parameters);
            if ($request instanceof \MarketplaceWebService_Model_CancelFeedSubmissionsRequest) {
                $response = $this->client->cancelFeedSubmissions($request);
                if ($response->isSetCancelFeedSubmissionsResult()) {
                    $cancelFeedSubmissionsResult = $response->getCancelFeedSubmissionsResult();
                    if ($cancelFeedSubmissionsResult instanceof \MarketplaceWebService_Model_CancelFeedSubmissionsResult) {
                        $data = [];
                        if ($cancelFeedSubmissionsResult->isSetCount())
                            $data['Count'] = $cancelFeedSubmissionsResult->getCount();
                        if ($cancelFeedSubmissionsResult->isSetFeedSubmissionInfo())
                            $data['FeedSubmissionInfo'] = $this->toArray($cancelFeedSubmissionsResult->getFeedSubmissionInfoList());
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
}
