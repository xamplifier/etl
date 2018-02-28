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
     * @return object Csv|Json|Xml
     */
    public static function factory(string $type, $filename)
    {
        switch ($type) {
            case 'csv':
                return new Csv($filename);

                break;
            case 'json':
                return new Json($filename);

                break;
            case 'xml':
                return new Xml($filename);

                break;
            default:
                throw new \InvalidArgumentException('Unknown format given');
                break;
        }
    }
}
