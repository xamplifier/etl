<?php
namespace Xamplifier\Etl\Loader;

use \RuntimeException;

class Loader
{
    protected $entities;

    protected $models;

    public function __construct(\SplObjectStorage $entities, array $config = [])
    {
        $this->entities = $entities;

        if (!$this->entities->count()) {
            return false;
        }
        $this->setModels($config['models']);
        $this->process();
    }

    protected function process()
    {
        $this->entities->rewind();

        while ($this->entities->valid()) {
            $entity = $this->entities->current();
            $models = $this->getModels();

            if (!$entity->hasErrors()) {
                foreach ($models['pass'] as $m) {
                    $obj = new $m;
                    $obj->etl($entity);
                }
            } else {
                foreach ($models['fail'] as $m) {
                    $obj = new $m;
                    $obj->etl($entity);
                }
            }
            $this->entities->next();
        }
    }

    /**
     * Set Eloquent Models from config
     *
     * @return void
     */
    public function setModels(array $models = [])
    {
        if (!$models) {
            throw new RuntimeException('Please enter models in the \'etl\' config to proceed');
        }

        if (!is_array($models)) {
            throw new RuntimeException('Models SHOULD be an array.');
        }

        if (!isset($models['pass']) && !isset($models['fail'])) {
            throw new RuntimeException('Please define in model either pass or fail models, or both.');
        }

        $this->models = $models;
    }

    /**
     * Returns the models which the entities will be sent.
     *
     * @return array Eloquent Models
     */
    public function getModels()
    {
        return $this->models;
    }
}
