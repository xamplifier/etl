<?php
namespace Xamplifier\Etl\Extractor;

use \stdClass;
use RuntimeException;

/**
 * Array parser
 */
class Arr
{
    protected $result;

    public function __construct($source = null)
    {
        $this->result = new stdClass;
        $this->setData($source);
    }

    public function setData($source) :void
    {
        if (empty($source)) {
            throw new RuntimeException('The array is empty');
        }

        $this->result->data = $this->getRowsWithKeys($source);
        $this->result->keys = $this->getKeys($source);
    }

    public function getData() :stdClass
    {
        return $this->result;
    }


    /**
     * Return JSON keys.
     *
     * @return array
     */
    public function getKeys($source)
    {
        $keys = [];
        $maxCountKeys = 0;

        array_walk($this->result->data, function ($item) use (&$keys, &$maxCountKeys) {
            if (count($item) > $maxCountKeys) {
                $maxCountKeys = count($item);
                $keys = array_keys($item);
            }
        });

        return $keys;
    }


    /**
     * Return JSON keys and objects.
     *
     * @return array
     */
    public function getRowsWithKeys($source)
    {
        $data = [];

        foreach ($source as $index => $array) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    unset($array[$key]);
                    $keys = array_keys($value);
                    foreach ($keys as $k) {
                        $newKey = $key . '-' . $k;
                        $array[$newKey] = $value[$k];
                    }
                }
            }

            $data[] = $array;
        }

        return $data;
    }
}
