<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use DarkGhostHunter\RutUtils\Rut;
use DarkGhostHunter\RutUtils\RutGenerator;

class HelpersTests extends TestCase
{
    public function testReturnsRut()
    {
        $this->assertInstanceOf(Rut::class, rut('10.666.309-2'));
    }

    public function testReturnsDefaultWhenInvalidRut()
    {
        $this->assertTrue(rut('foo', true));
    }

    public function testReturnsGeneratorWhenNoParameters()
    {
        $this->assertInstanceOf(RutGenerator::class, rut());
    }
}