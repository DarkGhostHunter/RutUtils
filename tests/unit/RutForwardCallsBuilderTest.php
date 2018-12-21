<?php

namespace Tests\Unit;

use DarkGhostHunter\RutUtils\Rut;
use PHPUnit\Framework\TestCase;

class RutForwardCallsBuilderTest extends TestCase
{
    protected function setUp()
    {
        \Mockery::mock('overload:DarkGhostHunter\RutUtils\RutBuilder')
            ->shouldReceive([
                'generate' => true,
                'unique' => true,
                'notUnique' => true,
                'asCompany' => true,
                'asPerson' => true,
                'asRaw' => true,
                'asString' => true,
                'asObject' => true,
            ]);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testGenerate()
    {
        $this->assertTrue(Rut::generate('argument'));
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testUnique()
    {
        $this->assertTrue(Rut::unique());
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testNotUnique()
    {
        $this->assertTrue(Rut::notUnique());
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testAsCompany()
    {
        $this->assertTrue(Rut::asCompany());
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testAsPerson()
    {
        $this->assertTrue(Rut::asPerson());
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testAsRaw()
    {
        $this->assertTrue(Rut::asRaw());
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testAsString()
    {
        $this->assertTrue(Rut::asString());
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testAsObject()
    {
        $this->assertTrue(Rut::asObject());
    }


}