<?php
namespace Xamplifier\Etl\Extractor\Contracts;

interface Extractor
{
    public function setData() :void;

    public function getData();
}
