<?php
/**
 * File MWSClient.php
 * Created by HuuLe at 11/08/2020 08:24
 */

namespace HuuLe\AmazonSDK\Client;

use HuuLe\AmazonSDK\AmazonRequest;
use HuuLe\AmazonSDK\AmazonResponse;

class MWSClient extends Client implements AmazonRequest, AmazonResponse
{
    private $client;

    /**
     * MWS Client constructor.
     * @param $wmsAccessKeyID
     * @param $wmsSecretAccessKey
     * @param $config
     */
    public function __construct($wmsAccessKeyID, $wmsSecretAccessKey, $config)
    {
        parent::__construct(\MarketplaceWebService_Client::class, $wmsAccessKeyID, $wmsSecretAccessKey, $config);
        $this->client = new \MarketplaceWebService_Client(
            $this->getWmsAccessKeyID(),
            $this->getWmsSecretAccessKey(),
            $this->getConfig(),
            $this->getApplicationName(),
            $this->getApplicationVersion()
        );
    }

    /**
     * Make Request function
     * @param string $requestName
     * @param array $parameters
     * @return array|bool|mixed
     * @author HuuLe
     */
    public function makeRequest($requestName, $parameters = [])
    {
        // TODO: Implement makeRequest() method.
        $requestClass = $this->getRequestClass($requestName);
        if (class_exists($requestClass))
            return new $requestClass($parameters);
        return false;
    }

    public function getResponse($response)
    {
        // TODO: Implement getResponse() method.
    }

    /**
     * Get Report function
     * @param $parameters
     * @return void
     * @author HuuLe
     * @throws \MarketplaceWebService_Exception
     */
    public function getReport($parameters)
    {
        $request = $this->makeRequest('getReport', $parameters);
        if ($request instanceof \MarketplaceWebService_Model_GetReportRequest) {
            $response = $this->client->getReport($request);
            dd($response);
        }
    }

    public function getReportScheduleCount($request)
    {
        // TODO: Implement getReportScheduleCount() method.
    }

    public function getReportRequestListByNextToken($request)
    {
        // TODO: Implement getReportRequestListByNextToken() method.
    }

    public function updateReportAcknowledgements($request)
    {
        // TODO: Implement updateReportAcknowledgements() method.
    }

    public function submitFeed($request)
    {
        // TODO: Implement submitFeed() method.
    }

    public function getReportCount($request)
    {
        // TODO: Implement getReportCount() method.
    }

    public function getFeedSubmissionListByNextToken($request)
    {
        // TODO: Implement getFeedSubmissionListByNextToken() method.
    }

    public function cancelFeedSubmissions($request)
    {
        // TODO: Implement cancelFeedSubmissions() method.
    }

    public function requestReport($request)
    {
        // TODO: Implement requestReport() method.
    }

    public function getFeedSubmissionCount($request)
    {
        // TODO: Implement getFeedSubmissionCount() method.
    }

    public function cancelReportRequests($request)
    {
        // TODO: Implement cancelReportRequests() method.
    }

    public function getReportList($request)
    {
        // TODO: Implement getReportList() method.
    }

    public function getFeedSubmissionResult($request)
    {
        // TODO: Implement getFeedSubmissionResult() method.
    }

    public function getFeedSubmissionList($request)
    {
        // TODO: Implement getFeedSubmissionList() method.
    }

    public function getReportRequestList($request)
    {
        // TODO: Implement getReportRequestList() method.
    }

    public function getReportScheduleListByNextToken($request)
    {
        // TODO: Implement getReportScheduleListByNextToken() method.
    }

    public function getReportListByNextToken($request)
    {
        // TODO: Implement getReportListByNextToken() method.
    }

    public function manageReportSchedule($request)
    {
        // TODO: Implement manageReportSchedule() method.
    }

    public function getReportRequestCount($request)
    {
        // TODO: Implement getReportRequestCount() method.
    }

    public function getReportScheduleList($request)
    {
        // TODO: Implement getReportScheduleList() method.
    }
}
