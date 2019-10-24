<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use DarkGhostHunter\RutUtils\Rut;
use DarkGhostHunter\RutUtils\Exceptions\InvalidRutException;

class RutTest extends TestCase
{
    public function testInstancing()
    {
        $rut = new Rut();

        $this->assertNull($rut->num);
        $this->assertNull($rut->vd);

        $rut = new Rut('foo');

        $this->assertNull($rut->num);
        $this->assertNull($rut->vd);

        $rut = new Rut('10666309-2');

        $this->assertEquals(10666309, $rut->num);
        $this->assertEquals(2, $rut->vd);

        $rut = new Rut(18300252, 'k');

        $this->assertEquals(18300252, $rut->num);
        $this->assertEquals('K', $rut->vd);
    }

    public function testMake()
    {
        $rut = Rut::make('foo');

        $this->assertNull($rut->num);
        $this->assertNull($rut->vd);

        $rut = Rut::make('10.666.309-2');

        $this->assertEquals(10666309, $rut->num);
        $this->assertEquals(2, $rut->vd);

        $rut = Rut::make(18300252, 'k');

        $this->assertEquals(18300252, $rut->num);
        $this->assertEquals('K', $rut->vd);

        $rut = Rut::make('1d2a9w0!3@9g1=9-1');

        $this->assertEquals(12903919, $rut->num);
        $this->assertEquals(1, $rut->vd);

        $rut = Rut::make('foo', 'bar');

        $this->assertEquals('foo', $rut->num);
        $this->assertEquals('BAR', $rut->vd);
    }

    public function testMakeFailsWhenNoArgument()
    {
        $this->expectException(\ArgumentCountError::class);

        Rut::make();
    }

    public function testMakeFailsWhenFirstArgumentNotString()
    {
        $this->expectException(\TypeError::class);

        Rut::make(null);
    }

    public function testMakeMany()
    {
        $ruts = Rut::makeMany([
            '18.300.252-k',
            [9306036, 9],
            '210668594',
            '1d2a9w0!3@9g1=9-1',
            'foo',
            ['foo', 'bar']
        ]);

        $this->assertIsArray($ruts);
        $this->assertCount(6, $ruts);

        $this->assertEquals(18300252, $ruts[0]->num);
        $this->assertEquals('K', $ruts[0]->vd);

        $this->assertEquals(9306036, $ruts[1]->num);
        $this->assertEquals(9, $ruts[1]->vd);

        $this->assertEquals(21066859, $ruts[2]->num);
        $this->assertEquals(4, $ruts[2]->vd);

        $this->assertEquals(12903919, $ruts[3]->num);
        $this->assertEquals(1, $ruts[3]->vd);

        $this->assertEquals(null, $ruts[4]->num);
        $this->assertEquals(null, $ruts[4]->vd);

        $this->assertEquals('foo', $ruts[5]->num);
        $this->assertEquals('BAR', $ruts[5]->vd);
    }

    public function testMakeManyUnpacks()
    {
        $ruts = Rut::makeMany(...[
            '18.300.252-k',
            [9306036, 9],
            '210668594',
            '1d2a9w0!3@9g1=9-1',
            'foo',
            ['foo', 'bar']
        ]);

        $this->assertIsArray($ruts);
        $this->assertCount(6, $ruts);

        $this->assertEquals(18300252, $ruts[0]->num);
        $this->assertEquals('K', $ruts[0]->vd);

        $this->assertEquals(9306036, $ruts[1]->num);
        $this->assertEquals(9, $ruts[1]->vd);

        $this->assertEquals(21066859, $ruts[2]->num);
        $this->assertEquals(4, $ruts[2]->vd);

        $this->assertEquals(12903919, $ruts[3]->num);
        $this->assertEquals(1, $ruts[3]->vd);

        $this->assertEquals(null, $ruts[4]->num);
        $this->assertEquals(null, $ruts[4]->vd);

        $this->assertEquals('foo', $ruts[5]->num);
        $this->assertEquals('BAR', $ruts[5]->vd);
    }

    public function testMakeManyReturnsEmptyArrayWhenNoArguments()
    {
        $ruts = Rut::makeMany();

        $this->assertIsArray($ruts);
        $this->assertEmpty($ruts);
    }

    public function testMakeValid()
    {
        $ruts = Rut::makeValid([
            '18.300.252-k',
            [9306036, 9],
            '210668594',
            '1d2a9w0!3@9g1=9-1',
            'foo',
            ['foo', 'bar'],
            187654321
        ]);

        $this->assertIsArray($ruts);
        $this->assertCount(4, $ruts);

        $this->assertEquals(18300252, $ruts[0]->num);
        $this->assertEquals('K', $ruts[0]->vd);

        $this->assertEquals(9306036, $ruts[1]->num);
        $this->assertEquals(9, $ruts[1]->vd);

        $this->assertEquals(21066859, $ruts[2]->num);
        $this->assertEquals(4, $ruts[2]->vd);

        $this->assertEquals(12903919, $ruts[3]->num);
        $this->assertEquals(1, $ruts[3]->vd);
    }

