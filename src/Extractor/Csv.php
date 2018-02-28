<?php
namespace Xamplifier\Etl\Extractor;

use League\Csv\Reader;

/**
 * Csv class provides helpful functions in parsing CSV files.
 */
class Csv extends Extractor
{
    /**
     * Instance of League\Csv\Reader
     * @var Object
     */
    protected $reader;

    public function __construct($filename)
    {
        $this->reader = Reader::createFromPath($filename);
        $this->setData();
    }


    public function getData()
    {
        return $this->result;
    }

    public function setData()
    {
        $this->result = new \StdClass;
        $this->result->keys = $this->getHeaders();
        $this->result->data = $this->getRowsWithKeys();
    }

    /**
     * Check if the header is valid, case sensitive comparison.
     *
     * @param  string  $header Name of the header
     * @return boolean         True if the header is found in the CSV headers.
     */
    public function isValidHeader($header)
    {
        return in_array($this->getHeaders(), $header);
    }

    /**
     * Return CSV headers.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->reader->fetchOne();
    }

    public function getRows($excludeHeaders = true)
    {
        if ($excludeHeaders) {
            $this->reader->setOffset(1);
        }

        return iterator_to_array($this->reader->fetchAll());
    }

    public function getRowsWithKeys($excludeHeaders = true)
    {
        if ($excludeHeaders) {
            $this->reader->setOffset();
        }

        return iterator_to_array($this->reader->fetchAssoc());
    }
}
