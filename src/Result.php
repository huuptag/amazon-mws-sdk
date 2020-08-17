<?php
/**
 * File Result.php
 * Created by HuuLe at 12/08/2020 14:53
 */

namespace HuuLe\AmazonSDK;

trait_exists(Helpers::class, true);

class Result
{
    use Errors, Helpers;

    private $data;
    private $response;
    private $nextToken;
    private $error;

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return mixed
     */
    public function getNextToken()
    {
        return $this->nextToken;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @param mixed $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * @param mixed $nextToken
     */
    public function setNextToken($nextToken)
    {
        $this->nextToken = $nextToken;
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * Has Data function
     * @return bool
     * @author HuuLe
     */
    public function hasData()
    {
        return $this->data !== false;
    }

    /**
     * Has Response function
     * @return bool
     * @author HuuLe
     */
    public function hasResponse()
    {
        return !empty($this->response);
    }

    /**
     * Has Next Token function
     * @return bool
     * @author HuuLe
     */
    public function hasNextToken()
    {
        return !empty($this->nextToken);
    }

    /**
     * Has Error function
     * @return bool
     * @author HuuLe
     */
    public function hasError()
    {
        return !empty($this->error);
    }

    /**
     * To Flatten Array function
     * @return self
     * @author HuuLe
     */
    public function toFlattenArray()
    {
        if ($this->hasData()) {
            $arrData = $this->getData();
            $this->setData($this->removeNestedLevel($arrData));
        }
        return $this;
    }
}
