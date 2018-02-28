<?php
namespace Xamplifier\Etl;

use Xamplifier\Etl\Extractor\Factory;
use Xamplifier\Etl\Transformer\Transformer;
use Xamplifier\Etl\Loader\Loader;

/**
 * Worker class acts as middle man between the ETL class.
 */
class Initiator
{
    /**
     * Instance of extractor
     * @var object
     */
    protected $extractor;

    protected $status;

    public function __construct($filename, array $config = [])
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->extractor = Factory::factory($ext, $filename);
        $data = $this->extractor->getData();
        $this->status['rows'] = count($data->data);
        $this->status['keys'] = count($data->keys);
        $transformer = new Transformer($data, $config);
        $entities = $transformer->getTransformerData();
        $this->status['entities'] = $entities->count();
        new Loader($entities, $config);
    }

    public function status()
    {
        $stats = ['rows', 'keys'];
        foreach ($stats as $s) {
            if (!isset($this->status[$s])) {
                $this->status[$s] = 0;
            }
        }

        return $this->status;
    }
}
