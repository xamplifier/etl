<?php
namespace Xamplifier\Etl\Extractor\Contracts;

interface Extractor
{
    public function setData();

    public function getData();
}