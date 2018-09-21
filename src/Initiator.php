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

    public function __construct($source, array $config = [])
    {

        // $this->checkModels($config['models']);
        $ext = gettype($source);
        if ($ext != 'array') {
            $ext = pathinfo($source, PATHINFO_EXTENSION);
        }

        $this->extractor = Factory::factory($ext, $source);

        $data = $this->extractor->getData();

        $transformer = new Transformer($data, $config);

        $entities = $transformer->getTransformerData();
    
        $this->status['rows'] = count($data->data);
        $this->status['keys'] = count($data->keys);
        $this->status['entities'] = $entities->count();

        new Loader($entities, $config);
    }

    /**
     * Given models should be checked before starting extracting
     *
     * @param  array  $models Eloquent models
     */
    protected function checkModels(array $models = [])
    {
        if (empty($models) || (empty($models['pass']) && empty($models['fail']))) {
            throw new \InvalidArgumentException("Models were not found");
        }

        $all = array_merge($models['pass'], $models['fail']);
        $length = sizeof($all) - 1;

        while ($length >= 0) {
            $class = $all[$length];

            $interfaces = class_implements($class);

            if (!in_array(EtlModel::class, $interfaces)) {
                throw new \InvalidArgumentException($all[$length] . " does not implement " . EtlModel::class);
            }

            $length--;
        }
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
