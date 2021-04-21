<?php

namespace Tests;

use DarkGhostHunter\RutUtils\Rut;
use PHPUnit\Framework\TestCase;

class RutCallbacksTest extends TestCase
{
    public function testDefaultNoCallbacks()
    {
        static::assertEmpty(Rut::getAfterCallbacks());
    }

    public function testAddsCallback()
    {
        $foo = function ($ruts) {
            return $ruts;
        };

        $bar = $foo;
        $qux = $bar;

        $callbacks = [$foo, $bar, $qux];

        Rut::after($foo);
        Rut::after($bar);
        Rut::after($qux);

        static::assertEquals($callbacks, Rut::getAfterCallbacks());
    }

    public function testRegisterAndExecutesCallback()
    {
        $order = [];

        $foo = function () use (&$order) {
            $order[] = 'foo';
            return 3;
        };

        Rut::after($foo);

        static::assertEquals(3, Rut::many('18300252K', '18300252K'));
        static::assertEquals(['foo'], $order);
    }

    public function testRegisterAndExecutesCallbacksInOrder()
    {
        $order = [];

        $foo = function () use (&$order) {
            $order[] = 'foo';
            return 3;
        };

        $bar = function ($ruts) use (&$order) {
            $order[] = 'bar';
            return $ruts * 10;
        };

        $qux = function ($ruts) use (&$order) {
            $order[] = 'qux';
            return $ruts / 2;
        };

        Rut::after($foo);
        Rut::after($bar);
        Rut::after($qux);

        static::assertEquals(15, Rut::many('18300252K', '18300252K'));
        static::assertEquals(['foo', 'bar', 'qux'], $order);

        $order = [];

        static::assertEquals(15, Rut::manyOrThrow('18300252K', '18300252K'));
        static::assertEquals(['foo', 'bar', 'qux'], $order);
    }

    public function testFlushesCallbacks()
    {
        $foo = function ($ruts) {
            return 6;
        };

        $bar = $foo;
        $qux = $bar;

        $callbacks = [$foo, $bar, $qux];

        Rut::after($foo);
        Rut::after($bar);
        Rut::after($qux);

        static::assertEquals($callbacks, Rut::getAfterCallbacks());

        Rut::flushAfterCallbacks();

        static::assertEmpty(Rut::getAfterCallbacks());
        static::assertIsArray(Rut::many('18300252K'));
    }

    public function testCallsWithoutCallbacks()
    {
        Rut::after(function () {
            return 3;
        });

        $expected = Rut::withoutCallbacks(function () {
            return Rut::many('18300252K', '18300252K');
        });

        static::assertIsArray($expected);
        static::assertCount(2, $expected);

        $expected = Rut::withoutCallbacks(function () {
            return Rut::manyOrThrow('18300252K', '18300252K');
        });

        static::assertIsArray($expected);
        static::assertCount(2, $expected);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        Rut::flushAfterCallbacks();
    }
}
