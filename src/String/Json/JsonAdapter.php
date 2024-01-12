<?php
namespace Cl\Adapter\String\Json;

use Cl\Adapter\String\Json\Exception\JsonAdapterException;

use JsonException;
use Exception;

class JsonAdapter
{
    /**
     * Converts an array to a JSON string
     *
     * @param mixed    $data        The data to convert
     * @param int      $flags       JSON encoding options
     * @param int|null $depth       Set the maximum depth
     * @param bool     $throwOnFail Throws an exception on conversion failure
     *
     * @return string
     * @throws JsonAdapterException
     */
    public static function toJson(mixed $data, int|null $flags = JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR, int|null $depth = 512, bool $throwOnFail = true): string
    {
        try {
            $json = json_encode($data, $flags, $depth);
            if (empty($json)) {
                throw new Exception('Encoded JSON is empty');
            }
        } catch (JsonException $e) {
            if ($throwOnFail) {
                throw new JsonAdapterException(sprintf('JSON encode: %s', $e->getMessage()), $e->getCode(), $e);
            }
        }
        return $json;
    }

    /**
     * Converts a JSON string to an array
     *
     * @param string    $json        The JSON string to convert
     * @param bool|null $associative When true, returned objects will be converted into associative arrays
     * @param int       $depth       User specified recursion depth
     * @param int       $flags       Bitmask of JSON decode options
     * @param bool      $throwOnFail Throws an exception on conversion failure
     *
     * @return array|object
     * @throws JsonAdapterException
     */
    public static function toArray(string $json, bool|null $associative = true, int|null $depth = 512, int $flags = JSON_THROW_ON_ERROR, bool $throwOnFail = true): array|object
    {
        try {
            //@TODO json_validate() for php >=8.3
            $array = json_decode($json, $associative, $depth, $flags);
            if ($array === null || $array === false || empty($array)) {
                throw new Exception("Decoded array|object is empty");
            }
        } catch (JsonException $e) {
            if ($throwOnFail) {
                    throw new JsonAdapterException(sprintf('JSON decode: %s', $e->getMessage()), $e->getCode(), $e);
            }
        }
        return $array;
    }
}