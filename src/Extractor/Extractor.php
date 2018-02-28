<?php
namespace Xamplifier\Etl\Extractor;

abstract class Extractor
{

    public $result;

    abstract public function setData();

    abstract public function getData();
}
