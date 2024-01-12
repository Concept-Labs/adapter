<?php

namespace Cl\Adapter\String\Csv;

use Cl\Adapter\String\Csv\Exception\CsvAdapterException;

class CsvAdapter
{
    /**
     * Converts an array to a CSV string
     *
     * @param array  $data      The array to convert
     * @param string $separator The CSV field separator
     * @param string $enclosure The CSV field enclosure
     *
     * @return string
     * @throws CsvAdapterException
     */
    public static function toCsv(array $data, string $separator = ',', string $enclosure = '"'): string
    {
        $output = fopen('php://temp', 'w+');

        if ($output === false) {
            throw new CsvAdapterException('Failed to open temporary file for CSV output');
        }

        try {
            foreach ($data as $row) {
                if (fputcsv($output, $row, $separator, $enclosure) === false) {
                    throw new CsvAdapterException('Error writing to CSV');
                }
            }

            rewind($output);

            $csvString = stream_get_contents($output);
            if ($csvString === false) {
                throw new CsvAdapterException('Error reading CSV content');
            }

            return $csvString;
        } finally {
            fclose($output);
        }
    }

    /**
     * Converts a CSV string to an array
     *
     * @param string $csvString The array to convert
     * @param bool   $headers   True to fetch headers and use for array keys
     * @param string $separator The CSV field separator
     * @param string $enclosure The CSV field enclosure
     *
     * @return array
     * @throws CsvAdapterException
     */
    public static function toArray(string $csvString, bool $headers = true, string $separator = ',', string $enclosure = '"'): array
    {
        if (empty($csvString)) {
            throw new CsvAdapterException('CSV string is empty');
        }

        $array = [];
        $rows = explode(PHP_EOL, $csvString);

        foreach ($rows as $row) {
            if (!empty($row)) {
                $array[] = str_getcsv(trim($row), $separator, $enclosure);
            }
        }

        if (empty($array)) {
            throw new CsvAdapterException('Converted array is empty');
        }

        if ($headers) {
            $headers = array_shift($array);
            $array = array_map(
                static function (array $row) use ($headers) {
                    return array_combine($headers, $row);
                },
                $array
            );
        }

        return $array;
    }
}
