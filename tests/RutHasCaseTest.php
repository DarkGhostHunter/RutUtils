<?php

namespace Tests;

use DarkGhostHunter\RutUtils\Rut;
use PHPUnit\Framework\TestCase;

class RutHasCaseTest extends TestCase
{
    public function testDefaultGlobalUppercase()
    {
        static::assertTrue(Rut::getGlobalUppercase());
    }

    public function testGlobalUppercase()
    {
        $rutA = new Rut('15518258', 'k');

        Rut::allUppercase();

        $rutB = new Rut('21644289', 'k');

        static::assertEquals('K', $rutA->vd);
        static::assertEquals('K', $rutA['vd']);
        static::assertEquals('K', $rutB->vd);
        static::assertEquals('K', $rutB['vd']);

        static::assertEquals('15.518.258-K', (string)$rutA);
        static::assertEquals('21.644.289-K', (string)$rutB);
    }

    public function testGlobalLowercase()
    {
        $rutA = new Rut('15518258', 'K');

        Rut::allLowercase();

        $rutB = new Rut('21644289', 'K');

        static::assertEquals('k', $rutA->vd);
        static::assertEquals('k', $rutA['vd']);
        static::assertEquals('k', $rutB->vd);
        static::assertEquals('k', $rutB['vd']);

        static::assertEquals('15.518.258-k', (string)$rutA);
        static::assertEquals('21.644.289-k', (string)$rutB);
    }

    public function testInstanceUppercase()
    {
        $rutA = new Rut('15518258', 'k');

        Rut::allLowercase();

        $rutB = new Rut('21644289', 'k');

        $rutA->uppercase();
        $rutB->uppercase();

        static::assertEquals('K', $rutA->vd);
        static::assertEquals('K', $rutA['vd']);
        static::assertEquals('K', $rutB->vd);
        static::assertEquals('K', $rutB['vd']);

        static::assertEquals('15.518.258-K', (string)$rutA);
        static::assertEquals('21.644.289-K', (string)$rutB);
    }

    public function testInstanceLowercase()
    {
        $rutA = new Rut('15518258', 'K');

        Rut::allUppercase();

        $rutB = new Rut('21644289', 'K');

        $rutA->lowercase();
        $rutB->lowercase();

        static::assertEquals('k', $rutA->vd);
        static::assertEquals('k', $rutA['vd']);
        static::assertEquals('k', $rutB->vd);
        static::assertEquals('k', $rutB['vd']);

        static::assertEquals('15.518.258-k', (string)$rutA);
        static::assertEquals('21.644.289-k', (string)$rutB);
    }
}
