<?php
namespace Xamplifier\Etl;

use Xamplifier\Etl\Loader\Loader;
use Xamplifier\Etl\Extractor\Factory;
use Xamplifier\Etl\Contract\EtlModel;
use Xamplifier\Etl\Transformer\Transformer;

/**
 * Initiates the library.
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

        $transformer = new Transformer($data, $config);
        $entities = $transformer->getTransformerData();

        $this->status['rows'] = count($data->data);
        $this->status['keys'] = count($data->keys);
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
