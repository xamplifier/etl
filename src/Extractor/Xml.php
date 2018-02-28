<?php
namespace Xamplifier\Etl\Extractor;

/**
 * XML parser
 */
class Xml
{
    public function __construct($filename = nul)
    {
        $file = file_get_contents($filename);
    }

    public function setData()
    {
        //code omitted
    }

    public function getData()
    {
        //code omitted
    }
}
