<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use DarkGhostHunter\RutUtils\Rut;

class RutHasFormatsTest extends TestCase
{
    public function testDefaultStrict()
    {
        $this->assertEquals('strict', Rut::getGlobalFormat());

        $rut = new Rut;

        $this->assertEquals('strict', $rut->getFormat());
    }

    public function testChangesGlobalFormats()
    {
        $rut = new Rut('18.300.252-k');

        $this->assertEquals('strict', Rut::getGlobalFormat());
        $this->assertEquals('18.300.252-K', (string)$rut);

        Rut::allFormatRaw();
        $this->assertEquals('raw', Rut::getGlobalFormat());
        $this->assertEquals('18300252K', (string)$rut);

        Rut::allFormatBasic();
        $this->assertEquals('basic', Rut::getGlobalFormat());
        $this->assertEquals('18300252-K', (string)$rut);

        Rut::allFormatStrict();
        $this->assertEquals('strict', Rut::getGlobalFormat());
        $this->assertEquals('18.300.252-K', (string)$rut);
    }

    public function testChangesInstanceFormat()
    {
        $rut = new Rut('18.300.252-k');
        $this->assertEquals('strict', $rut->getFormat());

        $rut->setFormat('raw');
        $this->assertEquals('raw', $rut->getFormat());
        $this->assertEquals('18300252K', (string)$rut);
        $this->assertEquals('18300252K', $rut->toFormattedString());

        $rut->setFormat('basic');
        $this->assertEquals('basic', $rut->getFormat());
        $this->assertEquals('18300252-K', (string)$rut);
        $this->assertEquals('18300252-K', $rut->toFormattedString());

        $rut->setFormat('strict');
        $this->assertEquals('strict', $rut->getFormat());
        $this->assertEquals('18.300.252-K', (string)$rut);
        $this->assertEquals('18.300.252-K', $rut->toFormattedString());
    }


}
