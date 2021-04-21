<?php

namespace Tests;

use DarkGhostHunter\RutUtils\Rut;
use DarkGhostHunter\RutUtils\RutGenerator;
use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    public function testReturnsRut()
    {
        static::assertInstanceOf(Rut::class, rut('10.666.309-2'));
    }

    public function testReturnsDefaultWhenInvalidRut()
    {
        static::assertTrue(rut('foo', true));
    }

    public function testReturnsGeneratorWhenNoParameters()
    {
        static::assertInstanceOf(RutGenerator::class, rut());
    }
}