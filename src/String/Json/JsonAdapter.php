<?php
namespace Cl\Adapter\String\Json;

use Cl\Adapter\Json\Exception\JsonAdapterException;

class JsonAdapter
{
    /**
     * Converts an array to a CSV string
     *
     * @param mixed    $data        The array to convert
     * @param int      $flags 
     * @param int|null $depth 
     * @param bool     $throwOnFail Throws an exception on convertion fail
     *
     * @return string
     */
    public static function toJson(mixed $data, int|null $flags = JSON_PRETTY_PRINT|JSON_THROW_ON_ERROR, int|null $depth = 512, bool $throwOnFail = true): string
    {
        $json = json_encode($data, $flags, $depth);
        if ($throwOnFail && !strlen($json)) {
            throw new JsonAdapterException("Encoded JSON is empty");
        }

        return $json;
    }

    /**
     * Converts JSON to array
     *
     * @param string    $json        The JSON string to be converted
     * @param bool|null $associative  
     * @param int       $depth 
     * @param int       $flags 
     * @param bool      $throwOnFail Throws an exception on convertion fail
     * 
     * @return array
     * @throws JsonAdapterException
     */
    public static function toArray(string $json, bool|null $associative = true, int|null $depth = 512, int $flags = JSON_THROW_ON_ERROR, bool $throwOnFail = true): array
    {
        $array = json_decode($json, $associative, $depth, $flags);
        if ($throwOnFail && (!$array || empty($array))) {
            throw new JsonAdapterException("Decoded array is empty");
        }

        return $array;
    }
}