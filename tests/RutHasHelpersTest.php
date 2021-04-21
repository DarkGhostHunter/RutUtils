<?php

namespace Tests;

use DarkGhostHunter\RutUtils\Rut;
use PHPUnit\Framework\TestCase;

class RutHasHelpersTest extends TestCase
{
    public function testIsValid()
    {
        $rut = new Rut(18300252, 'k');
        static::assertTrue($rut->isValid());
        static::assertFalse($rut->isInvalid());
    }

    public function testIsInvalid()
    {
        $rut = new Rut(0, 'bar');
        static::assertFalse($rut->isValid());
        static::assertTrue($rut->isInvalid());

        $rut = new Rut(1000, 0);
        static::assertFalse($rut->isValid());
        static::assertTrue($rut->isInvalid());
    }

    public function testIsPersonOrCompany()
    {
        $rut = new Rut(18300252, 'k');

        static::assertTrue($rut->isPerson());
        static::assertFalse($rut->isCompany());

        $rut = new Rut(50000000, '7');

        static::assertFalse($rut->isPerson());
        static::assertTrue($rut->isCompany());
    }

    public function testIsEqualTo()
    {
        $rut = new Rut(18300252, 'k');

        static::assertTrue($rut->isEqual('18300252K'));
        static::assertTrue($rut->isEqual(new Rut(18300252, 'K')));
        static::assertTrue($rut->isEqual(new Rut(18300252, 'k')), '18300252K');
        static::assertTrue($rut->isEqual([new Rut(18300252, 'K'), '18300252K', [18300252, 'K']]));

        static::assertFalse($rut->isEqual(null));
        static::assertFalse($rut->isEqual(new Rut(1000, 0)));
        static::assertFalse($rut->isEqual(new Rut(1000, 0), '18300252k'));
        static::assertFalse($rut->isEqual([new Rut(18300252, 'K'), '18300252K', [null, 'K']]));
    }

}