    public function testMakeReturnsEmptyArray()
    {
        $ruts = Rut::makeValid();

        $this->assertIsArray($ruts);
        $this->assertEmpty($ruts);
    }

    public function testMakeValidFailsWhenInvalidType()
    {
        $this->expectException(\TypeError::class);

        Rut::makeValid(187654321);
    }

    public function testMakeOrThrow()
    {
        $ruts = Rut::makeOrThrow([
            '18.300.252-k',
            [9306036, 9],
            '210668594',
            '1d2a9w0!3@9g1=9-1',
        ]);

        $this->assertIsArray($ruts);
        $this->assertCount(4, $ruts);

        $this->assertEquals(18300252, $ruts[0]->num);
        $this->assertEquals('K', $ruts[0]->vd);

        $this->assertEquals(9306036, $ruts[1]->num);
        $this->assertEquals(9, $ruts[1]->vd);

        $this->assertEquals(21066859, $ruts[2]->num);
        $this->assertEquals(4, $ruts[2]->vd);

        $this->assertEquals(12903919, $ruts[3]->num);
        $this->assertEquals(1, $ruts[3]->vd);
    }

    public function testMakeOrThrowFailsWhenInvalidRuts()
    {
        $this->expectException(InvalidRutException::class);

        Rut::makeOrThrow([
            '18.300.252-k',
            ['foo'],
            'bar'
        ]);
    }

    public function testPutRut()
    {
        $rut = new Rut;

        $this->assertNull($rut->num);
        $this->assertNull($rut->vd);

        $rut->putRut(18765432, 1);

        $this->assertEquals(18765432, $rut->num);
        $this->assertEquals(1, $rut->vd);
    }

    public function testGetAndSet()
    {
        $rut = new Rut('10666309-2');

        $this->assertEquals(10666309, $rut->num);
        $this->assertEquals(2, $rut->vd);

        $this->assertEquals(10666309, $rut['num']);
        $this->assertEquals(2, $rut['vd']);

        $rut->num = 'foo';
        $rut->vd = 'bar';

        $this->assertEquals(10666309, $rut->num);
        $this->assertEquals(2, $rut->vd);

        $this->assertEquals(10666309, $rut['num']);
        $this->assertEquals(2, $rut['vd']);

        $rut['num'] = 'foo';
        $rut['vd'] = 'bar';

        $this->assertEquals(10666309, $rut->num);
        $this->assertEquals(2, $rut->vd);

        $this->assertEquals(10666309, $rut['num']);
        $this->assertEquals(2, $rut['vd']);
    }

    public function testIssetAndUnset()
    {
        $rut = new Rut;

        $this->assertFalse(isset($rut->vd));
        $this->assertFalse(isset($rut['vd']));
        $this->assertFalse(isset($rut->num));
        $this->assertFalse(isset($rut['num']));

        unset($rut->vd, $rut['vd'], $rut->num, $rut['num']);

        $this->assertFalse(isset($rut->vd));
        $this->assertFalse(isset($rut['vd']));
        $this->assertFalse(isset($rut->num));
        $this->assertFalse(isset($rut['num']));

        $rut = new Rut('10666309-2');

        $this->assertTrue(isset($rut->vd));
        $this->assertTrue(isset($rut['vd']));
        $this->assertTrue(isset($rut->num));
        $this->assertTrue(isset($rut['num']));

        unset($rut->vd, $rut['vd'], $rut->num, $rut['num']);

        $this->assertTrue(isset($rut->vd));
        $this->assertTrue(isset($rut['vd']));
        $this->assertTrue(isset($rut->num));
        $this->assertTrue(isset($rut['num']));
    }

    public function testToJson()
    {
        $rut = new Rut('10666309-2');

        $this->assertJson($rut->toJson());
        $this->assertJson(json_encode($rut));

        $this->assertEquals('"10.666.309-2"', $rut->toJson());
        $this->assertEquals('"10.666.309-2"', json_encode($rut));
    }

    public function testToString()
    {
        $rut = new Rut('10666309-2');

        $this->assertIsString($rut->__toString());
        $this->assertEquals('10.666.309-2', $rut->__toString());
        $this->assertIsString((string)$rut);
        $this->assertEquals('10.666.309-2', (string)$rut);
    }

    public function testToArray()
    {
        $rut = new Rut('10666309-2');

        $this->assertIsArray($rut->toArray());

        $this->assertEquals($array = [
            'num' => 10666309,
            'vd' => '2'
        ], $rut->toArray());
    }

    public function testSerialization()
    {
        $unserialized = unserialize(serialize(new Rut('10666309-2')));

        $this->assertInstanceOf(Rut::class, $unserialized);
        $this->assertEquals(10666309, $unserialized->num);
        $this->assertEquals(2, $unserialized->vd);
    }
}
