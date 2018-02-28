<?php
namespace Xamplifier\Etl\Extractor;

use \RecursiveIteratorIterator;
use \RecursiveArrayIterator;

/**
 * JSON parser
 */
class Json
{
    //JSON error messages
    protected static $_messages = [
       JSON_ERROR_NONE => 'No error has occurred',
       JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
       JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
       JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
       JSON_ERROR_SYNTAX => 'Syntax error',
       JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded'
    ];

    public function __construct($filename = null)
    {
        $filename = file_get_contents($filename);
        $this->setData($filename);
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

    public function setData($filename)
    {
        if (!$this->validateJson($filename)) {
            $error = static::$_messages[json_last_error()];

            throw new \RunTimeException($error);
        }

        $this->result = new \StdClass;
        $this->result->keys = $this->getKeys($filename);
        $this->result->data = $this->getRowsWithKeys($filename);
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
    public function getKeys($filename)
    {
        $keys = [];
        $assoc = json_decode($filename, true);
        foreach ($assoc as $index => $array) {
            foreach ($array as $key => $value) {
                $keys[] = $key;
            }
        }

        return array_unique($keys);
    }

    /**
     * Return JSON keys and objects.
     *
     * @return array
     */
    public function getRowsWithKeys($filename)
    {
        $data = [];
        $assoc = json_decode($filename, true);
        foreach ($assoc as $index => $array) {
            $data[] = $array;
        }
        return $data;
    }
}
