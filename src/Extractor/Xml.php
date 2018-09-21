<?php
namespace Xamplifier\Etl\Extractor;

use \stdClass;

/**
 * XML parser
 */
class Xml
{
    protected $result;

    public function __construct(string $filename = nul)
    {
        $this->setData();
        $this->result = new stdClass;
        $filename = file_get_contents($filename);
    }

    public function setData() :void
    {
        //code omitted
    }

    public function getData() :stdClass
    {
        //code omitted
    }
}
