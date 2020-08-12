<?php
/**
 * File ResponseParser.php
 * Created by HuuLe at 12/08/2020 09:23
 */

namespace HuuLe\AmazonSDK;

trait ResponseParser
{
    /**
     * To Array function
     * @param array|object $dataList
     * @return array
     * @author HuuLe
     */
    public function toArray($dataList)
    {
        $result = [];
        if (is_object($dataList))
            $result = $this->parseItem($dataList);
        elseif (is_array($dataList)) {
            foreach ($dataList as $item) {
                $result[] = $this->parseItem($item);
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
     * @param $item
     * @return array
     * @author HuuLe
     */
    public function parseItem($item)
    {
        $result = [];
        $methods = get_class_methods($item);
        foreach ($methods as $method) {
            if (strpos($method, 'isSet') !== false && method_exists($item, $method)) {
                $field = str_replace('isSet', '', $method);
                $fieldValue = $item->{$method}() ? $item->{'get' . $field}() : '';
                if ($fieldValue instanceof \DateTime)
                    $fieldValue = $fieldValue->format(Constant::DEFAULT_DATE_FORMAT);
                $result[$field] = $fieldValue;
            }
        }
        return $result;
    }
}
