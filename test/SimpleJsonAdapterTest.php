<?php
namespace Cl\Adapter\String\Json;

use Cl\Adapter\String\Json\Exception\JsonAdapterException;
use PHPUnit\Framework\TestCase;

/**
 * @covers Cl\Adapter\String\Json\JsonAdapter 
 */
class SimpleJsonAdapterTest extends TestCase
{
    public function testToJson()
    {
        $data = ['key' => 'value', 'number' => 42, 'array' => [1, 2, 3]];
        $json = JsonAdapter::toJson($data);

        $this->assertJson($json);
        $this->assertEquals($data, json_decode($json, true));
    }

    public function testToArray()
    {
        $json = '{"key": "value", "number": 42, "array": [1, 2, 3]}';
        $array = JsonAdapter::toArray($json);

        $this->assertIsArray($array);
        $this->assertEquals(json_decode($json, true), $array);
    }

    public function testToJsonException()
    {
        $this->expectException(JsonAdapterException::class);

        // Force a JSON encoding error
        JsonAdapter::toJson("\xB1\x31");
    }

    public function testToArrayException()
    {
        $this->expectException(JsonAdapterException::class);

        // Force a JSON decoding error
        JsonAdapter::toArray('{"invalid": json}');
    }
}