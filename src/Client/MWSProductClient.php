<?php
/**
 * File MWSProductClient.php
 * Created by HuuLe at 11/08/2020 08:27
 */

namespace HuuLe\AmazonSDK\Client;

use HuuLe\AmazonSDK\Constant;
use HuuLe\AmazonSDK\Result;

class MWSProductClient extends Client implements \MarketplaceWebServiceProducts_Interface
{
    private $client;

    /**
     * MWSProductClient constructor.
     * @param string $wmsAccessKeyID
     * @param string $wmsSecretAccessKey
     * @param array $config
     * Config parameters:
     * - ServiceURL:    string(default: https://mws.amazonservices.com/Products/2011-10-01)
     * - ProxyHost:     string
     * - ProxyPort:     int
     * - ProxyUsername: string
     * - ProxyPassword: string
     * - MaxErrorRetry: int
     */
    public function __construct($wmsAccessKeyID, $wmsSecretAccessKey, $config = [])
    {
        if (empty($config['ServiceURL']))
            $config['ServiceURL'] = Constant::MWS_PRODUCT_SERVICE_URL;
        parent::__construct(\MarketplaceWebServiceProducts_Client::class, $wmsAccessKeyID, $wmsSecretAccessKey, $config);
        $this->client = new \MarketplaceWebServiceProducts_Client(
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
            if ($request instanceof \MarketplaceWebServiceProducts_Model_GetServiceStatusRequest) {
                $response = $this->client->getServiceStatus($request);
                $result->setResponse($response);
                if ($response->isSetGetServiceStatusResult()) {
                    $getServiceStatusResult = $response->getGetServiceStatusResult();
                    if ($getServiceStatusResult instanceof \MarketplaceWebServiceProducts_Model_GetServiceStatusResult) {
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
        } catch (\MarketplaceWebServiceProducts_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * List Matching Products function
     * @param array $parameters
     * @return Result
     * @author HuuLe
     */
    public function listMatchingProducts($parameters)
    {
        $result = new Result();
        try {
            $request = $this->makeRequest($parameters);
            if ($request instanceof \MarketplaceWebServiceProducts_Model_ListMatchingProductsRequest) {
                $response = $this->client->listMatchingProducts($request);
                $result->setResponse($response);
                if ($response->isSetListMatchingProductsResult()) {
                    $listMatchingProductsResult = $response->getListMatchingProductsResult();
                    if ($listMatchingProductsResult instanceof \MarketplaceWebServiceProducts_Model_ListMatchingProductsResult &&
                        $listMatchingProductsResult->isSetProducts()) {
                        $productList = $listMatchingProductsResult->getProducts();
                        $arrProductList = $this->toArray($productList);
                        if (!empty($arrProductList['Product']))
                            $result->setData($arrProductList['Product']);
                    }
                }
            } else
                $result->setWrongRequestTypeError();
        } catch (\MarketplaceWebServiceProducts_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * Get Matching Product function
     * @param array $ASIN
     * @return Result
     * @author HuuLe
     */
    public function getMatchingProduct($ASIN)
    {
        $result = new Result();
        try {
            $request = $this->makeRequest([
                'ASINList' => $ASIN
            ]);
            if ($request instanceof \MarketplaceWebServiceProducts_Model_GetMatchingProductRequest) {
                $response = $this->client->getMatchingProduct($request);
                $result->setResponse($response);
                if ($response->isSetGetMatchingProductResult()) {
                    $getMatchingProductResult = $response->getGetMatchingProductResult();
                    if (!empty($getMatchingProductResult) && is_array($getMatchingProductResult)) {
                        $data = [];
                        foreach ($getMatchingProductResult as $resultItem) {
                            if ($resultItem instanceof \MarketplaceWebServiceProducts_Model_GetMatchingProductResult &&
                                $resultItem->isSetASIN() && $resultItem->isSetProduct()) {
                                $data[$resultItem->getASIN()] = $this->toArray($resultItem->getProduct());
                            }
                        }
                        $result->setData($data);
                    }
                }
            } else
                $result->setWrongRequestTypeError();
        } catch (\MarketplaceWebServiceProducts_Exception $ex) {
            $result->setError($ex);
        }

        return $result;
    }

    /**
     * Get Matching Product For Id function
     * @param array $parameters
     * @return Result
     * @author HuuLe
     */
    public function getMatchingProductForId($parameters)
    {
        $result = new Result();
        try {
            $request = $this->makeRequest($parameters);
            if ($request instanceof \MarketplaceWebServiceProducts_Model_GetMatchingProductForIdRequest) {
                $response = $this->client->getMatchingProductForId($request);
                $result->setResponse($response);
                if ($response->isSetGetMatchingProductForIdResult()) {
                    $getMatchingProductForIDResult = $response->getGetMatchingProductForIdResult();
                    if (!empty($getMatchingProductForIDResult) && is_array($getMatchingProductForIDResult)) {
                        $data = [];
                        foreach ($getMatchingProductForIDResult as $resultItem) {
                            if ($resultItem instanceof \MarketplaceWebServiceProducts_Model_GetMatchingProductForIdResult &&
                                $resultItem->isSetId() && $resultItem->isSetProducts()) {
                                $arrProductList = $this->toArray($resultItem->getProducts());
                                if (!empty($arrProductList['Product']))
                                    $data[$resultItem->getId()] = $arrProductList['Product'];
                            }
                        }
                        $result->setData($data);
                    }
                }
            } else
                $result->setWrongRequestTypeError();
        } catch (\MarketplaceWebServiceProducts_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * Set Competitive Pricing Result function
     * @param array|object $getCompetitivePricingResult
     * @return array
     * @author HuuLe
     */
    private function setCompetitivePricingResult($getCompetitivePricingResult)
    {
        $data = [];
        if (!empty($getCompetitivePricingResult) && is_array($getCompetitivePricingResult)) {
            $data = [];
            foreach ($getCompetitivePricingResult as $resultItem) {
                if ($resultItem instanceof \MarketplaceWebServiceProducts_Model_GetCompetitivePricingForSKUResult &&
                    $resultItem->isSetSellerSKU() && $resultItem->isSetProduct()) {
                    $data[$resultItem->getSellerSKU()] = $this->toArray($resultItem->getProduct());
                }
                if ($resultItem instanceof \MarketplaceWebServiceProducts_Model_GetCompetitivePricingForASINResult &&
                    $resultItem->isSetASIN() && $resultItem->isSetProduct()) {
                    $data[$resultItem->getASIN()] = $this->toArray($resultItem->getProduct());
                }
            }
        }
        return $data;
    }

    /**
     * Get Competitive Pricing For SKU function
     * @param string|array $SellerSKUList
     * @return Result
     * @author HuuLe
     */
    public function getCompetitivePricingForSKU($SellerSKUList)
    {
        $result = new Result();
        try {
            if (!empty($SellerSKUList)) {
                $request = $this->makeRequest([
                    'SellerSKUList' => [
                        'SellerSKU' => $SellerSKUList
                    ]
                ]);
                if ($request instanceof \MarketplaceWebServiceProducts_Model_GetCompetitivePricingForSKURequest) {
                    $response = $this->client->getCompetitivePricingForSKU($request);
                    $result->setResponse($response);
                    if ($response->isSetGetCompetitivePricingForSKUResult()) {
                        $getCompetitivePricingForSKUResult = $response->getGetCompetitivePricingForSKUResult();
                        $result->setData($this->setCompetitivePricingResult($getCompetitivePricingForSKUResult));
                    }
                } else
                    $result->setWrongRequestTypeError();
            } else
                $result->throwError('Missing SellerSKUList');
        } catch (\MarketplaceWebServiceProducts_Exception $ex) {
            $result->setError($ex);
        }

        return $result;
    }

    /**
     * Get Competitive Pricing For ASIN function
     * @param string|array $ASINList
     * @return Result
     * @author HuuLe
     */
    public function getCompetitivePricingForASIN($ASINList)
    {
        $result = new Result();
        try {
            if (!empty($ASINList)) {
                $request = $this->makeRequest([
                    'ASINList' => [
                        'ASIN' => $ASINList
                    ]
                ]);
                if ($request instanceof \MarketplaceWebServiceProducts_Model_GetCompetitivePricingForASINRequest) {
                    $response = $this->client->getCompetitivePricingForASIN($request);
                    $result->setResponse($response);
                    if ($response->isSetGetCompetitivePricingForASINResult()) {
                        $getCompetitivePricingForASINResult = $response->getGetCompetitivePricingForASINResult();
                        $result->setData($this->setCompetitivePricingResult($getCompetitivePricingForASINResult));
                    }
                } else
                    $result->setWrongRequestTypeError();
            } else
                $result->throwError('Missing ASINList');
        } catch (\MarketplaceWebServiceProducts_Exception $ex) {
            $result->setError($ex);
        }

        return $result;
    }

    /**
     * Set Lowest Priced Offers Result function
     * @param array|object $lowestOfferPricedResult
     * @return array
     * @author HuuLe
     */
    private function setLowestPricedOffersResult($lowestOfferPricedResult)
    {
        $data = [
            'Identifier' => [],
            'Summary' => [],
            'Offers' => []
        ];
        if (!empty($lowestOfferPricedResult) && (
                $lowestOfferPricedResult instanceof \MarketplaceWebServiceProducts_Model_GetLowestPricedOffersForASINResult ||
                $lowestOfferPricedResult instanceof \MarketplaceWebServiceProducts_Model_GetLowestPricedOffersForSKUResult
            )) {
            if ($lowestOfferPricedResult->isSetIdentifier())
                $data['Identifier'] = $this->toArray($lowestOfferPricedResult->getIdentifier());
            if ($lowestOfferPricedResult->isSetSummary())
                $data['Summary'] = $this->toArray($lowestOfferPricedResult->getSummary());
            if ($lowestOfferPricedResult->isSetOffers()) {
                $arrOffers = $this->toArray($lowestOfferPricedResult->getOffers());
                $data['Offers'] = !empty($arrOffers['Offer']) ? $arrOffers['Offer'] : [];
            }
        }
        return $data;
    }

    /**
     * Get Lowest Priced Offers For SKU function
     * @param array $parameters
     * @return Result
     * @author HuuLe
     */
    public function getLowestPricedOffersForSKU($parameters)
    {
        $result = new Result();
        try {
            $request = $this->makeRequest($parameters);
            if ($request instanceof \MarketplaceWebServiceProducts_Model_GetLowestPricedOffersForSKURequest) {
                $response = $this->client->getLowestPricedOffersForSKU($request);
                $result->setResponse($response);
                if ($response->isSetGetLowestPricedOffersForSKUResult()) {
                    $getLowestPricedOffersForSKUResult = $response->getGetLowestPricedOffersForSKUResult();
                    $result->setData($this->setLowestPricedOffersResult($getLowestPricedOffersForSKUResult));
                }
            } else
                $result->setWrongRequestTypeError();
        } catch (\MarketplaceWebServiceProducts_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * Get Lowest Priced Offers For ASIN function
     * @param array $parameters
     * @return Result
     * @author HuuLe
     */
    public function getLowestPricedOffersForASIN($parameters)
    {
        $result = new Result();
        try {
            $request = $this->makeRequest($parameters);
            if ($request instanceof \MarketplaceWebServiceProducts_Model_GetLowestPricedOffersForASINRequest) {
                $response = $this->client->getLowestPricedOffersForASIN($request);
                $result->setResponse($response);
                if ($response->isSetGetLowestPricedOffersForASINResult()) {
                    $getLowestPricedOffersForASINResult = $response->getGetLowestPricedOffersForASINResult();
                    $result->setData($this->setLowestPricedOffersResult($getLowestPricedOffersForASINResult));
                }
            } else
                $result->setWrongRequestTypeError();
        } catch (\MarketplaceWebServiceProducts_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * Set Lowest Offer Listings Result function
     * @param array|object $getLowestOfferListingsResult
     * @return array
     * @author HuuLe
     */
    private function setLowestOfferListingsResult($getLowestOfferListingsResult)
    {
        $data = [];
        if (!empty($getLowestOfferListingsResult) && is_array($getLowestOfferListingsResult)) {
            $resultForSKU = $getLowestOfferListingsResult[0] instanceof \MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForSKUResult;
            $resultForASIN = $getLowestOfferListingsResult[0] instanceof \MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForASINResult;
            foreach ($getLowestOfferListingsResult as $resultItem) {
                $datum = [
                    'Product' => [],
                    'AllOfferListingsConsidered' => '',
                ];
                if ($resultForSKU || $resultForASIN) {
                    if ($resultItem->isSetProduct())
                        $datum['Product'] = $this->toArray($resultItem->getProduct());
                    if ($resultItem->isSetAllOfferListingsConsidered())
                        $datum['AllOfferListingsConsidered'] = $resultItem->getAllOfferListingsConsidered();
                }
                if ($resultForSKU && $resultItem->isSetSellerSKU())
                    $data[$resultItem->getSellerSKU()] = $datum;
                if ($resultForASIN && $resultItem->isSetASIN())
                    $data[$resultItem->getASIN()] = $datum;
            }
        }
        return $data;
    }

    /**
     * Get Lowest Offer Listings For SKU function
     * @param array $parameters
     * @return Result
     * @author HuuLe
     */
    public function getLowestOfferListingsForSKU($parameters)
    {
        $result = new Result();
        try {
            $request = $this->makeRequest($parameters);
            if ($request instanceof \MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForSKURequest) {
                $response = $this->client->getLowestOfferListingsForSKU($request);
                $result->setResponse($response);
                if ($response->isSetGetLowestOfferListingsForSKUResult()) {
                    $getLowestOfferListingsForSKUResult = $response->getGetLowestOfferListingsForSKUResult();
                    $result->setData($this->setLowestOfferListingsResult($getLowestOfferListingsForSKUResult));
                }
            } else
                $result->setWrongRequestTypeError();
        } catch (\MarketplaceWebServiceProducts_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * Get Lowest Offer Listings For ASIN function
     * @param array $parameters
     * @return Result
     * @author HuuLe
     */
    public function getLowestOfferListingsForASIN($parameters)
    {
        $result = new Result();
        try {
            $request = $this->makeRequest($parameters);
            if ($request instanceof \MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForASINRequest) {
                $response = $this->client->getLowestOfferListingsForASIN($request);
                $result->setResponse($response);
                if ($response->isSetGetLowestOfferListingsForASINResult()) {
                    $getLowestOfferListingsForASINResult = $response->getGetLowestOfferListingsForASINResult();
                    $result->setData($this->setLowestOfferListingsResult($getLowestOfferListingsForASINResult));
                }
            } else
                $result->setWrongRequestTypeError();
        } catch (\MarketplaceWebServiceProducts_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * Get My Fees Estimate function
     * @param array $feesEstimateRequestList
     * @return Result
     * @author HuuLe
     */
    public function getMyFeesEstimate($feesEstimateRequestList)
    {
        $result = new Result();
        try {
            if (is_array($feesEstimateRequestList) && !empty($feesEstimateRequestList)) {
                $request = $this->makeRequest([
                    'FeesEstimateRequestList' => [
                        'FeesEstimateRequest' => $feesEstimateRequestList
                    ]
                ]);
                if ($request instanceof \MarketplaceWebServiceProducts_Model_GetMyFeesEstimateRequest) {
                    $response = $this->client->getMyFeesEstimate($request);
                    $result->setResponse($response);
                    if ($response->isSetGetMyFeesEstimateResult()) {
                        $getMyFeesEstimateResult = $response->getGetMyFeesEstimateResult();
                        if ($getMyFeesEstimateResult instanceof \MarketplaceWebServiceProducts_Model_GetMyFeesEstimateResult &&
                            $getMyFeesEstimateResult->isSetFeesEstimateResultList()) {
                            $feesEstimateResult = $this->toArray($getMyFeesEstimateResult->getFeesEstimateResultList());
                            if (!empty($feesEstimateResult['FeesEstimateResult']))
                                $result->setData($feesEstimateResult['FeesEstimateResult']);
                        }
                    }
                } else
                    $result->setWrongRequestTypeError();
            } else {
                if (empty($feesEstimateRequestList))
                    $result->throwError('Missing FeesEstimateList(array)');
                else
                    $result->throwError('FeesEstimateList must be array');
            }
        } catch (\MarketplaceWebServiceProducts_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * Set My Price Result function
     * @param array|object $getMyPriceResult
     * @return array
     * @author HuuLe
     */
    private function setMyPriceResult($getMyPriceResult)
    {
        $data = [];
        if (!empty($getMyPriceResult) && is_array($getMyPriceResult)) {
            foreach ($getMyPriceResult as $resultItem) {
                if ($resultItem instanceof \MarketplaceWebServiceProducts_Model_GetMyPriceForSKUResult &&
                    $resultItem->isSetSellerSKU() && $resultItem->isSetProduct()) {
                    $data[$resultItem->getSellerSKU()] = $this->toArray($resultItem->getProduct());
                }
                if ($resultItem instanceof \MarketplaceWebServiceProducts_Model_GetMyPriceForASINResult &&
                    $resultItem->isSetASIN() && $resultItem->isSetProduct()) {
                    $data[$resultItem->getASIN()] = $this->toArray($resultItem->getProduct());
                }
            }
        }
        return $data;
    }

    /**
     * Get My Price For SKU function
     * @param array $parameters
     * @return Result
     * @author HuuLe
     */
    public function getMyPriceForSKU($parameters)
    {
        $result = new Result();
        try {
            $request = $this->makeRequest($parameters);
            if ($request instanceof \MarketplaceWebServiceProducts_Model_GetMyPriceForSKURequest) {
                $response = $this->client->getMyPriceForSKU($request);
                $result->setResponse($response);
                if ($response->isSetGetMyPriceForSKUResult()) {
                    $getMyPriceForSKUResult = $response->getGetMyPriceForSKUResult();
                    $result->setData($this->setMyPriceResult($getMyPriceForSKUResult));
                }
            } else
                $result->setWrongRequestTypeError();
        } catch (\MarketplaceWebServiceProducts_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * Get My Price For ASIN function
     * @param array $parameters
     * @return Result
     * @author HuuLe
     */
    public function getMyPriceForASIN($parameters)
    {
        $result = new Result();
        try {
            $request = $this->makeRequest($parameters);
            if ($request instanceof \MarketplaceWebServiceProducts_Model_GetMyPriceForASINRequest) {
                $response = $this->client->getMyPriceForASIN($request);
                $result->setResponse($response);
                if ($response->isSetGetMyPriceForASINResult()) {
                    $getMyPriceForASINResult = $response->getGetMyPriceForASINResult();
                    $result->setData($this->setMyPriceResult($getMyPriceForASINResult));
                }
            } else
                $result->setWrongRequestTypeError();
        } catch (\MarketplaceWebServiceProducts_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * Get Product Categories For ASIN function
     * @param string $ASIN
     * @return Result
     * @author HuuLe
     */
    public function getProductCategoriesForASIN($ASIN)
    {
        $result = new Result();
        try {
            if (!empty($ASIN)) {
                $request = $this->makeRequest([
                    'ASIN' => $ASIN
                ]);
                if ($request instanceof \MarketplaceWebServiceProducts_Model_GetProductCategoriesForASINRequest) {
                    $response = $this->client->getProductCategoriesForASIN($request);
                    if ($response->isSetGetProductCategoriesForASINResult()) {
                        $getProductCategoriesResult = $response->getGetProductCategoriesForASINResult();
                        if ($getProductCategoriesResult instanceof \MarketplaceWebServiceProducts_Model_GetProductCategoriesForASINResult &&
                            $getProductCategoriesResult->isSetSelf()) {
                            $result->setData($this->toArray($getProductCategoriesResult->getSelf()));
                        }
                    }
                } else
                    $result->setWrongRequestTypeError();
            } else
                $result->throwError('Missing ASIN');
        } catch (\MarketplaceWebServiceProducts_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    /**
     * Get Product Categories For SKU function
     * @param string $sellerSKU
     * @return Result
     * @author HuuLe
     */
    public function getProductCategoriesForSKU($sellerSKU)
    {
        $result = new Result();
        try {
            if (!empty($sellerSKU)) {
                $request = $this->makeRequest([
                    'SellerSKU' => $sellerSKU
                ]);
                if ($request instanceof \MarketplaceWebServiceProducts_Model_GetProductCategoriesForSKURequest) {
                    $response = $this->client->getProductCategoriesForSKU($request);
                    if ($response->isSetGetProductCategoriesForSKUResult()) {
                        $getProductCategoriesResult = $response->getGetProductCategoriesForSKUResult();
                        if ($getProductCategoriesResult instanceof \MarketplaceWebServiceProducts_Model_GetProductCategoriesForSKUResult &&
                            $getProductCategoriesResult->isSetSelf()) {
                            $result->setData($this->toArray($getProductCategoriesResult->getSelf()));
                        }
                    }
                } else
                    $result->setWrongRequestTypeError();
            } else
                $result->throwError('Missing SellerSKU');
        } catch (\MarketplaceWebServiceProducts_Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }
}
