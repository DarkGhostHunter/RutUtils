<?php

namespace Tests;

use TypeError;
use PHPUnit\Framework\TestCase;
use DarkGhostHunter\RutUtils\Rut;
use DarkGhostHunter\RutUtils\RutHelper;

class RutHelperTest extends TestCase
{

    /** @var array */
    protected $ruts = [
        '84954616',
        247009094,
        '58685666',
        136665782,
        '22605071k',
        '14379170K',
        '143281450',
    ];

    /** @var array */
    protected $invalidRuts = [
        '84984616',
        247009091,
        '58685668',
        13666578,
        '22605071J',
        '14379171K',
        '14328145K',
    ];

    protected $malformedRuts = [
        84954616,
        '2470!!!###0909-4',
        '586P""ld?¨_,`*85666',
        '136?~~66578-2',
        '22605071k',
        '1437*^¨Ç¿_:;>917q!$/%/e0K',
    ];

    public function testCleansRut()
    {
        foreach ($this->malformedRuts as $key => $rut) {
            $this->assertEquals(strtolower($this->ruts[$key]), RutHelper::cleanRut($rut, false));
        }
    }

    public function testNullOnEmptyCleanedRut()
    {
        $this->assertNull(RutHelper::cleanRut('asdasdasdasd', false));
        $this->assertNull(RutHelper::cleanRut('asdasdasdasd', true));
    }

    public function testSeparatesRut()
    {
        foreach ($this->ruts as $rut) {

            $this->assertEquals(
                strtoupper(substr($rut, 0, -1)),
                RutHelper::separateRut($rut)[0]
            );
            $this->assertEquals(
                strtoupper(substr($rut, -1, 1)),
                RutHelper::separateRut($rut)[1]
            );
        }
    }

    public function testSeparatesMalformedRut()
    {
        foreach ($this->malformedRuts as $key => $rut) {
            $this->assertEquals(
                strtoupper(substr($this->ruts[$key], 0, -1)),
                RutHelper::separateRut($rut)[0]
            );
            $this->assertEquals(
                strtoupper(substr($this->ruts[$key], -1, 1)),
                RutHelper::separateRut($rut)[1]
            );
        }
    }

    public function testNullOnCleanedRut()
    {
        $this->assertEquals([null, null], RutHelper::separateRut('this-is-no-a-rut'));
    }

    public function testValidate()
    {
        $this->assertTrue(RutHelper::validate($this->ruts));

        foreach ($this->ruts as $rut) {
            $this->assertTrue(RutHelper::validate($rut));
        }
    }

    public function testDoesntValidates()
    {
        foreach ($this->invalidRuts as $rut) {
            $this->assertFalse(RutHelper::validate($rut));
        }

        $this->assertFalse(RutHelper::validate('asdasdasd'));

    }

    public function testValidateOnMalformedRut()
    {
        foreach ($this->malformedRuts as $rut) {
            $this->assertTrue(RutHelper::validate($rut));
        }
    }

    public function testDoesntValidateOnMalformedRut()
    {
        $malformed = [
            '8498dfadf4616',
            247009091,
            '58685f66*^Ç¨_:;>¿??="·$&""8',
            13666578,
            '22^¨*¨"$&=(%605071J',
            '1437*^¨_:;·"$SDFPW9ASDW171K',
        ];

        foreach ($malformed as $rut) {
            $this->assertFalse(RutHelper::validate($rut));
        }
    }

    public function testDoesntValidateOnInvalidType()
    {
        $malformed = [
            '8.495.461-6',
            '24.700.909-4',
            ['5.868.566-6'],
            '13.666.578-2',
            '22.605.071-k',
            '14.379.170-K',
        ];

        $this->assertFalse(RutHelper::validate($malformed));
    }

    public function testValidateStrict()
    {
        $ruts = [
            '8.495.461-6',
            '24.700.909-4',
            '5.868.566-6',
            '13.666.578-2',
            '22.605.071-k',
            '14.379.170-K',
        ];

        $this->assertTrue(RutHelper::validateStrict($ruts));

        foreach ($ruts as $rut) {
            $this->assertTrue(RutHelper::validateStrict($rut));
        }
    }

