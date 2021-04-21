<?php

namespace Tests;

use DarkGhostHunter\RutUtils\Rut;
use DarkGhostHunter\RutUtils\RutGenerator;
use PHPUnit\Framework\TestCase;

class RutGeneratorTest extends TestCase
{
    /** @var RutGenerator */
    protected $generator;

    protected function setUp(): void
    {
        $this->generator = new RutGenerator();
    }

    public function testMake()
    {
        static::assertInstanceOf(RutGenerator::class, RutGenerator::make());
    }

    public function testGenerateOne()
    {
        $rut = $this->generator->generate(1);

        static::assertInstanceOf(Rut::class, $rut);
        static::assertIsInt($rut->num);
        static::assertIsString($rut->vd);
    }

    public function testGenerateOneInArray()
    {
        $rut = $this->generator->generate(1, false);

        static::assertIsArray($rut);
        static::assertInstanceOf(Rut::class, $rut[0]);
        static::assertIsInt($rut[0]->num);
        static::assertIsString($rut[0]->vd);
    }

    public function testGenerateMany()
    {
        $ruts = $this->generator->generate($rand = rand(10, 20));

        static::assertIsArray($ruts);
        static::assertCount($rand, $ruts);

        foreach ($ruts as $rut) {
            static::assertInstanceOf(Rut::class, $rut);
        }
    }

    public function testGenerateStatic()
    {
        $generator = new class extends RutGenerator {
            public static $runs = 0;
            public static $results = [0,1,1,2,3,3,4,4,4,5,6,7,8,9];

            public function generate(int $iterations = 1, bool $unwrapSingle = true)
            {
                $result = static::$results[static::$runs];

                ++static::$runs;

                return $result;
            }

            public function getStatic()
            {
                return static::$static;
            }
        };

        $results = [];

        for ($i = 0; $i < 10; ++$i) {
            $results[] = $generator->generateStatic();
        }

        static::assertEquals([0,1,2,3,4,5,6,7,8,9], $results);
        static::assertEquals([0,1,2,3,4,5,6,7,8,9], $generator->getStatic());

        $generator->flushStatic();

        static::assertEmpty($generator->getStatic());
    }

    public function testAsCompany()
    {
        $ruts = $this->generator->asCompany()->generate($rand = rand(10, 20));

        static::assertIsArray($ruts);
        static::assertCount($rand, $ruts);

        foreach ($ruts as $rut) {
            static::assertTrue($rut->num >= Rut::COMPANY_RUT_BASE && $rut->num < RutGenerator::MAXIMUM_NUMBER);
        }
    }

    public function testAsPerson()
    {
        $ruts = $this->generator->asPerson()->generate($rand = rand(10, 20));

        static::assertIsArray($ruts);
        static::assertCount($rand, $ruts);

        foreach ($ruts as $rut) {
            static::assertTrue($rut->num >= RutGenerator::MINIMUM_NUMBER && $rut->num < Rut::COMPANY_RUT_BASE);
        }
    }

    public function testAsRutObject()
    {
        $rut = $this->generator->asObject()->generate();

        static::assertInstanceOf(Rut::class, $rut);
    }

    public function testAsStrict()
    {
        $ruts = $this->generator->asStrict()->generate($rand = rand(10, 20));

        static::assertIsArray($ruts);
        static::assertCount($rand, $ruts);

        foreach ($ruts as $rut) {
            static::assertIsString($rut);
            static::assertStringContainsString('.', $rut);
            static::assertStringContainsString('-', $rut);
        }
    }

    public function testAsBasic()
    {
        $ruts = $this->generator->asBasic()->generate($rand = rand(10, 20));

        static::assertIsArray($ruts);
        static::assertCount($rand, $ruts);

        foreach ($ruts as $rut) {
            static::assertIsString($rut);
            static::assertStringNotContainsString('.', $rut);
            static::assertStringContainsString('-', $rut);
        }
    }

    public function testAsRaw()
    {
        $ruts = $this->generator->asRaw()->generate($rand = rand(10, 20));

        static::assertIsArray($ruts);
        static::assertCount($rand, $ruts);

        foreach ($ruts as $rut) {
            static::assertIsString($rut);
            static::assertStringNotContainsString('.', $rut);
            static::assertStringNotContainsString('-', $rut);
        }
    }

    public function testWithoutDuplicates()
    {
        $this->generator = new class extends RutGenerator {
            protected function performGenerate(int $iterations): array
            {
                if ($iterations < 5) return ['four', 'five'];

                return ['one', 'one', 'two', 'two', 'three',];
            }
        };

        $ruts = $this->generator->withoutDuplicates()->asRaw()->generate($rand = 5);

        static::assertCount($rand, $ruts);
        static::assertEquals(['one', 'two', 'three', 'four', 'five'], $ruts);
    }

    public function testNotUnique()
    {
        $this->generator = new class extends RutGenerator {
            protected function performGenerate(int $iterations): array
            {
                return ['one', 'one', 'two', 'two', 'three'];
            }
        };

        $ruts = $this->generator->withDuplicates()->asRaw()->generate($rand = 5);

        static::assertCount($rand, $ruts);

        static::assertEquals(['one', 'one', 'two', 'two', 'three'], $ruts);
    }
}
