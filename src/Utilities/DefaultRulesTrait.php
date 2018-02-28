<?php
namespace Xamplifier\Etl\Utilities;

trait DefaultRulesTrait
{
    protected $typeRules = [
        'string' => 'string',
        'date' => 'date',
        'email' => 'email',
        'integer' => 'integer',
    ];

    /**
     * Default validation rules.
     *
     * It provides simple set of rules based on type so the rules do not need to be
     * set for every field.
     *
     * @param  string $type Field type
     * @return array|null   Corresponding rule(s)
     */
    public function defaultRules(string $type)
    {
        if (!array_has($this->typeRules, $type)) {
            return;
        }

        return array_get($this->typeRules, $type);
    }
}
