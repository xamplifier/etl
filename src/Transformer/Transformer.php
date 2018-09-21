<?php
namespace Xamplifier\Etl\Transformer;

use \RuntimeException;
use Xamplifier\Etl\Utilities\Inflector;
use Xamplifier\Etl\Utilities\EntityValidator;

class Transformer
{

    /**
     * Transformer Entity
     * @var \Xamplifier\Etl\Transformer\Entity;
     */
    protected $entity;

    /**
     * Extracted data
     * @var array
     */
    protected $extracted;

    /**
     * Storage of entities
     * @var \SplObjectStorage
     */
    protected $entities;

    /**
     * Enrich data from client config.
     * @var array
     */
    protected $enrichData;

    /**
     * Class constuctor
     */
    public function __construct($extractedData, array $config = [])
    {
        $this->extracted = $extractedData;
        $this->entities = new \SplObjectStorage;
        if (!empty($config['enrichData'])) {
            $this->enrichData = $config['enrichData'];
        }
        $this->validator = new EntityValidator($config['fields']);
        $this->transform($config);
    }

    /**
     * Transform the extracted data to entities.
     *
     * @return void
     */
    public function transform(array $config = [])
    {
        foreach ($this->extracted->data as $row) {
            $this->entity = new Entity;

            foreach ($this->getFields($config['fields']) as $field) {
                $variation =  null;

                $value = $this->getFieldValue($row, $field);
                if (!$value) {
                    list($variation, $value) = $this->getVariationAndValue($row, $field, $config);
                }
                $type = $this->getFieldType($field, $value, $config['fields']);
                $value = $this->typeCastValue($value, $type);
                $this->entity->setProperty($field, $value, $variation, $type);
            }

            $this->entity = $this->validator->validate($this->entity);
            $this->entity->setRow($row);
            $this->entities->attach($this->entity);
        }
    }

    /**
     * Typecast the value based on type.
     *
     * The supported types are:
     * 'integer',
     * 'boolean'
     * Any other type will be ignored and typecasted to string.
     *
     * @param  string $value Field value
     * @param  string $type  Field type
     * @return string        Typecasted value
     */
    protected function typeCastValue($value, $type)
    {
        if (empty($value)) {
            return $value;
        }

        switch ($type) {
            case 'array':
                return (array) $value;
            case 'integer':
                return (integer) $value;
            case 'boolean':
                return (boolean) $value;
            case 'string':
            default:
                return (string) $value;
        }
    }

    /**
     * Return the field type. Defaults to string.
     *
     * @param  string $field Field name
     * @return string        Field type
     */
    protected function getFieldType(string $field, $value, array $fields = [])
    {
        if (!empty($fields[$field]['type'])) {
            return $fields[$field]['type'];
        }

        $type = gettype($value);
        if ($type) {
            return $type;
        }

        return 'string';
    }

    /**
     * Returns the field value from the row.
     *
     * @param  array  $row       CSV row
     * @param  string $field     field name
     * @param  string $variedKey variation key
     * @return string|null       Field's value
     */
    protected function getFieldValue(array $row, string $fieldName)
    {
        $value = null;
        //Try enrich data
        if (isset($this->enrichData[$fieldName])) {
            $value = $this->enrichData[$fieldName];
        }

        //Try row data
        if (isset($row[$fieldName])) {
            $value = $row[$fieldName];
        }

        //Try row data with variation
        $caseSensitiveName = $this->getFieldCase($row, $fieldName);
        if (isset($row[$caseSensitiveName])) {
            $value = $row[$caseSensitiveName];
        }

        return $value;
    }

    /**
     * Returns the storage of entities.
     *
     * @return \SplObjectStorage
     */
    public function getTransformerData()
    {
        return $this->entities;
    }

    /**
     * Returns the properties.
     *
     * @throws \RunTimeException When the config is not being created
     * @param  $fields           Config fields
     * @return array             All the properties
     */
    protected function getFields(array $fields = [])
    {
        if (!$fields) {
            throw new RuntimeException('Please create \'etl\' config to proceed.');
        }

        return array_keys($fields);
    }

    protected function getVariationAndValue(array $row, string $fieldName, array $config = [], $overwrite = false)
    {
        $variations = Inflector::variationsOf($fieldName, $config['fields']);
        $value = $variation = null;
        foreach ($variations as $v) {
            $variation = $this->getFieldCase($row, $v);
            if (isset($row[$variation])) {
                $value = $row[$variation];
            }
            //Get out of the loop when you find a value
            if ($value && !$overwrite) {
                break;
            }
        }

        return array_map('trim', [$variation, $value]);
    }

    protected function getFieldCase($row, $fieldName)
    {
        foreach ($row as $header => $value) {
            if (strcasecmp($header, $fieldName) === 0) {
                return $header;
            }
        }

        return;
    }
}
