<?php

use PHPUnit\Framework\TestCase;
use Cl\Adapter\Csv\CsvAdapter;
use Cl\Adapter\Csv\Exception\CsvAdapterException;

/**
 * @covers Cl\Config\DataProvider\File\Json\JsonFileDataProvider
 */
class CsvAdapterTest extends TestCase
{
    public function testToCsv()
    {
        $data = [
            ['Name', 'Age', 'City'],
            ['John', 25, 'New York'],
            ['Jane', 30, 'San Francisco'],
        ];

        $expectedCsv = "Name,Age,City\nJohn,25,\"New York\"\nJane,30,\"San Francisco\"\n";

        $csvString = CsvAdapter::toCsv($data);

        $this->assertEquals($expectedCsv, $csvString);
    }

    public function testToArrayWithHeaders()
    {
        $csvString = "Name,Age,City\nJohn,25,\"New York\"\nJane,30,\"San Francisco\"\n";

        $expectedArray = [
            ['Name' => 'John', 'Age' => 25, 'City' => 'New York'],
            ['Name' => 'Jane', 'Age' => 30, 'City' => 'San Francisco'],
        ];

        $array = CsvAdapter::toArray($csvString, true);

        $this->assertEquals($expectedArray, $array);
    }

    public function testToArrayWithoutHeaders()
    {
        $csvString = "John,25,\"New York\"\nJane,30,\"San Francisco\"\n";

        $expectedArray = [
            ['John', 25, 'New York'],
            ['Jane', 30, 'San Francisco'],
        ];

        $array = CsvAdapter::toArray($csvString, false);

        $this->assertEquals($expectedArray, $array);
    }

    public function testToArrayWithEmptyRows()
    {
        $csvString = "John,25,\"New York\"\n\nJane,30,\"San Francisco\"\n";

        $expectedArray = [
            ['John', 25, 'New York'],
            ['Jane', 30, 'San Francisco'],
        ];

        $array = CsvAdapter::toArray($csvString, false);

        $this->assertEquals($expectedArray, $array);
    }

    public function testToArrayInvalidCsv()
    {
        $this->expectException(CsvAdapterException::class);

        $csvString = "";

        CsvAdapter::toArray($csvString);
    }
}