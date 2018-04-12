<?php
namespace Xamplifier\Etl\Utilities;

use Validator;
use \Xamplifier\Etl\Transformer\Entity;

class EntityValidator
{
    use DefaultRulesTrait;

    /**
     * Fields defined in the etl config
     *
     * @var array
     */
    protected $fields;

    /**
     * Validation rules
     * @var array
     */
    protected $rules = [];

    public function __construct($fields)
    {
        $this->fields = $fields;

        if (!$this->fields) {
            $message = 'Cannot instantiate validator without providing validation rules';
            throw new \RuntimeException($message);
        }
        $this->buildRules();
    }

    /**
     * Build validation rules
     *
     * @return array rules
     */
    protected function buildRules()
    {
        foreach ($this->fields as $fieldName => $value) {
            $type = array_get($value, 'type') ?: 'string';
            $rules = array_has($value, 'rules')
                ? $value['rules']
                : $this->defaultRules($type);
            $this->rules[$fieldName] = $rules;
        }

        return $this->rules;
    }

    /**
     * Validate given entity
     *
     * @see \Xamplifier\Etl\Transformer\Entity::setErrors
     * @param  Entity $entity Entity to be validated.
     * @return Entity         In case there are errors, they are set on the Entity
     */
    public function validate(Entity $entity)
    {
        $data = $entity->getProperties(true);
        $validator = Validator::make($data, $this->rules);

        if ($validator->fails()) {
            $entity->setErrors($validator);
        }

        return $entity;
    }
}
