<?php

namespace Tests;

use DarkGhostHunter\RutUtils\Exceptions\InvalidRutException;
use DarkGhostHunter\RutUtils\Rut;
use PHPUnit\Framework\TestCase;

class RutTest extends TestCase
{
    public function testInstancing()
    {
        $rut = new Rut(18300252, 'k');

        static::assertEquals(18300252, $rut->num);
        static::assertEquals('K', $rut->vd);

        $rut = new Rut(1000, 'moUse');

        static::assertEquals(1000, $rut->num);
        static::assertEquals('MOUSE', $rut->vd);
    }

    public function testMake()
    {
        $rut = Rut::make('foo');

        static::assertNull($rut);

        $rut = Rut::make('10.666.309-2');

        static::assertEquals(10666309, $rut->num);
        static::assertEquals(2, $rut->vd);

        $rut = Rut::make(18300252, 'k');

        static::assertEquals(18300252, $rut->num);
        static::assertEquals('K', $rut->vd);

        $rut = Rut::make('1d2a9w0!3@9g1=9-1');

        static::assertEquals(12903919, $rut->num);
        static::assertEquals(1, $rut->vd);

        $rut = Rut::make('foo', 'bar');
        static::assertNull($rut);

        $rut = Rut::make('1d2a9w0!3@9g1=9-1');
        $rut = Rut::make($rut);

        static::assertEquals(12903919, $rut->num);
        static::assertEquals(1, $rut->vd);

        $rut = Rut::make('foo', 'bar', function () {
            return 'foo';
        });
        static::assertEquals('foo', $rut);

        $rut = Rut::make('foo', null, function () {
            return 'foo';
        });
        static::assertEquals('foo', $rut);

        $rut = Rut::make('foo', function () {
            return 'foo';
        });
        static::assertEquals('foo', $rut);
    }

    public function testClone()
    {
        $rutA = Rut::make('10.666.309-2');
        $rutB = $rutA->clone();

        static::assertEquals($rutA, $rutB);
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

        static::assertIsArray($ruts);
        static::assertCount(4, $ruts);

        static::assertEquals(18300252, $ruts[0]->num);
        static::assertEquals('K', $ruts[0]->vd);

        static::assertEquals(9306036, $ruts[1]->num);
        static::assertEquals(9, $ruts[1]->vd);

        static::assertEquals(21066859, $ruts[2]->num);
        static::assertEquals(4, $ruts[2]->vd);

        static::assertEquals(12903919, $ruts[3]->num);
        static::assertEquals(1, $ruts[3]->vd);
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

        static::assertIsArray($ruts);
        static::assertCount(4, $ruts);

        static::assertEquals(18300252, $ruts[0]->num);
        static::assertEquals('K', $ruts[0]->vd);

        static::assertEquals(9306036, $ruts[1]->num);
        static::assertEquals(9, $ruts[1]->vd);

        static::assertEquals(21066859, $ruts[2]->num);
        static::assertEquals(4, $ruts[2]->vd);

        static::assertEquals(12903919, $ruts[3]->num);
        static::assertEquals(1, $ruts[3]->vd);
    }

    public function testMakeManyReturnsEmptyArrayWhenNoArguments()
    {
        $ruts = Rut::many();

        static::assertIsArray($ruts);
        static::assertEmpty($ruts);
    }

    public function testManyOrThrow()
    {
        $this->expectException(InvalidRutException::class);
        $this->expectExceptionMessage('The given RUT [foo] is invalid');

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

        static::assertIsArray($ruts);
        static::assertEmpty($ruts);
    }

    public function testMakeOrThrowFailsWhenInvalidRut()
    {
        $this->expectException(InvalidRutException::class);
        $this->expectExceptionMessage('The given RUT [12345678-k] is invalid');

        Rut::makeOrThrow('12345678-k');
    }

    public function testMakeOrThrowFailsWhenInvalidRuts()
    {
        $this->expectException(InvalidRutException::class);
        $this->expectExceptionMessage('The given RUT is invalid');

        Rut::manyOrThrow([
            '18.300.252-k',
            ['foo'],
            'bar'
        ]);
    }

    public function testGetRut()
    {
        $rut = new Rut(10666309, 2);

        static::assertEquals(['num' => 10666309, 'vd' => 2], $rut->getRut());
    }

    public function testGetAndSet()
    {
        $rut = new Rut(10666309, 2);

        static::assertEquals(10666309, $rut->num);
        static::assertEquals(2, $rut->vd);

        static::assertEquals(10666309, $rut['num']);
        static::assertEquals(2, $rut['vd']);

        $rut->num = 'foo';
        $rut->vd = 'bar';

        static::assertEquals(10666309, $rut->num);
        static::assertEquals(2, $rut->vd);

        static::assertEquals(10666309, $rut['num']);
        static::assertEquals(2, $rut['vd']);

        $rut['num'] = 'foo';
        $rut['vd'] = 'bar';

        static::assertEquals(10666309, $rut->num);
        static::assertEquals(2, $rut->vd);

        static::assertEquals(10666309, $rut['num']);
        static::assertEquals(2, $rut['vd']);
    }

    public function testIssetAndUnset()
    {
        $rut = new Rut(10666309, 2);

        static::assertTrue(isset($rut->vd));
        static::assertTrue(isset($rut['vd']));
        static::assertTrue(isset($rut->num));
        static::assertTrue(isset($rut['num']));

        unset($rut->vd, $rut['vd'], $rut->num, $rut['num']);

        static::assertTrue(isset($rut->vd));
        static::assertTrue(isset($rut['vd']));
        static::assertTrue(isset($rut->num));
        static::assertTrue(isset($rut['num']));
    }

    public function testToJson()
    {
        $rut = new Rut(10666309, 2);

        static::assertJson($rut->toJson());
        static::assertJson(json_encode($rut));

        static::assertEquals('"10.666.309-2"', $rut->toJson());
        static::assertEquals('"10.666.309-2"', json_encode($rut));
    }

    public function testToString()
    {
        $rut = new Rut(10666309, 2);

        static::assertIsString($rut->__toString());
        static::assertEquals('10.666.309-2', $rut->__toString());
        static::assertIsString((string)$rut);
        static::assertEquals('10.666.309-2', (string)$rut);
    }

    public function testToArray()
    {
        $rut = new Rut(10666309, 2);

        static::assertIsArray($rut->toArray());

        static::assertEquals($array = [
            'num' => 10666309,
            'vd' => '2'
        ], $rut->toArray());
    }

    public function testSerialization()
    {
        $unserialized = unserialize(serialize(new Rut(10666309, 2)));

        static::assertInstanceOf(Rut::class, $unserialized);
        static::assertEquals(10666309, $unserialized->num);
        static::assertEquals(2, $unserialized->vd);
    }
}
