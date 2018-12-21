<?php

namespace Tests\Units;

use DarkGhostHunter\RutUtils\Rut;
use DarkGhostHunter\RutUtils\RutBuilder;
use PHPUnit\Framework\TestCase;

class RutBuilderTest extends TestCase
{

    /** @var RutBuilder */
    protected $builder;

    protected function setUp()
    {
        $this->builder = new RutBuilder();
    }

    public function testGenerateOne()
    {
        $rut = $this->builder->generate(1);

        $this->assertInstanceOf(Rut::class, $rut);
        $this->assertIsInt($rut->num);
        $this->assertIsString($rut->vd);
    }

    public function testGenerateOneInArray()
    {
        $rut = $this->builder->generate(1, false);

        $this->assertIsArray($rut);
        $this->assertInstanceOf(Rut::class, $rut[0]);
        $this->assertIsInt($rut[0]->num);
        $this->assertIsString($rut[0]->vd);
    }

    public function testGenerateMany()
    {
        $ruts = $this->builder->generate($rand = rand(10, 20));

        $this->assertIsArray($ruts);
        $this->assertCount($rand, $ruts);

        foreach ($ruts as $rut) {
            $this->assertInstanceOf(Rut::class, $rut);
        }
    }

    public function testAsCompany()
    {
        $ruts = $this->builder->asCompany()->generate($rand = rand(10, 20));

        $this->assertIsArray($ruts);
        $this->assertCount($rand, $ruts);

        foreach ($ruts as $rut) {
            $this->assertTrue($rut->num > 50000000 && $rut->num < 100000000);
        }
    }

    public function testAsPerson()
    {
        $ruts = $this->builder->asPerson()->generate($rand = rand(10, 20));

        $this->assertIsArray($ruts);
        $this->assertCount($rand, $ruts);

        foreach ($ruts as $rut) {
            $this->assertTrue($rut->num > 1000000 && $rut->num <= 50000000);
        }
    }

    public function testAsRutObject()
    {
        $rut = $this->builder->asObject()->generate();

        $this->assertInstanceOf(Rut::class, $rut);
    }

    public function testAsRaw()
    {
        $ruts = $this->builder->asRaw()->generate($rand = rand(10, 20));

        $this->assertIsArray($ruts);
        $this->assertCount($rand, $ruts);

        foreach ($ruts as $rut) {
            $this->assertIsString($rut);
            $this->assertNotContains('.', $rut);
            $this->assertNotContains('-', $rut);
        }
    }

    public function testAsString()
    {
        $ruts = $this->builder->asString()->generate($rand = rand(10, 20));

        $this->assertIsArray($ruts);
        $this->assertCount($rand, $ruts);

        foreach ($ruts as $rut) {
            $this->assertIsString($rut);
            $this->assertContains('.', $rut);
            $this->assertContains('-', $rut);
        }
    }

    public function testUnique()
    {
        $this->builder = new class extends RutBuilder {
            protected function performGenerate(int $iterations)
            {
                if ($iterations < 5) return ['four', 'five'];
                return ['one', 'one', 'two', 'two', 'three',];
            }
        };

        $ruts = $this->builder->unique()->asRaw()->generate($rand = 5);

        $this->assertCount($rand, $ruts);
        $this->assertEquals([
            'one', 'two', 'three', 'four', 'five',
        ], $ruts);
    }

    public function testNotUnique()
    {
        $this->builder = new class extends RutBuilder {
            protected function performGenerate(int $iterations)
            {
                return ['one', 'one', 'two', 'two', 'three',];
            }
        };

        $ruts = $this->builder->notUnique()->asRaw()->generate($rand = 5);

        $this->assertCount($rand, $ruts);

        $this->assertTrue(count($ruts) !== array_unique($ruts));
    }
}
