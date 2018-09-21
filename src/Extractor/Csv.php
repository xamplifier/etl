<?php
namespace Xamplifier\Etl\Extractor;

use League\Csv\Reader;
use Xamplifier\Etl\Extractor\Contracts\Extractor;

/**
 * Csv class provides helpful functions in parsing CSV files.
 */
class Csv implements Extractor
{
    /**
     * Instance of League\Csv\Reader
     * @var Object
     */
    protected $csv;

    /**
     * Holding the fetched data
     * @var Object
     */
    protected $data;

    public function __construct($filename)
    {
        $this->csv = Reader::createFromPath($filename, 'r');
        $this->data = new \StdClass;

        $this->setData();
    }

    /**
     * Returns the fetched data
     *
     * @return \StdClass
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Creates an object holdings the CSV data
     *
     * @return void
     */
    public function setData() :void
    {
        $this->data->keys = $this->getHeaders();
        $this->data->data = $this->getRows();
    }

    /**
     * Return CSV headers.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->csv->getHeader();
    }

    /**
     * Returns all CSV rows
     *
     * @param  boolean $excludeHeaders Optionally keep headers
     * @return array
     */
    public function getRows($excludeHeaders = true)
    {
        if ($excludeHeaders) {
            $this->csv->setHeaderOffset(0);
        }

        return iterator_to_array($this->csv->getRecords());
    }
}
