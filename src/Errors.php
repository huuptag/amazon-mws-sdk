<?php
/**
 * File HasException.php
 * Created by HuuLe at 12/08/2020 15:04
 */

namespace HuuLe\AmazonSDK;

trait Errors
{
    function throwError($message)
    {
        if (Constant::MWS_DEBUG) {
            $requestName = '';
            $caller = debug_backtrace();
            if (!empty($caller[1]['function']))
                $requestName = ucfirst($caller[1]['function']);
            $this->setError(new \Exception($message . ($requestName ? ' for ' . $requestName . ' function' : '') . '.'));
        }
    }

    /**
     * Set Wrong Request Type Error function
     * @return void
     * @author HuuLe
     */
    function setWrongRequestTypeError()
    {
        $this->throwError('Wrong request type');
    }

    /**
     * Set Missing Next Token Error function
     * @return void
     * @author HuuLe
     */
    function setMissingNextTokenError()
    {
        $this->throwError('Wrong request type');
    }
}
