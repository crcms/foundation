<?php

namespace CrCms\Foundation\Tests\Enums;

use PHPUnit\Framework\TestCase;

class EnumTest extends TestCase
{
    public function testEnum()
    {
        $this->assertEquals(DemoEnum::TEST()->getValue(), 1);
        $this->assertEquals(DemoEnum::TEST()->getKey(), 'TEST');
        $this->assertEquals(DemoEnum::TEST_VALUE(), 1);
        $this->assertEquals(DemoEnum::TEST_KEY(), 'TEST');
    }
}
