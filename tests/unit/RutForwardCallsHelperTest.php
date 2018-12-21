<?php

namespace Tests\Unit;

use DarkGhostHunter\RutUtils\Rut;
use DarkGhostHunter\RutUtils\RutHelper;
use PHPUnit\Framework\TestCase;


class RutForwardCallsHelperTest extends TestCase
{

    protected function setUp()
    {
        \Mockery::mock('overload:' . RutHelper::class)
            ->shouldReceive([
                'validate' => true,
                'areEqual' => true,
                'rectify' => true,
                'isPerson' => true,
                'isCompany' => true,
            ]);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testValidate()
    {
        $this->assertTrue(Rut::validate('argument'));
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testAreEqual()
    {
        $this->assertTrue(Rut::areEqual('247009094', '24.700.909-4'));
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testFilter()
    {
        $this->assertTrue(Rut::validate('argument'));
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testRectify()
    {
        $this->assertTrue(Rut::rectify('argument'));
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testIsPerson()
    {
        $this->assertTrue(Rut::isPerson('argument'));
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testIsCompany()
    {
        $this->assertTrue(Rut::isCompany('argument'));
    }

}