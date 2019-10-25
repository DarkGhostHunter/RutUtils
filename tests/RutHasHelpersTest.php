<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use DarkGhostHunter\RutUtils\Rut;

class RutHasHelpersTest extends TestCase
{
    public function testIsValid()
    {
        $rut = new Rut('18.300.252-k');
        $this->assertTrue($rut->isValid());
        $this->assertFalse($rut->isInvalid());

        $rut = new Rut('18300252-k');
        $this->assertTrue($rut->isValid());
        $this->assertFalse($rut->isInvalid());

        $rut = new Rut('18300252k');
        $this->assertTrue($rut->isValid());
        $this->assertFalse($rut->isInvalid());

        $rut = new Rut(18300252, 'k');
        $this->assertTrue($rut->isValid());
        $this->assertFalse($rut->isInvalid());
    }

    public function testIsInvalid()
    {
        $rut = new Rut;
        $this->assertFalse($rut->isValid());
        $this->assertTrue($rut->isInvalid());

        $rut = new Rut('foo', 'bar');
        $this->assertFalse($rut->isValid());
        $this->assertTrue($rut->isInvalid());

        $rut = new Rut('foo');
        $this->assertFalse($rut->isValid());
        $this->assertTrue($rut->isInvalid());

        $rut = new Rut(null);
        $this->assertFalse($rut->isValid());
        $this->assertTrue($rut->isInvalid());

        $rut = new Rut(null, null);
        $this->assertFalse($rut->isValid());
        $this->assertTrue($rut->isInvalid());
    }

    public function testIsPersonOrCompany()
    {
        $rut = new Rut('18.300.252-k');

        $this->assertTrue($rut->isPerson());
        $this->assertFalse($rut->isCompany());

        $rut = new Rut('50.000.000-7');

        $this->assertFalse($rut->isPerson());
        $this->assertTrue($rut->isCompany());
    }

    public function testIsEqualTo()
    {
        $rut = new Rut('18.300.252-k');

        $this->assertTrue($rut->isEqualTo('18300252K'));
        $this->assertTrue($rut->isEqualTo(new Rut(18300252, 'K')));
        $this->assertTrue($rut->isEqualTo(new Rut(18300252, 'k')), '18300252K');
        $this->assertTrue($rut->isEqualTo([new Rut(18300252, 'K'), '18300252K', [18300252, 'K']]));

        $this->assertFalse($rut->isEqualTo(null));
        $this->assertFalse($rut->isEqualTo(new Rut));
        $this->assertFalse($rut->isEqualTo(new Rut, '18300252k'));
        $this->assertFalse($rut->isEqualTo([new Rut(18300252, 'K'), '18300252K', [null, 'K']]));
    }

}
