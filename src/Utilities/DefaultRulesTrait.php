<?php
namespace Xamplifier\Etl\Utilities;

trait DefaultRulesTrait
{
    protected $typeRules = [
        'string' => 'string',
        'date' => 'date',
        'email' => 'email',
        'integer' => 'integer',
        'array' => 'array',
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
        return  $this->typeRules[$type] ?? null;
    }
}
