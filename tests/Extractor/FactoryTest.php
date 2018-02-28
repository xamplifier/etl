<?php
namespace Xamplifier\Etl\Test\Extractor;

use PHPUnit\Framework\TestCase;
use Xamplifier\Etl\Extractor\Csv;
use Xamplifier\Etl\Extractor\Xml;
use Xamplifier\Etl\Extractor\Json;
use Xamplifier\Etl\Extractor\Factory;

class FactoryTest extends TestCase
{
    public function test_factory_returns_correct_object()
    {
        $actual = get_class(Factory::factory('csv', __DIR__ . '/../examples/foobar.csv'));
        $expected = Csv::class;

        $this->assertEquals($expected, $actual);

        // $actual = get_class(Factory::factory('json', __DIR__ . '/../examples/foobar.json'));
        // $expected = Json::class;
        //
        // $this->assertEquals($expected, $actual);
    }
}
