<?php
namespace Xamplifier\Etl\Extractor;

use \stdClass;
use League\Csv\Reader;
use Xamplifier\Etl\Extractor\Contracts\Extractor;

/**
 * Csv class provides helpful functions in parsing CSV files.
 */
class Csv implements Extractor
{
    /**
     * @var Reader
     */
    protected $csv;

    /**
     * Holding the fetched data
     * @var stdClass
     */
    protected $result;

    public function __construct($filename)
    {
        $this->csv = Reader::createFromPath($filename, 'r');
        $this->result = new stdClass;

        $this->setData();
    }

    /**
     * Returns the fetched data
     *
     * @return stdClass
     */
    public function getData() :stdClass
    {
        return $this->result;
    }

    /**
     * Creates an object holdings the CSV data
     *
     * @return void
     */
    public function setData() :void
    {
        $this->result->keys = $this->getHeaders();
        $this->result->data = $this->getRows();
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
