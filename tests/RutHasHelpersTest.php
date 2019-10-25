<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use DarkGhostHunter\RutUtils\Rut;

class RutHasHelpersTest extends TestCase
{
    public function testIsValid()
    {
        $rut = new Rut(18300252, 'k');
        $this->assertTrue($rut->isValid());
        $this->assertFalse($rut->isInvalid());
    }

    public function testIsInvalid()
    {
        $rut = new Rut(0, 'bar');
        $this->assertFalse($rut->isValid());
        $this->assertTrue($rut->isInvalid());

        $rut = new Rut(1000, 0);
        $this->assertFalse($rut->isValid());
        $this->assertTrue($rut->isInvalid());
    }

    public function testIsPersonOrCompany()
    {
        $rut = new Rut(18300252, 'k');

        $this->assertTrue($rut->isPerson());
        $this->assertFalse($rut->isCompany());

        $rut = new Rut(50000000, '7');

        $this->assertFalse($rut->isPerson());
        $this->assertTrue($rut->isCompany());
    }

    public function testIsEqualTo()
    {
        $rut = new Rut(18300252, 'k');

        $this->assertTrue($rut->isEqual('18300252K'));
        $this->assertTrue($rut->isEqual(new Rut(18300252, 'K')));
        $this->assertTrue($rut->isEqual(new Rut(18300252, 'k')), '18300252K');
        $this->assertTrue($rut->isEqual([new Rut(18300252, 'K'), '18300252K', [18300252, 'K']]));

        $this->assertFalse($rut->isEqual(null));
        $this->assertFalse($rut->isEqual(new Rut(1000, 0)));
        $this->assertFalse($rut->isEqual(new Rut(1000, 0), '18300252k'));
        $this->assertFalse($rut->isEqual([new Rut(18300252, 'K'), '18300252K', [null, 'K']]));
    }

}
