<?php

namespace unit;

use BadMethodCallException;
use DarkGhostHunter\RutUtils\Exceptions\InvalidRutException;
use DarkGhostHunter\RutUtils\Rut;
use PHPUnit\Framework\TestCase;

class RutTest extends TestCase
{

    public function test__construct()
    {
        $this->assertInstanceOf(Rut::class, $rut = new Rut());
        $this->assertEmpty($rut->toRawString());

        $this->assertInstanceOf(Rut::class, $rut = new Rut('66123136K'));
        $this->assertEquals($rut->toRawString(), '66123136K');

        $this->assertInstanceOf(Rut::class, $rut = new Rut(66123136, 'K'));
        $this->assertEquals($rut->toRawString(), '66123136K');

        $this->assertInstanceOf(Rut::class, $rut = new Rut(66123136, 'K'));
        $this->assertEquals($rut->toRawString(), '66123136K');
    }

    public function testDoesntConstruct()
    {
        $this->expectException(InvalidRutException::class);

        $this->assertInstanceOf(Rut::class, $rut = new Rut('asdasdasd', 'K'));
    }

    public function testAddRut()
    {
        $rut = new Rut();

        $rut->addRut('66123136K');

        $this->assertEquals($rut->num, 66123136);
        $this->assertEquals($rut->vd, 'K');
    }

    public function testMake()
    {
        $rut = Rut::make('66123136K');

        $this->assertInstanceOf(Rut::class, $rut);
        $this->assertEquals('66.123.136-K', (string)$rut);

        $ruts = Rut::make('66123136K', 247009094);

        $this->assertIsArray($ruts);

        foreach ($ruts as $rut) {
            $this->assertInstanceOf(Rut::class, $rut);
        }

        $ruts =  $ruts = Rut::make(['66123136K', 247009094]);

        $this->assertIsArray($ruts);

        foreach ($ruts as $rut) {
            $this->assertInstanceOf(Rut::class, $rut);
        }
    }

    public function testExceptionOnMake()
    {
        $this->expectException(InvalidRutException::class);

        Rut::make('asdasdasd');
    }

    public function testExceptionOnMultipleMake()
    {
        $this->expectException(InvalidRutException::class);

        Rut::make('asdasdasd', 247009094);
    }

    public function testMakeValid()
    {
        $rut = Rut::makeValid('66123136K');

        $this->assertInstanceOf(Rut::class, $rut);
        $this->assertEquals('66.123.136-K', (string)$rut);

        $ruts = Rut::makeValid('66123136K', 247009094);

        $this->assertIsArray($ruts);

        foreach ($ruts as $rut) {
            $this->assertInstanceOf(Rut::class, $rut);
        }

        $ruts =  $ruts = Rut::makeValid(['66123136K', 247009094]);

        $this->assertIsArray($ruts);

        foreach ($ruts as $rut) {
            $this->assertInstanceOf(Rut::class, $rut);
        }
    }

    public function testExceptionOnMakeValid()
    {
        $this->expectException(InvalidRutException::class);

        Rut::makeValid('sadadasdasd');
    }

    public function testExceptionOnMultipleMakeValid()
    {
        $this->expectException(InvalidRutException::class);

        Rut::makeValid('asdasdasd', 247009094);
    }

    public function testAllUppercase()
    {
        Rut::allUppercase();

        $rut = new Rut('66123136k');

        $this->assertEquals('K', $rut->vd);
    }

    public function testAllLowercase()
    {
        Rut::allLowercase();

        $rut = new Rut('66123136K');

        $this->assertEquals('k', $rut->vd);

        Rut::allUppercase();
    }

    public function testIsValid()
    {
        $rut = new Rut('66123136K');

        $this->assertTrue($rut->isValid());

        $rut = new Rut('1111111K');

        $this->assertFalse($rut->isValid());
    }

    public function testIsPerson()
    {
        $rut = new Rut('66123136K');

        $this->assertFalse($rut->person());

        $rut = new Rut('84954616');

        $this->assertTrue($rut->person());
    }

    public function testIsCompany()
    {
        $rut = new Rut('66123136K');

        $this->assertTrue($rut->company());

        $rut = new Rut('84954616');

        $this->assertFalse($rut->company());
    }

