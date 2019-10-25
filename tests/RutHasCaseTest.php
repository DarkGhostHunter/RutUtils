<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use DarkGhostHunter\RutUtils\Rut;

class RutHasCaseTest extends TestCase
{
    public function testDefaultGlobalUppercase()
    {
        $this->assertTrue(Rut::getGlobalUppercase());
    }

    public function testGlobalUppercase()
    {
        $rutA = new Rut('15518258-k');

        Rut::allUppercase();

        $rutB = new Rut('21644289-k');

        $this->assertEquals('K', $rutA->vd);
        $this->assertEquals('K', $rutA['vd']);
        $this->assertEquals('K', $rutB->vd);
        $this->assertEquals('K', $rutB['vd']);

        $this->assertEquals('15.518.258-K', (string)$rutA);
        $this->assertEquals('21.644.289-K', (string)$rutB);
    }

    public function testGlobalLowercase()
    {
        $rutA = new Rut('15518258-K');

        Rut::allLowercase();

        $rutB = new Rut('21644289-K');

        $this->assertEquals('k', $rutA->vd);
        $this->assertEquals('k', $rutA['vd']);
        $this->assertEquals('k', $rutB->vd);
        $this->assertEquals('k', $rutB['vd']);

        $this->assertEquals('15.518.258-k', (string)$rutA);
        $this->assertEquals('21.644.289-k', (string)$rutB);
    }

    public function testInstanceUppercase()
    {
        $rutA = new Rut('15518258-k');

        Rut::allLowercase();

        $rutB = new Rut('21644289-k');

        $this->assertEquals('K', $rutA->uppercase()->vd);
        $this->assertEquals('K', $rutA->uppercase()['vd']);
        $this->assertEquals('K', $rutB->uppercase()->vd);
        $this->assertEquals('K', $rutB->uppercase()['vd']);

        $this->assertEquals('15.518.258-K', (string)$rutA);
        $this->assertEquals('21.644.289-K', (string)$rutB);
    }

    public function testInstanceLowercase()
    {
        $rutA = new Rut('15518258-K');

        Rut::allUppercase();

        $rutB = new Rut('21644289-K');

        $this->assertEquals('k', $rutA->lowercase()->vd);
        $this->assertEquals('k', $rutA->lowercase()['vd']);
        $this->assertEquals('k', $rutB->lowercase()->vd);
        $this->assertEquals('k', $rutB->lowercase()['vd']);

        $this->assertEquals('15.518.258-k', (string)$rutA);
        $this->assertEquals('21.644.289-k', (string)$rutB);
    }
}
