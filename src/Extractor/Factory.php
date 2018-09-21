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
     * @return CsvParser|JsonParser|XmlParser|ArrayParser
     */
    public static function factory(string $type, $source)
    {
        switch ($type) {
            case 'csv':
                return new Csv($source);

                break;
            case 'json':
                return new Json($source);

                break;
            case 'xml':
                return new Xml($source);

                break;
            case 'array':
                return new Arr($source);

                break;
            default:
                throw new \InvalidArgumentException('Unknown format given');
                break;
        }
    }
}
