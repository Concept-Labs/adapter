<?php
namespace Cl\Adapter\String\Csv;

use Cl\Adapter\Csv\Exception\CsvAdapterException;


class CsvAdapter
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
    public static function toCsv(array $data, string $separator = ',', string $enclosure = '"', string $escape = '\\'): string
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
     * Converts a CSV string to an array
     *
     * @param string $csvString The array to convert
     * @param bool   $headers   True to fetch headers and use for array keys
     * @param string $separator The CSV field separator
     * @param string $enclosure The CSV field enclosure
     * @param string $escape    The CSV escape character
     *
     * @return array
     */
    public static function toArray(string $csvString, bool $headers = true, string $separator = ',', string $enclosure = '"', string $escape = "\\"): array
    {
        $array = [];
        $rows = explode(PHP_EOL, $csvString);

        foreach ($rows as $row) {
            if (!empty($row)) {
                $array[] = str_getcsv(trim($row), $separator, $enclosure, $escape);
            }
        }

        if (empty($array)) {
            throw new CsvAdapterException("Converted array is empty");
        }

        if ($headers) {
            $headers = array_shift($array);
            $array = array_map(fn($row) => array_combine($headers, $row), $array);
        }

        return $array;
    }
}