    public function testDoesntValidateStrict()
    {
        foreach ($this->ruts as $rut) {
            $this->assertFalse(RutHelper::validateStrict($rut));
        }
    }

    public function testDoesntValidateStrictWhenInvalidType()
    {
        $ruts = [
            '8.495.461-6',
            ['24.700.909-4'],
            '5.868.566-6',
            '13.666.578-2',
            '14.379.170-K',
        ];

        $this->assertFalse(RutHelper::validateStrict($ruts));

        $ruts = [
            new Rut(8495461, '6'),
            new Rut(13666578, '6'),
        ];

        $this->assertFalse(RutHelper::validateStrict($ruts));
    }

    public function testAreTwoEqual()
    {
        $this->assertTrue(RutHelper::isEqual(247009094, '2470!!!###0909-4'));
        $this->assertTrue(RutHelper::isEqual(247009094, '2470!!!###0909-4', '24.700.909-4'));
    }

    public function testAreTwoNotEqual()
    {
        $this->assertFalse(RutHelper::isEqual(247009091, '2470!!!###0909-4'));
        $this->assertTrue(RutHelper::isEqual(247009094, '2470!!!###0909-4', '24.700.9094'));
    }

    public function testAreEqualWithSingleArray()
    {
        $this->assertTrue(RutHelper::isEqual([
            247009094, '2470!!!###0909-4',
        ]));

        $this->assertFalse(RutHelper::isEqual([
            247009091, '2470!!!###0909-4',
        ]));
    }

    public function testFilter()
    {
        $this->assertEquals(['247009094'], RutHelper::filter(247009094));
        $this->assertEquals(['247009094'], RutHelper::filter('247009094'));

        $this->assertEquals($this->ruts, RutHelper::filter($this->ruts));

        $malformedRuts = array_map(function ($rut) {
            return (string)$rut;
        }, $this->malformedRuts);

        $this->assertEquals($malformedRuts, RutHelper::filter($this->malformedRuts));
    }

    public function testDoesntFilter()
    {
        $this->assertEquals([], RutHelper::filter(247009091));
        $this->assertEquals([], RutHelper::filter('247009091'));
        $this->assertEquals([], RutHelper::filter($this->invalidRuts));
    }

    public function testRectify()
    {
        $rutA = 24700909;
        $rutAVd = 4;
        $rutB = '22605071';
        $rutBVd = 'k';

        $rutARectified = RutHelper::rectify($rutA);
        $rutBRectified = RutHelper::rectify($rutB);

        $this->assertInstanceOf(Rut::class, $rutARectified);
        $this->assertInstanceOf(Rut::class, $rutBRectified);

        $this->assertEquals((string)$rutA . $rutAVd, $rutARectified->toRawString());
        $this->assertEquals(strtoupper($rutB . $rutBVd), $rutBRectified->toRawString());
    }

    public function testDoesntRectify()
    {
        $this->expectException(TypeError::class);

        RutHelper::rectify('NotARut');
    }

    public function testIsPerson()
    {
        $this->assertTrue(RutHelper::isPerson(136665782));
        $this->assertTrue(RutHelper::isPerson('22605071k'));

        $this->assertFalse(RutHelper::isPerson(761231235));
        $this->assertFalse(RutHelper::isPerson('66123136K'));
    }

    public function testIsCompany()
    {
        $this->assertFalse(RutHelper::isCompany(136665782));
        $this->assertFalse(RutHelper::isCompany('22605071k'));

        $this->assertTrue(RutHelper::isCompany(761231235));
        $this->assertTrue(RutHelper::isCompany('66123136K'));
    }

    public function testGetVd()
    {
        $ruts = [
            '6' => '8495461',
            '4' => '24700909',
            '2' => '13666578',
            'K' => '22605071',
        ];

        foreach ($ruts as $key => $rut) {
            $this->assertEquals($key, RutHelper::getVd($rut));
        }

    }
}