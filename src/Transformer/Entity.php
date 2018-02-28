<?php
namespace Xamplifier\Etl\Transformer;

use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Validator;

class Entity
{
    /**
     * Holds the value name
     * @var string
     */
    const FIELD_VALUE = 'value';

    /**
     * Holds the variation name
     * @var string
     */
    const FIELD_VARIATION = 'variation';

    /**
     * Key string
     * @var string
     */
    const FIELD_KEY = 'key';

    /**
     * Key string
     * @var string
     */
    const FIELD_TYPE = 'type';

    /**
     * CSV row
     * @var array
     */
    protected $row;

    /**
     * Validator erros
     */
    protected $errors;

    /**
     * Returns the property.
     *
     * @param  string $prop Name of the property
     * @return array        Property with its values
     */
    public function getProperty($prop)
    {
        return $this->$prop;
    }

    /**
     * Alias of getPropertyValue
     *
     * @see self::getPropertyValue()
     * @return string|bool
     */
    public function get($prop = null)
    {
        return $this->getPropertyValue($prop);
    }

    /**
     * Returns property's value.
     *
     * @throws \InvalidArgumentException When The type of parameter is not a string.
     *                                        When the property is not valid.
     * @param  string $prop Property value
     * @return string|bool
     */
    public function getPropertyValue($prop)
    {
        if (!is_string($prop)) {
            $message = sprintf(
                'Invalid argument type, it should be a string. %s given',
                gettype($prop)
            );
            throw new \InvalidArgumentException($message);
        }

        if (!$this->isValid($prop)) {
            $message = 'Cannot recognize the property ' . $prop;
            throw new \InvalidArgumentException($message);
        }

        return $this->$prop[self::FIELD_VALUE];
    }

    /**
     * Get properties value.
     *
     * This function takes multiple props and return their value in assoc array.
     * Optionally, it removes the properties with empty values.
     *
     * @param  array   $props Properties to get their value.
     * @param  boolean $empty Empty flag
     * @return array          Associative array properties values
     */
    public function getPropertiesValue(array $props, $empty = true)
    {
        $result = [];
        foreach ($props as $prop) {
            $value = $this->getPropertyValue($prop);
            if ($empty && !$value) {
                continue;
            }
            $result[$prop] = $value;
        }

        return $result;
    }

    public function getPropertyVariation($prop)
    {
        return $this->$prop[self::FIELD_VARIATION];
    }

    /**
     * Sets the property
     *
     * Creates a new property with the following options
     * - name
     * - value
     * - variation
     * - type
     *
     * @todo: too many parameters here, array? varidatic maybe?
     * @param string $prop      Name
     * @param string $value     Value
     * @param string $variation Variation
     * @param string $type      Type
     */
    public function setProperty($prop = null, $value = null, $variation = null, $type = 'string')
    {
        $this->$prop[self::FIELD_KEY] = $prop;
        $this->$prop[self::FIELD_VALUE] = $value;
        $this->$prop[self::FIELD_VARIATION] = $variation;
        $this->$prop[self::FIELD_TYPE] = $type;
    }

    /**
     * Sets the property's variation.
     *
     * This function also logs a warning for the property with no variation.
     *
     * @param string $prop  Property name
     * @param string $variation Property's variation
     * @return false|void
     */
    public function setPropertyVariation($prop, $variation = null)
    {
        $this->$prop[self::FIELD_VARIATION] = $variation;
    }

    /**
     * Sets the property value.
     *
     * This function also logs a warning for the property with no value.
     *
     * @param string $prop  Property name
     * @param string $value Property value
     * @return void|false
     */
    public function setPropertyValue($prop, $value)
    {
        $this->$prop[self::FIELD_VALUE] = $value;
    }

    /**
     * Check the given property if it is valid.
     *
     * A valid property MUST be array and MUST have
     * static::FIELD_KEY as a key in it.
     *
     * @param  string  $prop Given property
     * @return boolean       True if yes
     */
    public function isValid($prop)
    {
        $props = get_object_vars($this);
        $validProps = array_column($props, self::FIELD_KEY);

        return in_array($prop, $validProps);
    }

    /**
     * Return all properties of the entity.
     *
     * @param  boolean $empty Flag when true, it removes the empty ones
     * @return array          Entity's properties
     */
    public function getProperties($empty = false)
    {
        $result = [];
        $props = array_column(get_object_vars($this), self::FIELD_KEY);
        foreach ($props as $prop) {
            $value = $this->getPropertyValue($prop);
            if ($empty && strlen(trim($value)) <= 0) {
                continue;
            }
            $result[$prop] = $value;
        }

        return $result;
    }

    /**
     * Set the CSV row.
     *
     * Options:
     * - 'empty': To remove empty elements
     *
     * @param array $row     Assoc array header:value
     * @param array $options Array of options. see doc block.
     */
    public function setRow($row, array $options = [])
    {
        $options += ['empty' => true];
        //exclude empty ones.
        if ($options['empty']) {
            foreach ($row as $column => $value) {
                if (strlen(trim($value)) > 0) {
                    continue;
                }
                unset($row[$column]);
            }
        }

        $this->row = $row;
    }

    /**
     * Returns the CSV row.
     *
     * @return array
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * Check if entity has errors
     *
     * @return boolean Yes, if it is true.
     */
    public function hasErrors()
    {
        return $this->getErrors() ? true : false;
    }

    /**
     * Validator errors
     *
     * @param Validator $v Set Validation errors
     */
    public function setErrors(Validator $v)
    {
        $this->errors = $v->errors();
    }

    /**
     * Get Validator errors
     *
     * @return $errors
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
