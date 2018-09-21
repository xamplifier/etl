<?php
namespace Xamplifier\Etl\Extractor;

use Illuminate\Support\Arr as LaravelArr;

/**
 * JSON parser
 */
class Arr
{
    public function __construct($source = null)
    {
        $this->setData($source);
    }


    /**
    * Validates Json file and returns error messages
    *
    */
    public function validateJson(...$args)
    {
        json_decode(...$args);
        return static::$_messages[json_last_error()];
    }

    public function setData($source)
    {
        if (empty($source)) {
            throw new \RunTimeException('The array is empty');
        }


        $this->result = new \StdClass;
        $this->result->data = $this->getRowsWithKeys($source);
        $this->result->keys = $this->getKeys($source);
    }

    public function getData()
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
        $maxCountKeys = 0;
        $keys = [];

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
