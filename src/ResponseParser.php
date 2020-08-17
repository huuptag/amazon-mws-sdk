<?php
/**
 * File ResponseParser.php
 * Created by HuuLe at 12/08/2020 09:23
 */

namespace HuuLe\AmazonSDK;

use DOMElement;
use DOMNode;

trait_exists(Helpers::class, true);

trait ResponseParser
{
    /**
     * To Array function
     * @param array|object $dataList
     * @param bool $skipEmpty
     * @return array
     * @author HuuLe
     */
    public function toArray($dataList, $skipEmpty = false)
    {
        $result = [];
        if (is_object($dataList))
            $result = $this->parseItem($dataList, $skipEmpty);
        elseif (is_array($dataList)) {
            foreach ($dataList as $item) {
                $result[] = $this->parseItem($item, $skipEmpty);
            }
        }
        return $result;
    }

    /**
     * To JSON function
     * @param array $dataList
     * @param int $options
     * @return string
     * @author HuuLe
     */
    public function toJSON($dataList, $options = null)
    {
        return json_encode($this->toArray($dataList), $options);
    }

    /**
     * Parse Item function
     * @param object|mixed $item
     * @param bool $skipEmpty
     * @return array
     * @author HuuLe
     */
    public function parseItem($item, $skipEmpty = false)
    {
        $result = [];
        // Check object type DOMElement
        if ($item instanceof \DOMElement)
            $result = $this->convertDOMElementToArray($item);
        else {
            $methods = get_class_methods($item);
            foreach ($methods as $method) {
                if (strpos($method, 'isSet') !== false && method_exists($item, $method)) {
                    $field = str_replace('isSet', '', $method);
                    $singularField = substr($field, 0, strlen($field) - 1);
                    $getMethod = 'get' . $field;
                    // Check skip empty with isset method
                    if ($skipEmpty && !$item->{$method}())
                        continue;
                    // Run normally
                    if (method_exists($item, $getMethod)) {
                        $fieldValue = $item->{$getMethod}();
                        if (is_array($fieldValue) && !$this->isAssoc($fieldValue) && $this->isObject($fieldValue[0])) {
                            $subResult = [];
                            foreach ($fieldValue as $childItem) {
                                $subResult[] = $this->parseItem($childItem, $field);
                            }
                            $result[$field] = $subResult;
                        } else
                            // Check value type object
                            if ($this->isObject($fieldValue) && !($fieldValue instanceof \DateTime)) {
                                if ($fieldValue instanceof \DOMElement) {
                                    $result[$field] = $this->convertDOMElementToArray($fieldValue);
                                } else {
                                    // Remove singular sub result when empty
                                    $subResult = $this->parseItem($fieldValue, $field);
                                    if (is_array($subResult) && count($subResult) == 1 && isset($subResult[$singularField]))
                                        $subResult = $subResult[$singularField];
                                    $result[$field] = $subResult;
                                }
                            } else {
                                // Check datetime value
                                if ($fieldValue instanceof \DateTime)
                                    $fieldValue = $fieldValue->format($this->getDefaultDateFormat());
                                // Convert to readable datetime format
                                if (is_string($fieldValue) && $this->checkFormattedDateTime($fieldValue))
                                    $fieldValue = $this->convertDateTimeDefaultFormat($fieldValue);
                                $result[$field] = $fieldValue;
                            }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Convert XML To Array function
     * @param string $xmlString
     * @return array
     * @author HuuLe
     */
    public
    function convertXMLToArray($xmlString)
    {
        $result = [];
        if ($xmlString) {
            $xml = simplexml_load_string($xmlString, "SimpleXMLElement", LIBXML_NOCDATA);
            $result = json_decode(json_encode($xml), true);
        }
        return $result;
    }

    /**
     * Convert XML To JSON function
     * @param string $xmlString
     * @param int $options
     * @return false|string
     * @author HuuLe
     */
    public
    function convertXMLToJSON($xmlString, $options = null)
    {
        return json_encode($this->convertXMLToArray($xmlString), $options);
    }

    /**
     * Convert DOM Element To Array function
     * @param DOMElement|DOMNode $domElement
     * @return array
     * @author HuuLe
     */
    function convertDOMElementToArray($domElement)
    {
        $result = [];
        if ($domElement->hasChildNodes()) {
            foreach (range(0, $domElement->childNodes->length - 1) as $index) {
                $childNode = $domElement->childNodes->item($index);
                if ($childNode->hasChildNodes() && $childNode->childNodes->length > 1)
                    $result[$childNode->localName] = $this->convertDOMElementToArray($childNode);
                else
                    $result[$childNode->localName] = $childNode->nodeValue;
            }
        }
        return $result;
    }
}
