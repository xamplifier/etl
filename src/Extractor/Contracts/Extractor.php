<?php
namespace Xamplifier\Etl\Extractor\Contracts;

use \stdClass;

interface Extractor
{
    public function setData() :void;

    public function getData() :stdClass;
}
