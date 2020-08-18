<?php

/**
 * File DateTime.php
 * Created by HuuLe at 12/08/2020 10:29
 */

namespace HuuLe\AmazonSDK;

use DateTime;
use DateTimeZone;

trait Helpers
{
    /**
     * Make Date Time function
     * @param string|int $time (default is now)
     * @param string $timezone
     * @return DateTime
     * @author HuuLe
     * @throws \Exception
     */
    public function makeDateTime($time = 'now', $timezone = null)
    {
        if (empty($timezone))
            $timezone = $this->getDefaultTimezone();
        return new DateTime($time, new DateTimeZone($timezone));
    }

    /**
     * Make Date Time For Request function
     * @param $time
     * @param string $timezone
     * @return string
     * @author HuuLe
     * @throws \Exception
     */
    public function makeDateTimeForRequest($time, $timezone = null)
    {
        return $this->makeDateTime($time, $timezone)->format(Constant::MWS_DATE_FORMAT);
    }

    /**
     * Check Formatted Date Time function
     * @param string $dateString
     * @return false|int
     * @author HuuLe
     */
    public function checkFormattedDateTime($dateString)
    {
        return preg_match('/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(\.\d{3})?Z/', $dateString);
    }

    /**
     * Convert Date Time Default Format By Formatted Value Returned From Amazon function
     * @param string $dateString
     * @return false|string
     * @author HuuLe
     */
    public function convertDateTimeDefaultFormat($dateString)
    {
        return date($this->getDefaultDateFormat(), strtotime($dateString));
    }

    /**
     * Check Is Assoc Array function
     * @param array $arr
     * @return bool
     * @author HuuLe
     */
    function isAssoc($arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * Is Object Or Object Class function
     * @param object|mixed $object
     * @return bool
     * @author HuuLe
     */
    function isObject($object)
    {
        return is_object($object) || $object instanceof \stdClass;
    }

    /**
     * Remove Nested Level function
     * @param array $arrMultiLevel
     * @param array $result
     * @return array
     * @author HuuLe
     */
    function removeNestedLevel($arrMultiLevel, $result = [])
    {
        if (is_array($arrMultiLevel)) {
            if (!$this->isAssoc($arrMultiLevel)) {
                foreach ($arrMultiLevel as $index => $item) {
                    $result[$index] = $this->removeNestedLevel($item);
                }
            } else {
                foreach ($arrMultiLevel as $field => $value) {
                    if (is_array($value)) {
                        $arrItem = $arrMultiLevel[$field];
                        unset($arrMultiLevel[$field]);
                        $result = $this->removeNestedLevel($arrItem, $result);
                    }
                }
                array_unshift($result, $arrMultiLevel);
            }
        }
        return $result;
    }

    /**
     * Debug data function
     * @param mixed ...$params
     * @return void
     * @author HuuLe
     */
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
}
