<?php
namespace Xamplifier\Etl\Utilities;

use Illuminate\Support\Facades\Log;

/**
 * Inflector class helps in finding the different variation of a word.
 */
class Inflector
{
    const VARIATIONS = 'variations';

    /**
     * Provider word variations from the given word.
     *
     * @param  string $word Just a word
     * @return array        The variations
     */
    public static function variationsOf($word, array $variations = [])
    {
        $result = [];

        if (!array_key_exists($word, $variations)) {
            throw new \InvalidArgumentException('The word "' . $word . '" has no variations.');
        }

        if (isset($variations[$word][self::VARIATIONS])) {
            $result = array_merge($result, $variations[$word][self::VARIATIONS]);
        }

        return $result;
    }
}
