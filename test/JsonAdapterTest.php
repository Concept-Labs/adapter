<?php

use PHPUnit\Framework\TestCase;
use Cl\Adapter\String\Json\JsonAdapter;
use Cl\Adapter\String\Json\Exception\JsonAdapterException;

/**
 * @covers Cl\Config\DataProvider\File\Json\JsonFileDataProvider
 */
class JsonAdapterTest extends TestCase
{
    public function testToJsonSuccess()
    {
        $data = ['key' => 'value'];
        $json = JsonAdapter::toJson($data);
        $this->assertJson($json);
    }

    public function testToJsonFailure()
    {
        $this->expectException(JsonAdapterException::class);
        JsonAdapter::toJson("\xB1\x31");
    }

    public function testToArraySuccess()
    {
        $json = '{"key":"value"}';
        $array = JsonAdapter::toArray($json);
        $this->assertIsArray($array);
        $this->assertEquals(['key' => 'value'], $array);
    }

    public function testToArrayFailure()
    {
        $this->expectException(JsonAdapterException::class);
        JsonAdapter::toArray('invalid json');
    }

    public function testToArrayWithNonAssociativeSuccess()
    {
        $json = '{"1":"one","2":"two","3":"three"}';
        $array = JsonAdapter::toArray($json, false);
        $object = new stdClass();
        $object->{1} = 'one';
        $object->{2} = 'two';
        $object->{3} = 'three';
        $this->assertIsObject($array);
        $this->assertEquals($object, $array);
    }

    public function testToArrayWithNonAssociativeFailure()
    {
        $this->expectException(JsonAdapterException::class);
        JsonAdapter::toArray('{"1":"one","2":"two","3":"three"', false);
    }
}