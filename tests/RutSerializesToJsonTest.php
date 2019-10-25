<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use DarkGhostHunter\RutUtils\Rut;

class RutSerializesToJsonTest extends TestCase
{
    public function testDefaultStringTrue()
    {
        $rut = new Rut(18300252, 'k');

        $this->assertFalse($rut->shouldJsonAsArray());
        $this->assertEquals('"18.300.252-K"', $rut->toJson());
    }

    public function testSerializesAllJson()
    {
        Rut::allJsonAsString();

        $rut = new Rut(18300252, 'k');

        Rut::allJsonAsArray();

        $this->assertJson($rut->toJson());
        $this->assertEquals('{"num":18300252,"vd":"K"}', $rut->toJson());

        Rut::allJsonAsString();

        $this->assertJson($rut->toJson());
        $this->assertEquals('"18.300.252-K"', $rut->toJson());
    }

    public function testSerializesJson()
    {
        Rut::allJsonAsString();

        $rut = new Rut(18300252, 'k');

        $rut->jsonAsArray();

        $this->assertJson($rut->toJson());
        $this->assertEquals('{"num":18300252,"vd":"K"}', $rut->toJson());

        $rut->jsonAsString();

        $this->assertJson($rut->toJson());
        $this->assertEquals('"18.300.252-K"', $rut->toJson());
    }
}
