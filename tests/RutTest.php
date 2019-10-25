<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use DarkGhostHunter\RutUtils\Rut;
use DarkGhostHunter\RutUtils\Exceptions\InvalidRutException;

class RutTest extends TestCase
{
    public function testInstancing()
    {
        $rut = new Rut(18300252, 'k');

        $this->assertEquals(18300252, $rut->num);
        $this->assertEquals('K', $rut->vd);

        $rut = new Rut(1000, 'moUse');

        $this->assertEquals(1000, $rut->num);
        $this->assertEquals('MOUSE', $rut->vd);
    }

    public function testMake()
    {
        $rut = Rut::make('foo');

        $this->assertNull($rut);

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
        $this->assertNull($rut);

        $rut = Rut::make('1d2a9w0!3@9g1=9-1');
        $rut = Rut::make($rut);

        $this->assertEquals(12903919, $rut->num);
        $this->assertEquals(1, $rut->vd);

        $rut = Rut::make('foo', 'bar', function () {
            return 'foo';
        });
        $this->assertEquals('foo', $rut);

        $rut = Rut::make('foo', null, function () {
            return 'foo';
        });
        $this->assertEquals('foo', $rut);

        $rut = Rut::make('foo', function () {
            return 'foo';
        });
        $this->assertEquals('foo', $rut);
    }

    public function testClone()
    {
        $rutA = Rut::make('10.666.309-2');
        $rutB = $rutA->clone();

        $this->assertEquals($rutA, $rutB);
    }

    public function testMakeMany()
    {
        $ruts = Rut::many([
            '18.300.252-k',
            [9306036, 9],
            '210668594',
            '1d2a9w0!3@9g1=9-1',
            'foo',
            ['foo', 'bar']
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

    public function testMakeManyUnpacks()
    {
        $ruts = Rut::many(...[
            '18.300.252-k',
            [9306036, 9],
            '210668594',
            '1d2a9w0!3@9g1=9-1',
            'foo',
            ['foo', 'bar']
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

    public function testMakeManyReturnsEmptyArrayWhenNoArguments()
    {
        $ruts = Rut::many();

        $this->assertIsArray($ruts);
        $this->assertEmpty($ruts);
    }

    public function testManyOrThrow()
    {
        $this->expectException(InvalidRutException::class);

        Rut::manyOrThrow([
            '18.300.252-k',
            [9306036, 9],
            '210668594',
            '1d2a9w0!3@9g1=9-1',
            'foo',
            ['foo', 'bar'],
            187654321
        ]);
    }

    public function testManyReturnsEmptyArray()
    {
        $ruts = Rut::many();

        $this->assertIsArray($ruts);
        $this->assertEmpty($ruts);
    }

    public function testMakeOrThrowFailsWhenInvalidRut()
    {
        $this->expectException(InvalidRutException::class);

        Rut::manyOrThrow([
            ['foo'],
        ]);
    }

    public function testMakeOrThrowFailsWhenInvalidRuts()
    {
        $this->expectException(InvalidRutException::class);

        Rut::manyOrThrow([
            '18.300.252-k',
            ['foo'],
            'bar'
        ]);
    }

    public function testGetRut()
    {
        $rut = new Rut(10666309, 2);

        $this->assertEquals(['num' => 10666309, 'vd' => 2], $rut->getRut());
    }

    public function testGetAndSet()
    {
        $rut = new Rut(10666309, 2);

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
        $rut = new Rut(10666309, 2);

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
        $rut = new Rut(10666309, 2);

        $this->assertJson($rut->toJson());
        $this->assertJson(json_encode($rut));

        $this->assertEquals('"10.666.309-2"', $rut->toJson());
        $this->assertEquals('"10.666.309-2"', json_encode($rut));
    }

    public function testToString()
    {
        $rut = new Rut(10666309, 2);

        $this->assertIsString($rut->__toString());
        $this->assertEquals('10.666.309-2', $rut->__toString());
        $this->assertIsString((string)$rut);
        $this->assertEquals('10.666.309-2', (string)$rut);
    }

    public function testToArray()
    {
        $rut = new Rut(10666309, 2);

        $this->assertIsArray($rut->toArray());

        $this->assertEquals($array = [
            'num' => 10666309,
            'vd' => '2'
        ], $rut->toArray());
    }

    public function testSerialization()
    {
        $unserialized = unserialize(serialize(new Rut(10666309, 2)));

        $this->assertInstanceOf(Rut::class, $unserialized);
        $this->assertEquals(10666309, $unserialized->num);
        $this->assertEquals(2, $unserialized->vd);
    }
}
