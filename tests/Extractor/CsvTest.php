<?php
namespace Xamplifier\Etl\Test\Extractor;

use PHPUnit\Framework\TestCase;
use Xamplifier\Etl\Extractor\Csv;

class CsvTest extends TestCase
{
    public function test_get_headers()
    {
        $file = __DIR__ . '/../examples/foobar.csv';
        $extractor = new Csv($file);
        $actual = $extractor->getHeaders();
        $expected = ['email', 'name', 'usernam'];

        $this->assertEquals($expected, $actual);
    }
}