    public function testIsEqualTo()
    {
        $rut = new Rut('66123136K');

        $this->assertTrue($rut->isEqualTo('66.123.136-K'));
        $this->assertTrue($rut->isEqualTo('66123136K'));
        $this->assertFalse($rut->isEqualTo('8.495.461-6'));
        $this->assertFalse($rut->isEqualTo('84954616'));
    }

    public function testToRawString()
    {
        $rut = new Rut('66123136K');
        $this->assertEquals('66123136K', $rut->toRawString());

        $rut = new Rut('84954616');
        $this->assertEquals('84954616', $rut->toRawString());
    }

    public function testToFormattedString()
    {
        $rut = new Rut('66123136K');

        $this->assertEquals('66.123.136-K', $rut->toFormattedString());

        $rut = new Rut('8495461-6');

        $this->assertEquals('8.495.461-6', $rut->toFormattedString());
    }

    public function testToArray()
    {
        $rut = new Rut('66123136K');

        $this->assertIsArray($rut->toArray());

        $rut = new Rut('8495461-6');

        $this->assertIsArray($rut->toArray());
    }

    public function testCallNotFoundException()
    {
        $this->expectException(BadMethodCallException::class);

        $rut = new Rut();

        $rut->badMethodCall();
    }

    public function testStaticCallNotFoundException()
    {
        $this->expectException(BadMethodCallException::class);

        Rut::badMethodCall();
    }

    public function testAccessibleAsArray()
    {
        $rut = new Rut('66123136K');

        $this->assertEquals(66123136, $rut['num']);
        $this->assertEquals('K', $rut['vd']);
        $this->assertNull($rut['null']);

        $rut['num'] = 'notanum';
        $rut['vd'] = 'notanum';

        $this->assertEquals(66123136, $rut['num']);
        $this->assertEquals('K', $rut['vd']);

        unset($rut['num']);

        $this->assertEquals(66123136, $rut['num']);

        $rut['lol'] = true;

        $this->assertNull($rut['lol']);

        $this->assertTrue(isset($rut['num']));
    }

    public function testAccessibleAsObject()
    {
        $rut = new Rut('66123136K');

        $this->assertEquals(66123136, $rut->num);
        $this->assertEquals('K', $rut->vd);
        $this->assertNull($rut->null);

        $rut->num = 'notanum';
        $rut->vd = 'notanum';

        $this->assertEquals(66123136, $rut->num);
        $this->assertEquals('K', $rut->vd);

        unset($rut->num);

        $this->assertEquals(66123136, $rut->num);

        $rut->lol = true;

        $this->assertNull($rut->lol);
    }

    public function testJsonSerializable()
    {
        $rut = new Rut('66123136K');

        $this->assertJson($json = json_encode($rut));

        $this->assertEquals(json_decode($json, true), '66.123.136-K');
    }

    public function testToJson()
    {
        $rut = new Rut('66123136K');

        $this->assertJson($rut->toJson());

        $this->assertEquals($rut->toJson(), '"66.123.136-K"');
    }

    public function testGetStringFormat()
    {
        $this->assertEquals('full', Rut::getStringFormat());

        Rut::setStringFormat('basic');

        $this->assertEquals('basic', Rut::getStringFormat());

        Rut::setStringFormat('raw');

        $this->assertEquals('raw', Rut::getStringFormat());

        Rut::setStringFormat('full');

        $this->assertEquals('full', Rut::getStringFormat());

        Rut::setStringFormat('basic');
        Rut::setStringFormat('anythingWillDefaultToFull');

        $this->assertEquals('full', Rut::getStringFormat());
    }

    public function testSetStringFormat()
    {
        $rut = new Rut('66123136K');

        $this->assertEquals('66.123.136-K', (string)$rut);

        Rut::setStringFormat('basic');

        $this->assertEquals('66123136-K', (string)$rut);

        Rut::setStringFormat('raw');

        $this->assertEquals('66123136K', (string)$rut);

        Rut::setStringFormat('full');

        $this->assertEquals('66.123.136-K', (string)$rut);

        Rut::setStringFormat('basic');
        Rut::setStringFormat('anythingWillDefaultToFull');

        $this->assertEquals('66.123.136-K', (string)$rut);
    }
}
