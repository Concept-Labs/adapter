<?php
namespace Cl\Adapter\String\Json;

use Cl\Adapter\Json\Exception\JsonAdapterException;

class JsonAdapter
{
    /**
     * Converts an array to a CSV string
     *
     * @param array  $data      The array to convert
     * @param string $separator The CSV field separator
     * @param string $enclosure The CSV field enclosure
     * @param string $escape    The CSV escape character
     *
     * @return string
     */
    public static function toJson(array $data, string $separator = ',', string $enclosure = '"', string $escape = '\\'): string
    {
        $output = fopen('php://temp', 'w+');

        foreach ($data as $row) {
            fputcsv($output, $row, $separator, $enclosure, $escape);
        }

        rewind($output);

        $csvString = stream_get_contents($output);

        fclose($output);

        return $csvString;
    }

    /**
     * Converts JSON to array
     *
     * @param string    $json        The JSON string to be converted
     * @param bool|null $associative  
     * @param int       $depth 
     * @param int       $flags 
     * 
     * @return array
     * @throws JsonAdapterException
     */
    public static function toArray(string $json, bool|null $associative = true, int|null $depth = 512, int $flags = JSON_THROW_ON_ERROR): array
    {
        $array = json_decode($json, associative: $associative, depth: $depth, flags: $flags);
        if (empty($array)) {
            throw new JsonAdapterException("Decoded array is empty");
        }

        return $array;
    }
}