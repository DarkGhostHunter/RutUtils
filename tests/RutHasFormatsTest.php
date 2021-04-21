<?php

namespace Tests;

use DarkGhostHunter\RutUtils\Rut;
use PHPUnit\Framework\TestCase;

class RutHasFormatsTest extends TestCase
{
    public function testDefaultStrict()
    {
        static::assertEquals('strict', Rut::getGlobalFormat());

        $rut = new Rut(18300252, 'k');

        static::assertEquals('strict', $rut->getFormat());
    }

    public function testChangesGlobalFormats()
    {
        $rut = new Rut(18300252, 'k');

        static::assertEquals('strict', Rut::getGlobalFormat());
        static::assertEquals('18.300.252-K', (string)$rut);

        Rut::allFormatRaw();
        static::assertEquals('raw', Rut::getGlobalFormat());
        static::assertEquals('18300252K', (string)$rut);

        Rut::allFormatBasic();
        static::assertEquals('basic', Rut::getGlobalFormat());
        static::assertEquals('18300252-K', (string)$rut);

        Rut::allFormatStrict();
        static::assertEquals('strict', Rut::getGlobalFormat());
        static::assertEquals('18.300.252-K', (string)$rut);
    }

    public function testChangesInstanceFormat()
    {
        $rut = new Rut(18300252, 'k');
        static::assertEquals('strict', $rut->getFormat());

        $rut->setFormat('raw');
        static::assertEquals('raw', $rut->getFormat());
        static::assertEquals('18300252K', (string)$rut);
        static::assertEquals('18300252K', $rut->toFormattedString());

        $rut->setFormat('basic');
        static::assertEquals('basic', $rut->getFormat());
        static::assertEquals('18300252-K', (string)$rut);
        static::assertEquals('18300252-K', $rut->toFormattedString());

        $rut->setFormat('strict');
        static::assertEquals('strict', $rut->getFormat());
        static::assertEquals('18.300.252-K', (string)$rut);
        static::assertEquals('18.300.252-K', $rut->toFormattedString());
    }


}
