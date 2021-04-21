<?php

namespace Tests;

use DarkGhostHunter\RutUtils\Rut;
use PHPUnit\Framework\TestCase;

class RutSerializesToJsonTest extends TestCase
{
    public function testDefaultStringTrue()
    {
        $rut = new Rut(18300252, 'k');

        static::assertFalse($rut->shouldJsonAsArray());
        static::assertEquals('"18.300.252-K"', $rut->toJson());
    }

    public function testSerializesAllJson()
    {
        Rut::allJsonAsString();

        $rut = new Rut(18300252, 'k');

        Rut::allJsonAsArray();

        static::assertJson($rut->toJson());
        static::assertEquals('{"num":18300252,"vd":"K"}', $rut->toJson());

        Rut::allJsonAsString();

        static::assertJson($rut->toJson());
        static::assertEquals('"18.300.252-K"', $rut->toJson());
    }

    public function testSerializesJson()
    {
        Rut::allJsonAsString();

        $rut = new Rut(18300252, 'k');

        $rut->jsonAsArray();

        static::assertJson($rut->toJson());
        static::assertEquals('{"num":18300252,"vd":"K"}', $rut->toJson());

        $rut->jsonAsString();

        static::assertJson($rut->toJson());
        static::assertEquals('"18.300.252-K"', $rut->toJson());
    }
}
