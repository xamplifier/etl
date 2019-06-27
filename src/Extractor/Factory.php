<?php
namespace Xamplifier\Etl\Extractor;

/**
 * Factory class for determining the type of object.
 * This class utilizes Static Factory Pattern.
 */
final class Factory
{
    /**
     * Determines which object to create based on the given type.
     *
     * @param  string $type Object type
     * @return Csv|Json|Xml|Array
     */
    public static function factory(string $type, $source)
    {
        switch ($type) {
            case 'csv':

                return new Csv($source);
            case 'json':

                return new Json($source);
            case 'xml':

                return new Xml($source);
            case 'array':

                return new Arr($source);
            default:

                throw new \InvalidArgumentException('Unknown format given');
        }
    }
}
